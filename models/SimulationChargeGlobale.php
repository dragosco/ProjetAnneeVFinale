<?php
require("SimulationMC.php");
// require("Project.php");

Class SimulationChargeGlobale extends SimulationMC {
  function calculate()
  {
		$max = 0;
		// $project = Project::getInstance();

		foreach ($this->projet->listeTaches as $tache)
    {
  	  $max = $max + $tache->loi->valeurMax;
		}

    $nbCat = floor(($max / $this->largeurIntervalle) + 1);

    for($cc = 0; $cc <= $nbCat; $cc++)
    {
    	$distrib[$cc] = 0;
    }

    for($i=0; $i<$this->nbEchantillons; $i++)
    {
    	$ech = 0;
      foreach ($this->projet->listeTaches as $tache)
      {
        $ech = $ech + $tache->loi->generate();
      }

    	$index = floor($ech/$this->largeurIntervalle);
    	$distrib[$index]++;
    }

    $this->resultatCalcul = new ResultatSimulation($distrib, $this->nbEchantillons, $this->largeurIntervalle);

    return $this->resultatCalcul;
  }
}
?>
