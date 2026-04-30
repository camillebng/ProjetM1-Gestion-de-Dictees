<?php
// Active les erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
require_once 'config.php';

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

try {
    $statement = $pdo->prepare($requete);

    if ($statement->execute([$type, $titre, $niveau, $contenu, $date, $id_dict])) {
        // Redirige vers la page de modification avec un message de succès
        header("Location: ../modification.php?success=1");
        exit();
    }
} catch (PDOException $e) {
    die("❌ Erreur lors de la mise à jour : " . $e->getMessage());
}
?>