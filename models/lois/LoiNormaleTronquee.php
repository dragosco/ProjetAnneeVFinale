<?php
/*
 * Loi normal
*
* Represente la loi de probabilite Normal
*/
Class LoiNormaleTronquee extends LoiNormale
{
	function __construct($valeurmin , $valeurmax , $mu, $sigma)
	{
		parent::__construct($valeurmin , $valeurmax , $mu, $sigma);
	}

	function generate()
	{
		do {
			$value = parent::generate();
		} while (( $value > $this->valeurMax) OR ( $value < $this->valeurMin));

		return $value;
	}
}
