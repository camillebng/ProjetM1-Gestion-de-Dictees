<?php
// Connexion à la base de données
require_once 'php/config.php';

// Récupère l'ID de la dictée depuis l'URL
$id_dict = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_dict <= 0) {
    die("ID de dictée invalide.");
}

// Requête pour récupérer la dictée
$requete = "SELECT * FROM version_prof WHERE id_dict = ?";
$statement = $pdo->prepare($requete);
$statement->execute([$id_dict]);
$dictee = $statement->fetch(PDO::FETCH_ASSOC);

if (!$dictee) {
    die("Dictée non trouvée.");
}


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
        <a href="index.php">Accueil</a> |
        <a href="saisie.html">Saisie</a> |
        <a href="modification.php">Modifier</a> |
        <a href="visualisation.php">Tendances</a>
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
            </div>
            <div class="textarea-group">
                <textarea id="contenu" name="contenu"><?php echo htmlspecialchars($dictee['contenu_prof']); ?></textarea>
            </div>
            <div class="button-group">
               
                <button type="button" class="cancel-btn" onclick="window.location.href='modification.php'">Annuler</button>
                <button type="submit" class="submit-btn">Mettre à jour</button>
            </div>
        </form>
    </div>
</body>
</html>