<?php
// Fonction de tokénisation (pure)
function tokenize_text($text) {
    if (empty($text)) return [];
    // Modification technique : utilisation de \p{L} et /u pour capturer correctement tous les accents
    preg_match_all("/[\p{L}0-9\-]+'?/u", $text, $matches);
    return $matches[0] ?? [];
}

// Protocole de Token/MAJ BDD/Scores
function executer_tokenisation($pdo, $id, $type_auteur) {
    try {
        // Supprimer les anciens tokens pour MAJ de CETTE dictée
        if ($type_auteur === 'prof') {
            $sql_delete = "DELETE FROM toks_prof WHERE id_dict_fk = ?";
        } else {
            $sql_delete = "DELETE FROM toks_eleve WHERE id_dict_fk = (SELECT dict_fk FROM version_eleve WHERE id_dict_eleve = ?)";
        }
        $statement_delete = $pdo->prepare($sql_delete);
        $statement_delete->execute([$id]);

        // Token du nouveau texte
        if ($type_auteur === 'prof') {
            $stmt = $pdo->prepare("SELECT contenu_prof FROM version_prof WHERE id_dict = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && !empty($row['contenu_prof'])) {
                $tokens = tokenize_text($row['contenu_prof']);
                $stmt_ins = $pdo->prepare("INSERT INTO toks_prof (id_dict_fk, tok_prof, position_prof) VALUES (?, ?, ?)");
                foreach ($tokens as $i => $token) {
                    $stmt_ins->execute([$id, $token, $i + 1]);
                }
                $pdo->prepare("UPDATE version_prof SET is_tokenized = 1 WHERE id_dict = ?")->execute([$id]);
            }
        } else { //  pour les éleves
            $stmt = $pdo->prepare("SELECT dict_fk, contenu_eleve FROM version_eleve WHERE id_dict_eleve = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && !empty($row['contenu_eleve'])) {
                $dict_fk = $row['dict_fk'];
                $tokens = tokenize_text($row['contenu_eleve']);
                $stmt_ins = $pdo->prepare("INSERT INTO toks_eleve (id_dict_fk, tok_eleve, position_eleve, est_correct) VALUES (?, ?, ?, 0)");
                foreach ($tokens as $i => $token) {
                    $stmt_ins->execute([$dict_fk, $token, $i + 1]);
                }

                // Comparaison immédiate des tokens (correct ou non)
                // Modification technique : ajout de BINARY pour rendre la comparaison sensible aux accents
                $sql_compare = "
                    UPDATE toks_eleve e
                    INNER JOIN toks_prof p ON e.id_dict_fk = p.id_dict_fk AND e.position_eleve = p.position_prof
                    SET e.est_correct = (CASE WHEN BINARY e.tok_eleve = BINARY p.tok_prof THEN 1 ELSE 0 END)
                    WHERE e.id_dict_fk = ?";
                $pdo->prepare($sql_compare)->execute([$dict_fk]);

                // Calcul et mise à jour du score sur 20
                $sql_score = "
                    UPDATE version_eleve v
                    SET v.score_sur_20 = (
                        SELECT (SUM(CASE WHEN e.est_correct = 1 THEN 1 ELSE 0 END) / COUNT(p.id_toks)) * 20
                        FROM toks_prof p
                        LEFT JOIN toks_eleve e ON p.id_dict_fk = e.id_dict_fk AND p.position_prof = e.position_eleve
                        WHERE p.id_dict_fk = v.dict_fk
                    ),
                    v.is_tokenized_e = 1
                    WHERE v.id_dict_eleve = ?";
                $pdo->prepare($sql_score)->execute([$id]);
            }
        }
    } catch (PDOException $e) {
        //En cas d'erreur
        error_log("Erreur Tokenisation : " . $e->getMessage());
        echo " Erreur lors de la tokénisation : " . $e->getMessage();
        throw $e;
    }
}
?>