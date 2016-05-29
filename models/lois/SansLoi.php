<?php
/*
 * Loi fixe
*
* Represente la loi de probabilite Normal
*/
Class SansLoi extends LoiProbabilite
{
	var $val;

	function __construct($val)
	{
		$this->val = $val;
	}
	
	function generate()
	{
		return $this->val;
	}

}