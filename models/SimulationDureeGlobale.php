<?php
Class SimulationDureeGlobale extends SimulationMC {

  function calculate()
  {
		$max = 0;

    $allPaths = $this->projet->listeParallelPaths;

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
      $valeursGenerees = array();
      //genere des valeurs pour chaque tache du projet
      foreach ($this->projet->listeTaches as $tache)
      {
        $valeursGenerees[$tache->id] = $tache->loi->generate();
      }

      $maxSomme = 0;
      //pour chaque chemin parallele commençant par 'start' et finissant par 'end'
      foreach ($allPaths as $path) {
        $somme = 0;
        //fais la somme de la duree du chemin
        foreach ($path as $tache) {
          $somme = $somme + $valeursGenerees[$tache->id];
        }
        //garde que la somme la plus importante
        if($somme > $maxSomme) {
          $maxSomme = $somme;
        }
      }

    	$index = floor($maxSomme/$this->largeurIntervalle);
    	$distrib[$index]++;
    }

    $this->resultatCalcul = new ResultatSimulation($distrib, $this->nbEchantillons, $this->largeurIntervalle);

    return $this->resultatCalcul;
  }
}
?>
