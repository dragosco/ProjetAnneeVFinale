<?php
require("../models/Project.php");

$project = Project::getInstance();

$typeSimulateur = $_POST['typeSimulateur'];
$iteration = $_POST['iteration'];
$intervalle = $_POST['intervalle'];
$probabilite = $_POST['probabilite'];
$charge = $_POST['charge'];

$res = $project->executeSimulation($typeSimulateur, $iteration, $intervalle, $probabilite, $charge);

$xAxis = $res->xAxis;
$yAxis = $res->yAxis;

$data = [];
$data['xAxis'] = $xAxis;
$data['yAxis'] = $yAxis;

header('Content-Type: application/json');
echo json_encode($data);

?>
