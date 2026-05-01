<?php
require_once 'php/config.php';


$query = $pdo->query("SELECT id_dict, titre FROM version_prof ORDER BY titre ASC");
$dictees_prof = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisie - Gestion des Dictées</title>
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
        <h1>Saisie</h1>
    </div>

    <div class="main-container clearfix">
        <form action="php/ajouter_dictee.php" method="POST" id="form-dictee">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="type">Type</label>
                    <select id="type" name="type">
                        <option value="texte">Texte</option>
                        <option value="phrase">Phrase</option>
                        <option value="mot">Mots</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="niveau">Niveau</label>
                    <select id="niveau" name="niveau">
                        <option value="cp">CP</option>
                        <option value="ce1">CE1</option>
                        <option value="ce2">CE2</option>
                        <option value="cm1">CM1</option>
                        <option value="cm2">CM2</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="auteur">Auteur</label>
                    <select id="auteur" name="auteur" onchange="toggleInputs()">
                        <option value="prof">Professeur</option>
                        <option value="eleve">Élève</option>
                    </select>
                </div>

                <!-- Champ Titre : Libre pour le prof -->
                <div class="form-group" id="group-titre">
                    <label for="titre">Titre</label>
                    <input type="text" id="titre" name="titre" placeholder="Ex: Leçon 1">
                </div>

                <!-- Champ Sélection : Caché par défaut pour l'élève -->
                <div class="form-group" id="group-selection-prof" style="display: none;">
                    <label for="dict_fk">Référence Prof</label>
                    <select id="dict_fk" name="dict_fk">
                        <option value="">-- Choisir --</option>
                        <?php foreach ($dictees_prof as $dp): ?>
                            <option value="<?php echo $dp['id_dict']; ?>">
                                <?php echo htmlspecialchars($dp['titre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>

            <div class="textarea-group">
                <textarea id="contenu" name="contenu" placeholder="Saisissez le texte de la dictée ici..."></textarea>
            </div>

            <button type="submit" class="submit-btn">Enregistrer</button>
        </form>
    </div>

    <script>
    function toggleInputs() {
        const auteur = document.getElementById('auteur').value;
        const groupTitre = document.getElementById('group-titre');
        const groupSelect = document.getElementById('group-selection-prof');

        // Si l'auteur sélectionné est un élève :
        if (auteur === 'eleve') {
            groupTitre.style.display = 'none'; // La saisie libre du titre est masquée 
            groupSelect.style.display = 'block'; // Une liste déroulante affichant les dictées déjà existantes s'affiche 
        } else {
            groupTitre.style.display = 'block';
            groupSelect.style.display = 'none';
        }
    }

    document.getElementById('form-dictee').addEventListener('submit', function(e) {
        const auteur = document.getElementById('auteur').value;
        const titre = document.getElementById('titre').value.trim();
        const dictFk = document.getElementById('dict_fk').value;

        if (auteur === 'prof' && titre === "") {
            e.preventDefault();
            alert("Veuillez donner un titre à votre dictée.");
        } else if (auteur === 'eleve' && dictFk === "") {
            e.preventDefault();
            alert("Veuillez sélectionner la dictée du professeur correspondante.");
        }
    });
    </script>
</body>
</html>