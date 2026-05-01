
<?php
// Active les erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Appel aux fichiers
require_once 'config.php';
require_once 'tokenize.php'; 

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(" Erreur de connexion : " . $e->getMessage());
}

// Récup formulaire
$id_dict = $_POST['id_dict'] ?? null;
$type = $_POST['type'] ?? '';
$niveau = $_POST['niveau'] ?? '';
$titre = $_POST['titre'] ?? '';
$date = !empty($_POST['date']) ? $_POST['date'] : '0000-00-00';
$contenu = $_POST['contenu'] ?? '';
$auteur = $_POST['auteur'] ?? 'prof'; // Par défaut : 'prof'

if (!$id_dict) {
    die(" ID de dictée manquant.");
}

// Requête SQL pour mettre à jour
try {
    $sql = "UPDATE version_prof SET
            type = ?,
            titre = ?,
            niveau = ?,
            contenu_prof = ?,
            Date = ?
            WHERE id_dict = ?";

    $statement = $pdo->prepare($sql);
    //Relancement de la tokenization et MAJ BDD
    $success = $statement->execute([$type, $titre, $niveau, $contenu, $date, $id_dict]);

    if ($success && $statement->rowCount() > 0) {
        // Relance tokeniz
        executer_tokenisation($pdo, $id_dict, $auteur);

        // Relance le POSTag
        $id_securise = escapeshellarg($id_dict);
        $type_securise = escapeshellarg($auteur);
        $commande_pos = "python3 ../scripts/pos_tagging.py " . $id_securise . " " . $type_securise;
        $output_pos = shell_exec($commande_pos);

        // Redirection quand fini (succès)
        header("Location: ../modification.php?success=1");
        exit();
    } else {
        die(" Aucune dictée mise à jour");
    }

} catch (PDOException $e) {
    die(" Erreur lors de la mise à jour : " . $e->getMessage());
}
?>