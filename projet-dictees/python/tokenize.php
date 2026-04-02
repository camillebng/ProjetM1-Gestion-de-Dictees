<?php
// Fonction de tokénisation
function tokenize($text) {
    if (empty($text)) return [];
    preg_match_all("/[\w\-]+'?/u", $text, $matches);
    return $matches[0] ?? [];
}

try {
    $dsn = "mysql:host=localhost;dbname=gr4m1IDL;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
    ];
    $mydb = new PDO($dsn, "m2dilipem", "m2dilipem", $options);
    echo "Connexion réussie\n";

    // Tokénisation dictée prof
    $query_prof = "SELECT id_dict, contenu_prof FROM version_prof";
    $stmt_prof_select = $mydb->query($query_prof);
    $stmt_prof_insert = $mydb->prepare("INSERT INTO toks_prof (id_dict_fk, tok_prof, position_prof) VALUES (?, ?, ?)");

    $mydb->beginTransaction();
    while ($row = $stmt_prof_select->fetch()) {
        $tokens = tokenize($row['contenu_prof']);
        $i = 1;
        foreach ($tokens as $token) {
            $stmt_prof_insert->execute([$row['id_dict'], $token, $i]);
            $i++;
        }
    }
    $mydb->commit(); 
    echo "Tokens profs insérés.\n";


    // Tokénisation dictée élève
    $query_eleve = "SELECT e.dict_fk, e.contenu_eleve FROM version_eleve e";
    $stmt_eleve_select = $mydb->query($query_eleve);
    $stmt_eleve_insert = $mydb->prepare("INSERT INTO toks_eleve (id_dict_fk, tok_eleve, position_eleve) VALUES (?, ?, ?)");

    $mydb->beginTransaction(); 
    while ($row = $stmt_eleve_select->fetch()) {
        $tokens = tokenize($row['contenu_eleve']);
        $i = 1;
        foreach ($tokens as $token) {
            $stmt_eleve_insert->execute([$row['dict_fk'], $token, $i]);
            $i++;
        }
    }
    $mydb->commit(); 
    echo "Tokens élèves insérés.\n";


    // Comparaison des tokens et calcul du score 
    echo "Lancement de la comparaison globale...\n";

    $sql_compare = "
        UPDATE toks_eleve e
        INNER JOIN toks_prof p ON e.id_dict_fk = p.id_dict_fk 
                               AND e.position_eleve = p.position_prof
        SET e.est_correct = 1
        WHERE e.tok_eleve = p.tok_prof
    ";

    $count = $mydb->exec($sql_compare);

    echo "Tokénisation et comparaison terminées. $count mots marqués comme corrects.\n";

    $sql_score = "
                UPDATE version_eleve v
                SET v.score_sur_20 = (
                FROM toks_prof p 
                LEFT JOIN toks_eleve e ON p.id_dict_fk = e.id_dict_fk AND p.position_prof = e.position_eleve
                WHERE p.id_dict_fk = v.dict_fk"

    $mydb->exec($sql_score)


} catch (PDOException $e) {
    if (isset($mydb) && $mydb->inTransaction()) $mydb->rollBack();
    echo "Erreur base de données : " . $e->getMessage() . "\n";
}

$mydb = null;
?>