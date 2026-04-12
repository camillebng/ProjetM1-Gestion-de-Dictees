<?php include 'php/liste_dict.php'; ?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier - Gestion des Dictées</title>
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
        <h1>Modifier</h1>
    </div>

    <div class="main-container">
        <div class="filters-row">
            <div class="filter-group">
                <label for="filter-type">Type</label>
                <select id="filter-type">
                    <option value="">Tous</option>
                    <option value="dictee">Dictée</option>
                    <option value="test">Test</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filter-niveau">Niveau</label>
                <select id="filter-niveau">
                    <option value="">Tous</option>
                    <option value="cp">CP</option>
                    <option value="ce1">CE1</option>
                    <option value="ce2">CE2</option>
                    <option value="cm1">CM1</option>
                    <option value="cm2">CM2</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filter-titre">Titre</label>
                <input type="text" id="filter-titre" placeholder="Rechercher...">
            </div>
            <div class="filter-group">
                <label for="filter-date">Date</label>
                <input type="date" id="filter-date">
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Niveau</th>
                    <th>Version</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="liste-dictees">
                <?php foreach ($dictees as $dictee): ?>
                <tr class="row-<?php echo strtolower($dictee['version']); ?>">
                    <td><strong><?php echo htmlspecialchars($dictee['titre']); ?></strong></td>
                    <td><?php echo htmlspecialchars($dictee['type']); ?></td>
                    <td><?php echo htmlspecialchars($dictee['niveau']); ?></td>
                    <td>
                        <span class="badge-<?php echo ($dictee['version'] == 'Prof') ? 'blue' : 'green'; ?>">
                            <?php echo $dictee['version']; ?>
                        </span>
                    </td>
                    <td>
                        <?php 
                            echo ($dictee['date_tri']) 
                            ? date('d/m/Y', strtotime($dictee['date_tri'])) 
                            : '---'; 
                        ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?php echo $dictee['id_dict']; ?>" class="edit-btn">Modifier</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div> 
</body>
</html>