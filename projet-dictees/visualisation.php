<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tendances - Gestion des Dictées</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <h1>Tendances de la Classe</h1>
    </div>

    <!-- Formulaire de filtrage -->
    <div class="form-container">
        <form id="filterForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="type">Type</label>
                    <select id="type" name="type">
                        <option value="">Tous les types</option>
                        <option value="texte">Texte</option>
                        <option value="phrase">Phrases</option>
                        <option value="mot">Mots</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="niveau">Niveau</label>
                    <select id="niveau" name="niveau">
                        <option value="">Tous les niveaux</option>
                        <option value="cp">CP</option>
                        <option value="ce1">CE1</option>
                        <option value="ce2">CE2</option>
                        <option value="cm1">CM1</option>
                        <option value="cm2">CM2</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Filtrer par date</label>
                    <input type="date" id="date" name="date">
                </div>
            </div>
        </form>
    </div>

    <!-- Zone du graphique -->
    <div class="main-container">
        <div class="chart-wrapper">
            <canvas id="trendChart"></canvas>
        </div>

        <!-- Zone des statistiques -->
        <div class="stats-summary">
            <div class="stat-card">
                <h3>Moyenne Générale</h3>
                <div class="value" id="stat-moyenne">-</div>
            </div>
            <div class="stat-card">
                <h3>Dictées Réalisées</h3>
                <div class="value" id="stat-count">-</div>
            </div>
            <div class="stat-card">
                <h3>Note Max / Min</h3>
                <div class="value" id="stat-range">-</div>
            </div>
            <div class="stat-card">
                <h3>Top 3 Erreurs</h3>
                <div class="value" id="stat-errors" style="font-size: 1.1rem; line-height: 1.2;">-</div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('trendChart').getContext('2d');
        let trendChart;

        function updateStats() {
            const type = document.getElementById('type').value;
            const niveau = document.getElementById('niveau').value;
            const date = document.getElementById('date').value;

            fetch(`php/get_stats.php?type=${type}&niveau=${niveau}&date=${date}`)
                .then(response => response.json())
                .then(data => {
                    // 1. Mise à jour des cartes classiques
                    document.getElementById('stat-moyenne').textContent = data.summary.moyenne ? parseFloat(data.summary.moyenne).toFixed(2) + "/20" : "N/A";
                    document.getElementById('stat-count').textContent = data.summary.nb_dictées || 0;
                    document.getElementById('stat-range').textContent = data.summary.note_max ? 
                        `${parseFloat(data.summary.note_max).toFixed(1)} / ${parseFloat(data.summary.note_min).toFixed(1)}` : "N/A";

                    // 2. Mise à jour de la carte des 3 erreurs les plus fréquentes
                    const errorDiv = document.getElementById('stat-errors');
                    if (data.top_errors && data.top_errors.length > 0) {
                        const labelsFr = {
                            'NOUN': 'Noms', 'VERB': 'Verbes', 'ADJ': 'Adjectifs',
                            'DET': 'Déterminants', 'ADV': 'Adverbes', 'PRON': 'Pronoms',
                            'PROPN': 'Noms Propres', 'ADP': 'Prépositions', 'AUX': 'Auxiliaires',
                            'CONJ': 'Conjonctions', 'SCONJ': 'Conjonctions de sub.', 'NUM': 'Nombres'
                        };

                        let html = '<ul style="list-style: none; padding: 0; margin: 0; text-align: left; display: inline-block;">';
                        data.top_errors.forEach((item, index) => {
                            // Si le tag n'est pas dans le dico, on affiche le tag brut entre parenthèses
                            const label = labelsFr[item.pos_tok] || `Autre (${item.pos_tok})`;
                            html += `<li>${index + 1}. <strong>${label}</strong> (${item.nb_erreurs})</li>`;
                        });
                        html += '</ul>';
                        errorDiv.innerHTML = html;
                    } else {
                        errorDiv.textContent = "Aucune erreur";
                    }

                    // 3. Mise à jour du graphique
                    const labels = data.chart.map(d => d.date);
                    const scores = data.chart.map(d => d.moy_jour);

                    if (trendChart) trendChart.destroy();

                    trendChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Moyenne de la classe',
                                data: scores,
                                borderColor: '#3498db',
                                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                                fill: true,
                                tension: 0.3
                            }]
                        },
                        options: { 
                            scales: { y: { min: 0, max: 20 } },
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                });
        }

        document.getElementById('type').addEventListener('change', updateStats);
        document.getElementById('niveau').addEventListener('change', updateStats);
        document.getElementById('date').addEventListener('change', updateStats);

        updateStats();
    });
    </script>
</body>
</html>