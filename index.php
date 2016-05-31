<?php
require("models/Project.php");
session_start();
// Tentative d'instanciation de la classe
$project = Project::getInstance();
//require("cnx.php");

if(!isset($_SESSION['email'])){
	header("Location: login.php");
}

?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gestion de projet</title>

	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	<link href="css/joint.css" rel="stylesheet">
	<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>


</head>

<body>
	<?php require 'navbar.php'; ?>

	<div class="row main">
		<div class="col-lg-12">
			<div class="row">
				<h1 class="page-header">PERT Chart</h1>
				<p class="message-boucle" style="color:darkred;">Error, cycle detected in the overall schema </p>
				<p id="waitForDiagram" class="saving">Loading Diagram <span>.</span><span>.</span><span>.</span></p>
				<button class="btn btn-default pull-right" type="button" id="enlargePaperWidthButton">Enlarge paper's width</button>
				<button class="btn btn-default pull-right" type="button" id="enlargePaperHeightButton">Enlarge paper's height</button>
				<button class="btn btn-default pull-right" type="button" id="reorganizeGraphButton">Reorganize graph</button>
				<button class="btn btn-default pull-right" type="button" id="reloadButton" href="index.php">Reload</button>
				<section style="width:100%; height:600px; background-color: transparent;"></section>
			</div>
		</div>
	</div>
	<!-- Joint js needs -->
	<script src="js/jquery.min.js"></script>
	<script src="js/lodash.min.js"></script>
	<script src="js/backbone-min.js"></script>
	<script src="js/joint.js"></script>
	<!-- /Joint js needs -->

	<script src="js/bootstrap.min.js"></script>
	<script src="js/createTask.js"></script>
	<script src="js/scripts.js"></script>
	<script src="js/show-PERT.js"></script>
</body>
</html>
