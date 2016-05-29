<?php
/*
 * Loi normal
*
* Represente la loi de probabilite Normal
*/
Class LoiNormale extends LoiProbabilite
{
	var $mu ;
	var $sigma ;
	function __construct($valeurmin , $valeurmax , $mu, $sigma)
	{
		$this->valeurMax = $valeurmax ;
		$this->valeurMin = $valeurmin ;
		$this->sigma = $sigma ;
		$this->mu = $mu ;
	}

	function generate()
	{
		$p =0 ;
		$valeur1 = 0 ;
		assert($this->sigma>0);
		do {
			$valeur1  = $this->uniform(-1, 1);
			$valeur2 = $this->uniform(-1, 1) ;
			$p = $valeur1 * $valeur1 + $valeur2 * $valeur2 ;

		} while ( $p >= 1);
		$value = $this->mu + $this->sigma * $valeur1 * sqrt( -2. * log( $p ) / $p );

		return $value;
	}

}
