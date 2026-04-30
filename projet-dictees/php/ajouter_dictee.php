<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php'; 
require_once 'tokenize.php'; // On charge le fichier contenant la fonction de tokénisation

// Récupération des données du formulaire
$auteur = $_POST['auteur'] ?? '';
$date = $_POST['date'] ?? date('Y-m-d'); 
$id_genere = null;

try {
    if ($auteur === 'prof') {
        $type = $_POST['type'] ?? '';
        $niveau = $_POST['niveau'] ?? '';
        $titre = $_POST['titre'] ?? '';
        $contenu_prof = $_POST['contenu'] ?? '';

        // On insère la dictée brute
        $sql = "INSERT INTO version_prof (type, titre, niveau, contenu_prof, is_tokenized) 
                VALUES (?, ?, ?, ?, 0)";
        $statement = $pdo->prepare($sql);
        $statement->execute([$type, $titre, $niveau, $contenu_prof]);
        
        $id_genere = $pdo->lastInsertId();

    } elseif ($auteur === 'eleve') {
        $contenu_eleve = $_POST['contenu'] ?? '';
        $dict_fk = $_POST['dict_fk'] ?? null; 

        // On insère la copie de l'élève
        $sql = "INSERT INTO version_eleve (date, contenu_eleve, dict_fk, is_tokenized_e) 
                VALUES (?, ?, ?, 0)";
        $statement = $pdo->prepare($sql);
        $statement->execute([$date, $contenu_eleve, $dict_fk]);
        
        $id_genere = $pdo->lastInsertId();
    }

    if ($id_genere) {
        // Lancement de la tokénisation PHP + calcul du score si dictée élève
        executer_tokenisation($pdo, $id_genere, $auteur);

        // Lancement du script Python pour le POS Tagging (via Spacy)
        $id_securise = escapeshellarg($id_genere);
        $type_securise = escapeshellarg($auteur);
        
        $commande_pos = "python3 ../scripts/pos_tagging.py " . $id_securise . " " . $type_securise;
        $output_pos = shell_exec($commande_pos);

        // Redirection vers l'index 
        header("Location: ../index.php?success=1");
        exit();
    }

} catch (PDOException $e) {
    die("Erreur lors de l'enregistrement : " . $e->getMessage());
}
?>