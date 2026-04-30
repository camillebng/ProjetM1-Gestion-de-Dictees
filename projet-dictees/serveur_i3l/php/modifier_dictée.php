<?php
// Active les erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
$host = 'localhost';
$dbname = 'gr4m1IDL';
$username = 'm2dilipem';
$password = 'm2dilipem';

$connexion = new mysqli($host, $username, $password, $dbname);

if ($connexion->connect_error) {
    die(" Échec de la connexion : " . $connexion->connect_error);
}

// Récup formulaire
$id_dict = $_POST['id_dict'];
$type = $_POST['type'];
$niveau = $_POST['niveau'];
$titre = $_POST['titre'];
$date = !empty($_POST['date']) ? $_POST['date'] : '0000-00-00'; // Valeur par défaut si vide
$contenu = $_POST['contenu'];

if (empty($id_dict)) {
    die(" ID de dictée manquant.");
}

// Requête SQL pour mettre à jour
$requete = "UPDATE version_prof SET
            type = ?,
            titre = ?,
            niveau = ?,
            contenu_prof = ?,
            Date = ?  -- 
            WHERE id_dict = ?";

$statement = $connexion->prepare($requete);
if (!$statement) {
    die("❌ Erreur de préparation : " . $connexion->error);
}

$statement->bind_param("sssssi", $type, $titre, $niveau, $contenu, $date, $id_dict);

if ($statement->execute()) {
    // Redirige vers la page de modification avec un message de succès
    header("Location: ../modification.php?success=1");
    exit();
} else {
    die("❌ Erreur lors de la mise à jour : " . $statement->error);
}

$statement->close();
$connexion->close();
?>