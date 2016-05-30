<?php

require("models/Project.php");

$project = Project::getInstance();

if(isset($_POST['json'])) {
    $project->updateTable(json_decode($_POST['json'], true));
}

if(isset($_POST['nvnom'])) {
    $project->updateTask($_POST['id'], $_POST['nvnom']);
}

if(isset($_POST['listePredecesseurs'])) {
    $project->updatePredecesseurs($_POST['idTache'], array_unique(json_decode($_POST['listePredecesseurs'], true)));
}
if(isset($_POST['listeSuccesseurs'])) {
    $project->updateSuccesseurs($_POST['idTache'], array_unique(json_decode($_POST['listeSuccesseurs'], true)));
}

if(isset($_POST['nvloi'])) {

    $loi = array();
    $loi['nom'] = $_POST['nvloi'];

    if($loi['nom'] == LoiEnum::Beta) {
        $loi['valeurMin'] = $_POST['min_beta'];
        $loi['valeurMax'] = $_POST['max_beta'];
        $loi['w'] = $_POST['w'];
        $loi['v'] = $_POST['v'];
    } else if($loi['nom'] == LoiEnum::Triangulaire) {
        $loi['valeurMin'] = $_POST['min_triang'];
        $loi['valeurMax'] = $_POST['max_triang'];
        $loi['c'] = $_POST['c'];
    } else if($loi['nom'] == LoiEnum::Normale) {
        $loi['valeurMin'] = $_POST['min_norm'];
        $loi['valeurMax'] = $_POST['max_norm'];
        $loi['mu'] = $_POST['mu'];
        $loi['sigma'] = $_POST['sigma'];
    } else if($loi['nom'] == LoiEnum::Uniforme) {
        $loi['valeurMin'] = $_POST['min_unif'];
        $loi['valeurMax'] = $_POST['max_unif'];
    } else if($loi['nom'] == LoiEnum::SansLoi) {
        $loi['valeurMin'] = $_POST['valeur'];
        $loi['valeurMax'] = $_POST['valeur'];
    }
    
    $project->updateTaskLoi($_POST['id'], $loi);
}

header("Location: tasklist.php");
?>
