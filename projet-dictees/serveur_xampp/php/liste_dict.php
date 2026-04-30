<?php
$host = 'localhost';
$dbname = 'gr4m1idl';
$username = 'root';
$password = '';


try{
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",$username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$sql_query = $pdo->query("  SELECT p.id_dict, p.titre, p.niveau, p.type, 'Prof' AS version, p.`date` AS date_tri
                            FROM version_prof p 

                            UNION ALL

                            SELECT p.id_dict, p.titre, p.niveau, p.type, 'Eleve' AS version, e.`date` AS date_tri
                            FROM version_prof p
                            INNER JOIN version_eleve e ON p.id_dict = e.dict_fk 

                            ORDER BY date_tri DESC ");

                            
$dictees = $sql_query->fetchAll(PDO::FETCH_ASSOC);



?>