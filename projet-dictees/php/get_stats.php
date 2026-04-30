<?php
require_once 'config.php';

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';
$niveau = $_GET['niveau'] ?? '';
$date = $_GET['date'] ?? '';

try {
    // Requête avec filtres
    $sql = "SELECT 
                AVG(v.score_sur_20) as moyenne, 
                COUNT(v.id_eleve) as nb_dictées,
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

    // 10 dernières données pour le graphique
    $sql_chart = "SELECT v.date, AVG(v.score_sur_20) as moy_jour 
                  FROM version_eleve v 
                  JOIN version_prof p ON v.dict_fk = p.id_dict
                  WHERE 1=1";
    // (Ajouter les mêmes filtres ici si nécessaire)
    $sql_chart .= " GROUP BY v.date ORDER BY v.date ASC LIMIT 10";
    $stmt_chart = $pdo->prepare($sql_chart);
    $stmt_chart->execute($params);
    $chart_data = $stmt_chart->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'summary' => $stats,
        'chart' => $chart_data
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}