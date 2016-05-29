<?php
require("../models/Project.php");
$project = Project::getInstance();

$data = array();
foreach ($project->listeTaches as $task) {
    $predecesseurs = [];
    foreach ($task->predecesseurs as $pred) {
        $predecesseurs[] = $pred->nom;
    }
    array_push($data, array('id' => $task->id,
                          'nom' => $task->nom,
                          'predecesseurs' => $predecesseurs
                          ));
}

header('Content-Type: application/json');
echo json_encode($data);
?>
