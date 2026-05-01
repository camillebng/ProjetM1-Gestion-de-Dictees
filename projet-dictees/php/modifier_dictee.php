<?php
require_once 'config.php';
require_once 'tokenize.php';

// 1. Récupération des données du formulaire
$id_dict = isset($_POST['id_dict']) ? (int)$_POST['id_dict'] : 0;
$version = $_POST['version'] ?? 'Prof';
$nouveau_contenu = $_POST['contenu'] ?? '';
$titre = $_POST['titre'] ?? '';
$type = $_POST['type'] ?? '';
$niveau = $_POST['niveau'] ?? '';

if ($id_dict <= 0) {
    die("ID invalide.");
}

try {
    $pdo->beginTransaction();

    if ($version === 'Prof') {
        // --- CAS PROFESSEUR ---

        // A. Flush des anciens tokens prof
        $sqlDelete = "DELETE FROM toks_prof WHERE id_dict_fk = ?";
        $pdo->prepare($sqlDelete)->execute([$id_dict]);

        // B. Mise à jour du texte et reset du flag de tokénisation
        $sqlUpdate = "UPDATE version_prof SET titre = ?, type = ?, niveau = ?, contenu_prof = ?, is_tokenized = 0 WHERE id_dict = ?";
        $pdo->prepare($sqlUpdate)->execute([$titre, $type, $niveau, $nouveau_contenu, $id_dict]);

    } else {
        // --- CAS ÉLÈVE ---

        // A. Flush des anciens tokens élève
        $sqlDelete = "DELETE FROM toks_eleve WHERE id_dict_fk = (SELECT dict_fk FROM version_eleve WHERE id_dict_eleve = ?)";

        $pdo->prepare($sqlDelete)->execute([$id_dict]);

        // B. Mise à jour du texte élève
        $sqlUpdate = "UPDATE version_eleve SET contenu_eleve = ?, is_tokenized_e = 0 WHERE id_dict_eleve = ?";
        $pdo->prepare($sqlUpdate)->execute([$nouveau_contenu, $id_dict]);
    }

    $pdo->commit();

    // 2. Relance de la chaîne de traitement (PHP puis Python)
    
    // Appel de la fonction de tokénisation (qui va recréer les tokens dans la bdd)
    executer_tokenisation($pdo, $id_dict, strtolower($version));

    // Si c'est un élève, on relance le POS Tagging Python
    if ($version === 'Eleve') {
        $id_securise = escapeshellarg($id_dict);
        $type_securise = escapeshellarg('eleve');
        
        $python_path = "C:\\Users\\cbeno\\AppData\\Local\\Microsoft\\WindowsApps\\python.exe";
        $script_path = __DIR__ . "/../scripts/pos_tagging.py";
        
        $commande = "$python_path \"$script_path\" $id_securise $type_securise 2>&1";
        shell_exec($commande);
    }

    // Redirection 
    header("Location: ../modification.php?success=1");
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("Erreur lors de la mise à jour : " . $e->getMessage());
}