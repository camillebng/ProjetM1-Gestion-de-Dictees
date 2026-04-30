<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'gr4m1idl';
$username = 'root';
$password = '';

$connexion = new mysqli($host, $username, $password, $dbname);

if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

// Récupère les données du formulaire

$auteur = $_POST['auteur'];
$date = $_POST['date'];
 if ($auteur === 'prof') {
    $contenu_prof = $_POST['contenu'];
    $type = $_POST['type'];
    $niveau = $_POST['niveau'];
$titre = $_POST['titre'];
} elseif ($auteur === 'eleve') {
    $contenu_eleve = $_POST['contenu'];
    //$score = $_POST['score'];
}

// Requête SQL
// Note : id_dict est auto-incrémenté et is_tokenized par défaut = 1
if ($auteur === 'prof') {
    $requete = "INSERT INTO version_prof (type, titre, niveau, date, contenu_prof, is_tokenized)
                VALUES (?, ?, ?, ?, ?, 1)";
    
    $statement = $connexion->prepare($requete);
        if (!$statement) {
    die("Erreur de préparation : " . $connexion->error);
}
    $statement->bind_param("sssss", $type, $titre, $niveau, $date, $contenu_prof);
    if ($statement->execute()) {
        header("Location: ../modification.php");
        exit();
    } else {
        die(" Erreur d'exécution : " . $statement->error);
    }
} elseif ($auteur === 'eleve') {
    $requete = "INSERT INTO version_eleve (date, contenu_eleve, is_tokenized_e)
                VALUES (?, ?, 1)";
    
    $statement = $connexion->prepare($requete);
        if (!$statement) {
    die("Erreur de préparation : " . $connexion->error);
}
    $statement->bind_param("ss", $date, $contenu_eleve);
        if ($statement->execute()) {
        header("Location: ../modification.php");
        exit();
    } else {
        die(" Erreur d'exécution : " . $statement->error);
    }

}
$connexion->close();
?>