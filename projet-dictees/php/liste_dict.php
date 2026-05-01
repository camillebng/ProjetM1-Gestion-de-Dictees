<?php

require_once 'config.php';


$sql = "SELECT id_dict, titre, type, niveau, 'Prof' as version, NULL as date_tri 
        FROM version_prof
        
        UNION ALL
        
        SELECT v.id_dict_eleve as id_dict, p.titre, p.type, p.niveau, 'Eleve' as version, v.date as date_tri
        FROM version_eleve v
        JOIN version_prof p ON v.dict_fk = p.id_dict
        ORDER BY id_dict DESC";

$statement = $pdo->query($sql);
$dictees = $statement->fetchAll(PDO::FETCH_ASSOC);



?>