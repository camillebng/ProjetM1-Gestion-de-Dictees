<?php
$host = 'localhost';
$dbname = 'gr4m1IDL';
$username = 'm2dilipem':
$password = 'm2dilipem';


try{
    $pdo = new PDO("mysql:host = $host;dbname = $dbname;charset = utf8",$username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " / $e->getMessage());
}

$sql_query = $pdo -> query("SELECT p.titre, niveau, type, e.date FROM version_eleve e, version_prof p ORDER BY e.date DESC");
$dictees = $sql_query->fetchAll(PDO::FETCH_ASSOC);



?>