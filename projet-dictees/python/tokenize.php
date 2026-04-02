<?php
// Fonction de tokénisation
function tokenize($text) {
    if (empty($text)) {
        return [];
    }
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

   // Tokénisation des dictées prof
    $query_prof = "SELECT id_dict, contenu_prof FROM version_prof";
    $stmt_prof_select = $mydb->query($query_prof);
    
    $stmt_prof_insert = $mydb->prepare("INSERT INTO toks_prof (id_dict, tok_prof, position_prof) VALUES (?, ?, ?)");

    $mydb->beginTransaction();
    
    while ($row = $stmt_prof_select->fetch()) {
        $id_dict = $row['id_dict'];
        $content_prof = $row['contenu_prof'];
        
        $tokens = tokenize($content_prof);
        
        $i = 1;
        foreach ($tokens as $token) {
            $stmt_prof_insert->execute([$id_dict, $token, $i]);
            $i++;
        }
    }
    $mydb->commit(); 


// Tokénisation des dictées version élève
    $query_eleve = "SELECT e.dict_fk, e.contenu_eleve 
                    FROM version_eleve e 
                    JOIN version_prof p ON p.id_dict = e.dict_fk";
    $stmt_eleve_select = $mydb->query($query_eleve);
    
    $stmt_eleve_insert = $mydb->prepare("INSERT INTO toks_eleve (id_dict, tok_eleve, position_eleve) VALUES (?, ?, ?)");

    $mydb->beginTransaction(); 
    
    while ($row = $stmt_eleve_select->fetch()) {
        $id_dict_origine = $row['dict_fk'];
        $content_eleve = $row['contenu_eleve'];
        
        $tokens = tokenize($content_eleve);
        
        $i = 1;
        foreach ($tokens as $token) {
            $stmt_eleve_insert->execute([$id_dict_origine, $token, $i]);
            $i++;
        }
    }
    $mydb->commit(); 

    echo "Tokénisation terminée\n";

} catch (PDOException $e) {
    if ($mydb->inTransaction()) {
        $mydb->rollBack();
    }
    echo "Erreur base de données : " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Erreur générale : " . $e->getMessage() . "\n";
}

$mydb = null;

?>