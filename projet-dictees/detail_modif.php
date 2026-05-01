<?php
// Connexion à la base de données
require_once 'php/config.php';

// Récupère l'ID et la version depuis l'URL
$id_dict = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$version = isset($_GET['version']) ? $_GET['version'] : 'Prof'; // Récupère si c'est Prof ou Eleve

if ($id_dict <= 0) {
    die("ID de dictée invalide.");
}

// Récupération dynamique selon l'auteur
if ($version === 'Prof') {
    // Requête classique sur la table prof
    $requete = "SELECT id_dict, titre, type, niveau, contenu_prof AS contenu FROM version_prof WHERE id_dict = ?";
} else {
    // Requête sur la table élève avec jointure pour récupérer les infos de référence (titre, type, niveau)
    $requete = "SELECT v.id_dict_eleve AS id_dict, p.titre, p.type, p.niveau, v.contenu_eleve AS contenu 
                FROM version_eleve v 
                JOIN version_prof p ON v.dict_fk = p.id_dict 
                WHERE v.id_dict_eleve = ?";
}

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
        <a href="saisie.php">Saisie</a> |
        <a href="modification.php">Modifier</a> |
        <a href="visualisation.php">Tendances</a>
    </div>
    <div class="header">
        <h1>Modifier la Dictée (<?php echo $version; ?>)</h1>
    </div>
    <div class="main-container clearfix">
        <form action="php/modifier_dictee.php" method="POST">
            <!-- Champs cachés pour l'ID et la version  -->
            <input type="hidden" name="id_dict" value="<?php echo htmlspecialchars($dictee['id_dict']); ?>">
            <input type="hidden" name="version" value="<?php echo htmlspecialchars($version); ?>">

            <div class="form-row">
                <div class="form-group">
                    <label for="type">Type</label>
                    <select id="type" name="type" <?php echo ($version === 'Eleve') ? 'disabled' : ''; ?>>
                        <option value="mot" <?php echo ($dictee['type'] === 'mot') ? 'selected' : ''; ?>>Mots</option>
                        <option value="phrase" <?php echo ($dictee['type'] === 'phrase') ? 'selected' : ''; ?>>Phrases</option>
                        <option value="texte" <?php echo ($dictee['type'] === 'texte') ? 'selected' : ''; ?>>Texte</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="niveau">Niveau</label>
                    <select id="niveau" name="niveau" <?php echo ($version === 'Eleve') ? 'disabled' : ''; ?>>
                        <option value="cp" <?php echo ($dictee['niveau'] === 'cp') ? 'selected' : ''; ?>>CP</option>
                        <option value="ce1" <?php echo ($dictee['niveau'] === 'ce1') ? 'selected' : ''; ?>>CE1</option>
                        <option value="ce2" <?php echo ($dictee['niveau'] === 'ce2') ? 'selected' : ''; ?>>CE2</option>
                        <option value="cm1" <?php echo ($dictee['niveau'] === 'cm1') ? 'selected' : ''; ?>>CM1</option>
                        <option value="cm2" <?php echo ($dictee['niveau'] === 'cm2') ? 'selected' : ''; ?>>CM2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="titre">Titre</label>
                    <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($dictee['titre']); ?>" <?php echo ($version === 'Eleve') ? 'readonly' : ''; ?>>
                </div>
            </div>
            <div class="textarea-group">

                <textarea id="contenu" name="contenu"><?php echo htmlspecialchars($dictee['contenu']); ?></textarea>
            </div>
            <div class="button-group">
                <button type="button" class="cancel-btn" onclick="window.location.href='modification.php'">Annuler</button>
                <button type="submit" class="submit-btn">Mettre à jour</button>
            </div>
        </form>
    </div>
</body>
</html>