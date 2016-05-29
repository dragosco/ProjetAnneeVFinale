<?php

require("models/Project.php");

$project = Project::getInstance();

if(isset($_POST['json'])) {
    $project->updateTable(json_decode($_POST['json'], true));
}

if(isset($_POST['id'])) {
    $project->updateTask($_POST['id'], $_POST['nvnom']);
}

if(isset($_POST['listePredecesseurs'])) {
    $project->updatePredecesseurs($_POST['idTache'], array_unique(json_decode($_POST['listePredecesseurs'], true)));
}
if(isset($_POST['listeSuccesseurs'])) {
    $project->updateSuccesseurs($_POST['idTache'], array_unique(json_decode($_POST['listeSuccesseurs'], true)));
}

header("Location: tasklist.php");
?>
