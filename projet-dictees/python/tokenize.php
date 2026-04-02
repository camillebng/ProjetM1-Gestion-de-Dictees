<?php

function tokenize($text) {
    if (!$text) {
        return [];
    }

    preg_match_all('/[\w\-]+\'?/', $text, $matches);
    return $matches[0] ?? [];
}

$servername = "localhost";
$username = "m2dilipem";
$password = "m2dilipem";
$dbname = "gr4m1IDL";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully\n";

    // Tokénisation des dictées prof 
    $sql_prof = "SELECT id_dict, contenu_prof FROM version_prof";
    $result_prof = $conn->query($sql_prof); // Correction : $sql_prof au lieu de $sql


    $stmt_p = $conn->prepare("INSERT INTO toks_prof (id_dict, tok_prof, position_prof) VALUES (?, ?, ?)");

    foreach ($result_prof as $row) {
        $id_dict = $row['id_dict']; 
        $content_prof = $row['contenu_prof'];
        
        $tokens = tokenize($content_prof);
        
        $i = 1; 
        foreach ($tokens as $token) {
            $stmt_p->execute([$id_dict, $token, $i]); 
            $i++; 
        }
    }

    // Tokénisation des dictées élèves 
    $sql_eleve = "SELECT e.dict_fk, e.contenu_eleve 
                  FROM version_eleve e 
                  JOIN version_prof p ON p.id_dict = e.dict_fk";
    
    $result_eleve = $conn->query($sql_eleve); 


    $stmt_e = $conn->prepare("INSERT INTO toks_eleve (id_dict, tok_eleve, position_eleve) VALUES (?, ?, ?)");

    foreach ($result_eleve as $row) {
        $id_dict = $row['dict_fk'];
        $content_eleve = $row['contenu_eleve'];
        
        $tokens = tokenize($content_eleve);
        
        $i = 1; 
        foreach ($tokens as $token) {
            $stmt_e->execute([$id_dict, $token, $i]); 
            $i++; 
        }
    }   
    
    echo "Traitement terminé avec succès.";

} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

?>