<?php

require_once 'config.php';


$sql_query = $pdo->query("  SELECT p.id_dict, p.titre, p.niveau, p.type, 'Prof' AS version, e.`date` AS date_tri
                            FROM version_prof p, version_eleve e

                            UNION ALL

                            SELECT p.id_dict, p.titre, p.niveau, p.type, 'Eleve' AS version, e.`date` AS date_tri
                            FROM version_prof p
                            INNER JOIN version_eleve e ON p.id_dict = e.dict_fk 

                            ORDER BY date_tri DESC ");

                            
$dictees = $sql_query->fetchAll(PDO::FETCH_ASSOC);



?>