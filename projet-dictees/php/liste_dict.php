<?php
$host = 'localhost';
$dbname = 'gr4m1idl';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}



$sql = "SELECT p.id_dict, p.titre, p.niveau, p.type, 'Prof' AS version, NULL AS date_tri
        FROM version_prof p 

        UNION ALL

        SELECT p.id_dict, p.titre, p.niveau, p.type, 'Elève', e.date 
        FROM version_prof p
        INNER JOIN version_eleve e ON p.id_dict = e.dict_fk 

        ORDER BY date_tri DESC, titre ASC";


$sql_query = $pdo->query($sql);
$dictees = $sql_query->fetchAll(PDO::FETCH_ASSOC);
?>