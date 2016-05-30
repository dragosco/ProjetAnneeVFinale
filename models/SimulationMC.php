<?php
require("ResultatSimulation.php");
require("SimulateurEnum.php");
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

	function __construct($typeSimulateur, $nbEchantillons, $largeurIntervalle, $projet)
	{
		$this->typeSimulateur = $typeSimulateur;
		$this->nbEchantillons = $nbEchantillons;
		$this->largeurIntervalle = $largeurIntervalle;

		$this->projet = $projet;
		$this->resultatCalcul = NULL;
	}

  function estimateProbabilityGivenCharge($charge) {
    $this->charge = $charge;

    if(is_null($this->resultatCalcul)) {
      $this->calculate();
    }

    return $this->resultatCalcul->estimateProbabilityGivenCharge($charge, $this->largeurIntervalle);
  }

  function estimateChargeGivenProbability($probability) {
    $this->probability = $probability;

    if(is_null($this->resultatCalcul)) {
      $this->calculate();
    }

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
