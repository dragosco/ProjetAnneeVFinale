<?php
//require("lois/LoiProbabilite.php");
require("ResultatSimulation.php");
require("SimulateurEnum.php");
// require("Project.php");
/*
 * Monte Carlo
 *
 * Classe SimulationMC
 */
class SimulationMC
{
	var $typeSimulateur;
	var $nbEchantillons;
	var $largeurIntervalle;
	var $charge;
	var $probabilite;
	var $projet;
	var $resultatCalcul;

	// public static function constructFull($typeSimulateur, $nbEchantillons, $largeurIntervalle, $probabiliteEntree, $chargeEntree, $projet) //
	// {
	// 	$simulation = new SimulationMC();
	//
	// 	$simulation->typeSimulateur = $typeSimulateur;
	// 	$simulation->nbEchantillons = $nbEchantillons;
	// 	$simulation->largeurIntervalle = $largeurIntervalle;
	// 	$simulation->probabiliteEntree = $probabiliteEntree;
	// 	$simulation->chargeEntree = $chargeEntree;
	//
	// 	$simulation->projet = $projet;
	// 	$simulation->resultatCalcul = NULL;
	//
	// 	return $simulation;
	// }
	//
	// public static function constructBasic($typeSimulateur, $nbEchantillons, $largeurIntervalle, $projet)
	// {
	// 	$simulation = new SimulationMC();
	//
	// 	$simulation->typeSimulateur = $typeSimulateur;
	// 	$simulation->nbEchantillons = $nbEchantillons;
	// 	$simulation->largeurIntervalle = $largeurIntervalle;
	// 	// $this->chargeEntree = $chargeEntree;
	// 	// $this->probabiliteEntree = $probabiliteEntree;
	//
	// 	$simulation->projet = $projet;
	// 	$simulation->resultatCalcul = NULL;
	//
	// 	return $simulation;
	// }

	// function calculate() {}
	function __construct($typeSimulateur, $nbEchantillons, $largeurIntervalle, $projet) // , $chargeEntree, $probabiliteEntree
	{
		$this->typeSimulateur = $typeSimulateur;
		$this->nbEchantillons = $nbEchantillons;
		$this->largeurIntervalle = $largeurIntervalle;
		// $this->chargeEntree = $chargeEntree;
		// $this->probabiliteEntree = $probabiliteEntree;

		$this->projet = $projet;
		$this->resultatCalcul = NULL;
	}

	function updateParameters() {

	}

  function estimateProbabilityGivenCharge($charge) {
    $this->charge = $charge;

    if(is_null($this->resultatCalcul)) {
      $this->calculate();
    }

		//verifier si $charge == $this->chargeEntree
		//sinon update bd

    return $this->resultatCalcul->estimateProbabilityGivenCharge($charge, $this->largeurIntervalle);
  }

  function estimateChargeGivenProbability($probability) {
    $this->probability = $probability;

    if(is_null($this->resultatCalcul)) {
      $this->calculate();
    }

		//verifier si $probability == $this->probability
		//sinon update bd

    return $this->resultatCalcul->estimateChargeGivenProbability($probability, $this->largeurIntervalle);
  }

	public function __get($property) {
    if (property_exists($this, $property)) {
        return $this->$property;
    }
  }

  public function __set($property, $value) {
    if (property_exists($this, $property)) {
        $this->$property = $value;
    }
  }
}
?>
