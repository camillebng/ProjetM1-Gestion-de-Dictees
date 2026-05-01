<?php
require_once 'config.php';

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';
$niveau = $_GET['niveau'] ?? '';
$date = $_GET['date'] ?? '';

try {
    // 1. Requête pour le résumé (cartes de stats)
    $sql = "SELECT 
                AVG(v.score_sur_20) as moyenne, 
                COUNT(v.id_dict_eleve) as nb_dictées,
                MAX(v.score_sur_20) as note_max,
                MIN(v.score_sur_20) as note_min
            FROM version_eleve v
            JOIN version_prof p ON v.dict_fk = p.id_dict
            WHERE 1=1";
    
    $params = [];
    if (!empty($type)) { $sql .= " AND p.type = ?"; $params[] = $type; }
    if (!empty($niveau)) { $sql .= " AND p.niveau = ?"; $params[] = $niveau; }
    if (!empty($date)) { $sql .= " AND v.date = ?"; $params[] = $date; }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Requête pour les 10 dernières données du graphique
    $sql_chart = "SELECT v.date, AVG(v.score_sur_20) as moy_jour 
                  FROM version_eleve v
                  JOIN version_prof p ON v.dict_fk = p.id_dict
                  WHERE 1=1";
    
    if (!empty($type)) { $sql_chart .= " AND p.type = ?"; }
    if (!empty($niveau)) { $sql_chart .= " AND p.niveau = ?"; }
    if (!empty($date)) { $sql_chart .= " AND v.date = ?"; }
    
    $sql_chart .= " GROUP BY v.date ORDER BY v.date ASC LIMIT 10";
    
    $stmt_chart = $pdo->prepare($sql_chart);
    $stmt_chart->execute($params);
    $chart_data = $stmt_chart->fetchAll(PDO::FETCH_ASSOC);

    // 3. Requête pour les erreurs les plus fréquentes
    $sql_errors = "SELECT te.pos_tok, COUNT(*) as nb_erreurs 
                   FROM toks_eleve te
                   JOIN version_eleve v ON te.id_dict_fk = v.dict_fk
                   JOIN version_prof p ON v.dict_fk = p.id_dict
                   WHERE te.est_correct = 0"; 
    
    $params_errors = $params; 
    if (!empty($type)) { $sql_errors .= " AND p.type = ?"; }
    if (!empty($niveau)) { $sql_errors .= " AND p.niveau = ?"; }
    if (!empty($date)) { $sql_errors .= " AND v.date = ?"; }

    $sql_errors .= " GROUP BY te.pos_tok ORDER BY nb_erreurs DESC LIMIT 3";
    
    $stmt_errors = $pdo->prepare($sql_errors);
    $stmt_errors->execute($params_errors);
    $top_errors = $stmt_errors->fetchAll(PDO::FETCH_ASSOC);

    // Envoi de la réponse complète au format JSON
    echo json_encode([
        'summary' => $stats,
        'chart' => $chart_data,
        'top_errors' => $top_errors // Ajout de la nouvelle donnée pour la carte
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>