<?php
require_once 'config.php';

// Fonction de tokénisation 
function tokenize($text) {
    if (empty($text)) return [];
    preg_match_all("/[\w\-]+'?/u", $text, $matches);
    return $matches[0] ?? [];
}

try {
    // Tokénisation dictées profs
    $query_prof = "SELECT id_dict, contenu_prof FROM version_prof";
    $stmt_prof_select = $pdo->query($query_prof);
    $stmt_prof_insert = $pdo->prepare("INSERT INTO toks_prof (id_dict_fk, tok_prof, position_prof) VALUES (?, ?, ?)");
    $stmt_mark_prof = $pdo->prepare("UPDATE version_prof SET is_tokenized = 1 WHERE id_dict = ?");

    $pdo->beginTransaction();
    while ($row = $stmt_prof_select->fetch()) {
        $tokens = tokenize($row['contenu_prof']);
        $i = 1;
        foreach ($tokens as $token) {
            $stmt_prof_insert->execute([$row['id_dict'], $token, $i]);
            $i++;
        }
        $stmt_mark_prof->execute([$row['id_dict']]);
    }
    $pdo->commit(); 

    // Tokénisation dictées élèves
    $query_eleve = "SELECT e.dict_fk, e.contenu_eleve FROM version_eleve e";
    $stmt_eleve_select = $pdo->query($query_eleve);
    $stmt_eleve_insert = $pdo->prepare("INSERT INTO toks_eleve (id_dict_fk, tok_eleve, position_eleve) VALUES (?, ?, ?)");
    $stmt_mark_eleve = $pdo->prepare("UPDATE version_eleve SET is_tokenized_e = 1 WHERE dict_fk = ?");

    $pdo->beginTransaction(); 
    while ($row = $stmt_eleve_select->fetch()) {
        $tokens = tokenize($row['contenu_eleve']);
        $i = 1;
        foreach ($tokens as $token) {
            $stmt_eleve_insert->execute([$row['dict_fk'], $token, $i]);
            $i++;
        }
        $stmt_mark_eleve->execute([$row['dict_fk']]);
    }
    $pdo->commit(); 

    // Comparaison des tokens élèves avec profs
    $sql_compare = "
        UPDATE toks_eleve e
        INNER JOIN toks_prof p ON e.id_dict_fk = p.id_dict_fk 
                               AND e.position_eleve = p.position_prof
        SET e.est_correct = 1
        WHERE e.tok_eleve = p.tok_prof
    ";
    $pdo->exec($sql_compare);

    // Calcul du score sur 20
    $sql_score = "
        UPDATE version_eleve v
        SET v.score_sur_20 = (
            SELECT (SUM(CASE WHEN e.est_correct = 1 THEN 1 ELSE 0 END) / COUNT(p.id_toks)) * 20
            FROM toks_prof p
            LEFT JOIN toks_eleve e ON p.id_dict_fk = e.id_dict_fk AND p.position_prof = e.position_eleve
            WHERE p.id_dict_fk = v.dict_fk
        )
    ";
    $pdo->exec($sql_score);

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    echo "Erreur : " . $e->getMessage();
}

$pdo = null;
?>