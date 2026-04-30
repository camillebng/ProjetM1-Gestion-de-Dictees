<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once 'config.php'; 

// Récupère les données du formulaire
$auteur = $_POST['auteur'] ?? '';
$date = $_POST['date'] ?? '';

if ($auteur === 'prof') {
    $type = $_POST['type'];
    $niveau = $_POST['niveau'];
    $titre = $_POST['titre'];
    $contenu_prof = $_POST['contenu'];

    try {
        // Insertion SQL dictées profs

        $sql = "INSERT INTO version_prof (type, titre, niveau, contenu_prof, is_tokenized) 
                VALUES (?, ?, ?, ?, 1)";
        
        $statement = $pdo->prepare($sql);
        $statement->execute([$type, $titre, $niveau, $contenu_prof]);

        header("Location: ../modification.php?success=1");
        exit();

    } catch (PDOException $e) {
        die("Erreur d'exécution Prof : " . $e->getMessage());
    }

} elseif ($auteur === 'eleve') {
    $contenu_eleve = $_POST['contenu'];

    try {
        // Insertion SQL dictées élèves
        $sql = "INSERT INTO version_eleve (date, contenu_eleve, is_tokenized_e) 
                VALUES (?, ?, 1)";
        
        $statement = $pdo->prepare($sql);
        $statement->execute([$date, $contenu_eleve]);

        header("Location: ../modification.php?success=1");
        exit();

    } catch (PDOException $e) {
        die("Erreur d'exécution Élève : " . $e->getMessage());
    }
}
?>