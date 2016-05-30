<?php
class ResultatSimulation {
  var $lastValueRange;
  var $xAxis;
  var $yAxis;

  function __construct($simulation, $nbEchantillons, $largeurIntervalle)
	{
    $this->lastValueRange = array();
    $this->xAxis = array();
    $this->yAxis = array();

    for ($cc = 0; $cc < count($simulation); $cc++) {
      array_push($this->lastValueRange, ($cc*$largeurIntervalle)+($largeurIntervalle-1));
      array_push($this->xAxis, ($cc*$largeurIntervalle) . ' - ' . (($cc*$largeurIntervalle)+($largeurIntervalle-1)));
      array_push($this->yAxis, ($simulation[$cc] / $nbEchantillons) * 100);
    }
  }

  function estimateProbabilityGivenCharge($charge, $largeurIntervalle) {
    $resultat = 0;
    for($cc = 0; $cc < count($this->lastValueRange); $cc++) {
      if($charge > $this->lastValueRange[$cc]) {
        //echo "entrou";
      } else {
        $valeurDepasse = $charge - ($this->lastValueRange[$cc] - ($largeurIntervalle - 1));
        $ratio = $valeurDepasse / $largeurIntervalle;

        $sommeProbabilites = 0;
        for($i = 0; $i < $cc; $i++) {
          $sommeProbabilites += $this->yAxis[$i];
        }

        $probProportionnelle = $this->yAxis[$cc] * $ratio;

        $resultat = $sommeProbabilites + $probProportionnelle;
        $cc = count($this->lastValueRange);
      }
    }

    return round($resultat, 2);
  }

  function estimateChargeGivenProbability($probability, $largeurIntervalle) {
    $sommePartielleProbabilites = 0;
    $resultat = 0;
    for($cc = 0; $cc < count($this->yAxis); $cc++) {
      if($probability > $sommePartielleProbabilites + $this->yAxis[$cc]) {
        $sommePartielleProbabilites += $this->yAxis[$cc];
      } else {
        if($probability == $sommePartielleProbabilites + $this->yAxis[$cc]) {
          $resultat =  $this->lastValueRange[$cc];
        } else { // somme partielle depasse
          $pourcentageIntervalle = $this->yAxis[$cc];
          $pourcentageDepasse = $probability - $sommePartielleProbabilites;
          $ratio = $pourcentageDepasse / $pourcentageIntervalle;
          $resultat = ($this->lastValueRange[$cc] + (1 - $largeurIntervalle)) + ($largeurIntervalle * $ratio);
        }
        $cc = count($this->yAxis);
      }
    }
    return round($resultat);
  }
}
?>
