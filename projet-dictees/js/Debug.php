<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base de données
$host = 'localhost';
$dbname = 'gr4m1idl';
$username = 'root';
$password = '';

$connexion = new mysqli($host, $username, $password, $dbname);

// Vérifie la connexion
if ($connexion->connect_error) {
    die("❌ Échec de la connexion : " . $connexion->connect_error);
}

// Debug : Affiche les données reçues
echo "<pre>Données POST reçues : ";
var_dump($_POST);
echo "</pre>";

// Vérifie que toutes les données sont présentes
if (empty($_POST['type']) || empty($_POST['niveau']) || empty($_POST['titre']) || empty($_POST['contenu'])) {
    die("❌ Erreur : Données manquantes dans le formulaire.");
}

// Récupère les données
$type = $_POST['type'];
$niveau = $_POST['niveau'];
$titre = $_POST['titre'];
$contenu = $_POST['contenu'];

// Requête SQL
$requete = "INSERT INTO version_prof (type, titre, niveau, contenu_prof, is_tokenized) VALUES (?, ?, ?, ?, 1)";
echo "Requête SQL : $requete<br>"; // Debug

// Prépare la requête
$statement = $connexion->prepare($requete);
if (!$statement) {
    die("❌ Erreur de préparation : " . $connexion->error);
}

// Bind les paramètres
$success = $statement->bind_param("sssss", $type, $titre, $niveau, $contenu);
if (!$success) {
    die("❌ Erreur de bind_param : " . $statement->error);
}

// Exécute la requête
if ($statement->execute()) {
    echo "✅ Insertion réussie ! ID généré : " . $statement->insert_id . "<br>";

    // Vérifie que la ligne a bien été insérée
    $result = $connexion->query("SELECT * FROM version_prof WHERE titre = '$titre' AND type = '$type'");
    if ($result->num_rows > 0) {
        echo "✅ Ligne trouvée en base : ";
        var_dump($result->fetch_assoc());
    } else {
        echo "❌ Aucune ligne trouvée en base après insertion !";
    }

    // Redirige après vérification
    header("Location: ../modification.php");
    exit();
} else {
    die("❌ Erreur d'exécution : " . $statement->error);
}

// Ferme la connexion
$statement->close();
$connexion->close();
?>