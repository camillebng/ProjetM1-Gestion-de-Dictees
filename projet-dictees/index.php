<?php 

require_once 'php/config.php'; 

// Affiche les trois dernières dictées entrées dans la base de données
try {
    $query = "SELECT id_dict, titre, type, niveau FROM version_prof ORDER BY id_dict DESC LIMIT 3";
    $stmt = $pdo->query($query);
    $dernieres_dictees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $dernieres_dictees = []; // En cas d'erreur, affiche un tableau vide 
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion des Dictées</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="nav-menu">
        <a href="index.php">Accueil</a> | 
        <a href="saisie.html">Saisie</a> | 
        <a href="modification.php">Modifier</a> | 
        <a href="visualisation.html">Tendances</a>
    </div>

    <div class="header">
        <h1>Accueil</h1>
    </div>

    <div class="main-container">
        <div class="welcome-text">
            <h2>Bienvenue dans le système de gestion des dictées</h2>
            <p>Cet outil est conçu pour aider les enseignants à enregistrer, organiser et visualiser les progrès en dictée.</p>
        </div>


        <div class="actions-grid">
            <div class="action-card" onclick="window.location.href='saisie.html';" style="cursor: pointer;">
                <span class="action-icon">➕</span>
                <h3>Enregistrer une Nouvelle Dictée</h3>
                <p>Enregistrez une dictée pour votre classe.</p>
            </div>
            <div class="action-card" onclick="window.location.href='modification.php';" style="cursor: pointer;">
                <span class="action-icon">🔍</span>
                <h3>Consulter l'Historique</h3>
                <p>Consultez et recherchez des dictées passées.</p>
            </div>
            <div class="action-card" onclick="window.location.href='visualisation.html';" style="cursor: pointer;">
                <span class="action-icon">📈</span>
                <h3>Visualiser les Tendances</h3>
                <p>Analysez les progrès de la classe.</p>
            </div>
        </div>

        <div class="recent-section">
            <div class="recent-header">Dernières Dictées Ajoutées</div>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Niveau</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dernieres_dictees)): ?>
                        <?php foreach ($dernieres_dictees as $dictee): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($dictee['titre']); ?></td>
                                <td><?php echo htmlspecialchars($dictee['type']); ?></td>
                                <td><?php echo strtoupper(htmlspecialchars($dictee['niveau'])); ?></td>
                                <td><a href="detail_modif.php?id=<?php echo $dictee['id_dict']; ?>">Modifier</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align:center;">Aucune dictée enregistrée pour le moment.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>