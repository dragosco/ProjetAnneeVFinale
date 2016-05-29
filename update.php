<?php

require("models/Project.php");

$project = Project::getInstance();

if(isset($_POST['json'])) {
    $project->updateTable(json_decode($_POST['json'], true));
}

if(isset($_POST['id'])) {
    $project->updateTask($_POST['id'], $_POST['nvnom']);
}

if(isset($_POST['idPredecesseur'])) {
    $project->removePredecesseur($_POST['idTache'], $_POST['idPredecesseur']);
}
if(isset($_POST['idSuccesseur'])) {
    $project->removeSuccesseur($_POST['idTache'], $_POST['idSuccesseur']);
}

header("Location: tasklist.php");
?>
