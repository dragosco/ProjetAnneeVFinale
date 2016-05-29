<?php
require("../models/Project.php");
$project = Project::getInstance();

$data = array();
foreach ($project->listeTaches as $task) {
  array_push($data, array('nom' => $task->nom,
                              /*'duree' => $donnees['duree'],*/
                              'precedent1' => $task->precedent1,
                              'precedent2' => $task->precedent2,
                              'suivant1' => $task->suivant1,
                              'suivant2' => $task->suivant2,
      
                            ));
}

header('Content-Type: application/json');
echo json_encode($data);
?>
