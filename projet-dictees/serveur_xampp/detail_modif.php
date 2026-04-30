<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'gr4m1IDL';
$username = 'root';
$password = '';

$connexion = new mysqli($host, $username, $password, $dbname);

if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

// Récupère l'ID de la dictée depuis l'URL
$id_dict = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_dict <= 0) {
    die("ID de dictée invalide.");
}

// Requête pour récupérer la dictée
$requete = "SELECT * FROM version_prof WHERE id_dict = ?";
$statement = $connexion->prepare($requete);
$statement->bind_param("i", $id_dict);
$statement->execute();
$resultat = $statement->get_result();

if ($resultat->num_rows === 0) {
    die("Dictée non trouvée.");
}

// Récupère les données de la dictée
$dictee = $resultat->fetch_assoc();

// Ferme la connexion
$statement->close();
$connexion->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de Modification - Gestion des Dictées</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="nav-menu">
        <a href="index.html">Accueil</a> |
        <a href="saisie.html">Saisie</a> |
        <a href="modification.php">Modifier</a> |
        <a href="visualisation.html">Tendances</a>
    </div>
    <div class="header">
        <h1>Modifier la Dictée</h1>
    </div>
    <div class="main-container clearfix">
        <form action="php/modifier_dictee.php" method="POST">
            <!-- Champ caché pour l'ID de la dictée -->
            <input type="hidden" name="id_dict" value="<?php echo htmlspecialchars($dictee['id_dict']); ?>">

            <div class="form-row">
                <div class="form-group">
                    <label for="type">Type</label>
                    <select id="type" name="type">
                        <option value="mot" <?php echo ($dictee['type'] === 'mot') ? 'selected' : ''; ?>>Mots</option>
                        <option value="phrase" <?php echo ($dictee['type'] === 'phrase') ? 'selected' : ''; ?>>Phrases</option>
                        <option value="texte" <?php echo ($dictee['type'] === 'texte') ? 'selected' : ''; ?>>Texte</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="niveau">Niveau</label>
                    <select id="niveau" name="niveau">
                        <option value="cp" <?php echo ($dictee['niveau'] === 'cp') ? 'selected' : ''; ?>>CP</option>
                        <option value="ce1" <?php echo ($dictee['niveau'] === 'ce1') ? 'selected' : ''; ?>>CE1</option>
                        <option value="ce2" <?php echo ($dictee['niveau'] === 'ce2') ? 'selected' : ''; ?>>CE2</option>
                        <option value="cm1" <?php echo ($dictee['niveau'] === 'cm1') ? 'selected' : ''; ?>>CM1</option>
                        <option value="cm2" <?php echo ($dictee['niveau'] === 'cm2') ? 'selected' : ''; ?>>CM2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="titre">Titre</label>
                    <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($dictee['titre']); ?>">
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($dictee['date']); ?>">
                </div>
            </div>
            <div class="textarea-group">
                <textarea id="contenu" name="contenu"><?php echo htmlspecialchars($dictee['contenu_prof']); ?></textarea>
            </div>
            <button type="submit" class="submit-btn">Mettre à jour</button>
        </form>
    </div>
</body>
</html>