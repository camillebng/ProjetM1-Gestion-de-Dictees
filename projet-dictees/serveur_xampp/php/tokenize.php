<?php
// Fonction de tokénisation
function tokenize($text) {
    if (empty($text)) return [];
    preg_match_all("/[\w\-]+'?/u", $text, $matches);
    return $matches[0] ?? [];
}

try {
    $dsn = "mysql:host=localhost;dbname=gr4m1idl;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
    ];
    $mydb = new PDO($dsn, "root", "", $options);
    echo "Connexion réussie\n";

    // Tokénisation dictée prof
    $query_prof = "SELECT id_dict, contenu_prof FROM version_prof";
    $stmt_prof_select = $mydb->query($query_prof);
    $stmt_prof_insert = $mydb->prepare("INSERT INTO toks_prof (id_dict_fk, tok_prof, position_prof) VALUES (?, ?, ?)");

    $stmt_mark_prof = $mydb->prepare("UPDATE version_prof SET is_tokenized = 1 WHERE id_dict = ?");

    $mydb->beginTransaction();
    while ($row = $stmt_prof_select->fetch()) {
        $tokens = tokenize($row['contenu_prof']);
        $i = 1;
        foreach ($tokens as $token) {
            $stmt_prof_insert->execute([$row['id_dict'], $token, $i]);
            $i++;
        }
        $stmt_mark_prof->execute([$row['id_dict']]);
    }
    $mydb->commit(); 
    echo "Tokens profs insérés.\n";


    // Tokénisation dictées élève 
    $query_eleve = "SELECT e.dict_fk, e.contenu_eleve FROM version_eleve e";
    $stmt_eleve_select = $mydb->query($query_eleve);
    $stmt_eleve_insert = $mydb->prepare("INSERT INTO toks_eleve (id_dict_fk, tok_eleve, position_eleve) VALUES (?, ?, ?)");
    
    $stmt_mark_eleve = $mydb->prepare("UPDATE version_eleve SET is_tokenized_e = 1 WHERE dict_fk = ?");

    $mydb->beginTransaction(); 
    while ($row = $stmt_eleve_select->fetch()) {
        $tokens = tokenize($row['contenu_eleve']);
        $i = 1;
        foreach ($tokens as $token) {
            $stmt_eleve_insert->execute([$row['dict_fk'], $token, $i]);
            $i++;
        }
        $stmt_mark_eleve->execute([$row['dict_fk']]);
    }
    $mydb->commit(); 
    echo "Tokens élèves insérés.\n";


    // Comparaison des tokens élèves vs. prof
    echo "Lancement de la comparaison globale...\n";
    $sql_compare = "
        UPDATE toks_eleve e
        INNER JOIN toks_prof p ON e.id_dict_fk = p.id_dict_fk 
                               AND e.position_eleve = p.position_prof
        SET e.est_correct = 1
        WHERE e.tok_eleve = p.tok_prof
    ";
    $count = $mydb->exec($sql_compare);
    echo "Comparaison terminée. $count mots marqués comme corrects.\n";


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
    $mydb->exec($sql_score);
    echo "Scores mis à jour dans version_eleve.\n";

} catch (PDOException $e) {
    if (isset($mydb) && $mydb->inTransaction()) $mydb->rollBack();
    echo "Erreur base de données : " . $e->getMessage() . "\n";
}

$mydb = null;
?>