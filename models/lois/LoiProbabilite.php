<?php
/*
 * LoiProbabilite
 *
 * Classe mere de toutes les lois
 */
class LoiProbabilite
{
	var $valeurMin = 1 ;
	var $valeurMax = 2 ;

	function __construct($valeurMin, $valeurMax)
	{
		$this->valeurMax = $valeurMax ;
		$this->valeurMin = $valeurMin ;
	}
	
	function uniform($min, $max)
	{
		if($min>=0 && $max>$min)
		{
			$resul = rand($min , ($max * 1000)-1);
		}else if($min< 0 && $max>0 )
		{
			$resul = rand(($min*1000) , ($max * 1000));
		}else if($max<0 && $min<$max )
		{
			$resul = rand(($min * 1000), $max );
		}else
		{
			$resul = 0 ;
		}
		$resul = $resul / 1000 ;
		return $resul ;
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
