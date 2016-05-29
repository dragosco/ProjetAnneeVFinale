<?php
Class LoiRand extends LoiProbabilite
{

  function generate()
  {
	 	return rand($this->valeurMin , $this->valeurMax) ;
  }
}

?>
