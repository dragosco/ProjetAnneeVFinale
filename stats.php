<?php
//require("cnx.php");
require("models/Project.php");
//require("models/SimulationMC.php");
//require("models/SimulationChargeGlobale.php");

// Tentative d'instanciation de la classe
$project = Project::getInstance();

// On récupère tout le contenu de la table tâche
//$reponse = $bdd->query('SELECT * FROM tache');
?>

<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Gestion de projet</title>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
		<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/highcharts-3d.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>

		<script src="js/stats.js"></script>
		<script src="js/stats_view.js"></script>

		<link href="css/themecharts.css" rel="stylesheet">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/styles.css" rel="stylesheet">
		<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
	</head>

	<body>
		<?php require 'navbar.php'; ?>

		<div class="row main">
		  <div class="row">
		    <div class="col-lg-12">
		      <h1 class="page-header">Statistiques</h1>
		    </div>
		  </div><!--/.row-->

		  <div class="row">
		    <div class="col-lg-12">
		      <div class="panel panel-default">
		        <div class="panel-heading"><span class="glyphicon glyphicon-tasks"></span> Monte Carlo</div>
		        <div class="panel-body">
							<?php require 'chargeGlobale.php'; ?>
							<?php require 'dureeGlobale.php'; ?>
							<?php require 'coutGlobal.php'; ?>
					 	</div>
		      </div>
		    </div>
		  </div><!--/.row-->
		</div><!--/.main-->
	</body>
</html>
