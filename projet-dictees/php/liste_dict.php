<?php
$host = 'localhost';
$dbname = 'gr4m1IDL';
$username = 'm2dilipem';
$password = 'm2dilipem';


try{
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",$username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$sql_query = $pdo -> query("SELECT p.titre, p.niveau, p.type, e.date 
                            FROM version_prof p 
                            INNER JOIN version_eleve e ON p.id_dict = e.dict_fk 
                            ORDER BY e.date DESC");
                            
$dictees = $sql_query->fetchAll(PDO::FETCH_ASSOC);



?>