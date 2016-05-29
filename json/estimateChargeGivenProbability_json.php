<?php
// require("models/SimulationChargeGlobale.php");
require("../models/Project.php");

$project = Project::getInstance();

$typeSimulateur = $_POST['typeSimulateur'];
$iteration = $_POST['iteration'];
$intervalle = $_POST['intervalle'];
$probabilite = $_POST['probabilite'];
$charge = $_POST['charge'];

$charge = $project->estimateCharge($typeSimulateur, $iteration, $intervalle, $probabilite, $charge);

$data = [];
$data['charge'] = $charge;

header('Content-Type: application/json');
echo json_encode($data);

?>
