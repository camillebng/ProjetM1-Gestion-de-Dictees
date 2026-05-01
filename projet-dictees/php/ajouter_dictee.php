<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php'; 
require_once 'tokenize.php'; 

$auteur = $_POST['auteur'] ?? '';
$date = $_POST['date'] ?? date('Y-m-d'); 
$id_genere = null;

try {
    if ($auteur === 'prof') {
        $type = $_POST['type'] ?? '';
        $niveau = $_POST['niveau'] ?? '';
        $titre = $_POST['titre'] ?? '';
        $contenu_prof = $_POST['contenu'] ?? '';

        $sql = "INSERT INTO version_prof (type, titre, niveau, contenu_prof, is_tokenized) 
                VALUES (?, ?, ?, ?, 0)";
        $statement = $pdo->prepare($sql);
        $statement->execute([$type, $titre, $niveau, $contenu_prof]);
        
        $id_genere = $pdo->lastInsertId();

    } elseif ($auteur === 'eleve') {
        $contenu_eleve = $_POST['contenu'] ?? '';
        $dict_fk = $_POST['dict_fk'] ?? null; 

        $sql = "INSERT INTO version_eleve (date, contenu_eleve, dict_fk, is_tokenized_e) 
                VALUES (?, ?, ?, 0)";
        $statement = $pdo->prepare($sql);
        $statement->execute([$date, $contenu_eleve, $dict_fk]);
        
        $id_genere = $pdo->lastInsertId();
    }

    if ($id_genere) {
        executer_tokenisation($pdo, $id_genere, $auteur);

        if ($auteur === 'eleve') {
            $id_securise = escapeshellarg($id_genere);
            $type_securise = escapeshellarg($auteur);
            
            $python_path = "C:\\Users\\cbeno\\AppData\\Local\\Microsoft\\WindowsApps\\python.exe";

            $script_path = __DIR__ . "/../scripts/pos_tagging.py"; 
            
            $commande_pos = "$python_path \"$script_path\" $id_securise $type_securise 2>&1";
            $output_pos = shell_exec($commande_pos);

            // En cas de problème
            file_put_contents("debug_log.txt", "Commande : $commande_pos\nRetour : $output_pos\n", FILE_APPEND);
        }

        header("Location: ../index.php?success=1");
        exit();
    }

} catch (PDOException $e) {
    die("Erreur lors de l'enregistrement : " . $e->getMessage());
}
?>