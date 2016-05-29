<?php
/*
 * Loi beta
 *
 * Represente la loi de probabilite BETA
 */
Class LoiBeta extends LoiProbabilite
{
  var $v ;
  var $w ;

  function __construct($valeurmin , $valeurmax , $v , $w)
  {
  	$this->valeurMax = $valeurmax ;
  	$this->valeurMin = $valeurmin ;
  	$this->v = $v ;
  	$this->w = $w ;
  }

  function generate()
  {
  	return $this->beta($this->v,$this->w,$this->valeurMin,$this->valeurMax);
  }

  function beta($v, $w, $xMin, $xMax)
  {
  	if ( $v < $w )
  	{
  		return $xMin + $xMax  -  $this->beta($w,$v,$xMin,$xMax);
  	}

  	$y1 = $this->gamma(0. , 1. , $v) ;
  	$y2 = $this->gamma(0. , 1. , $w) ;

  	// $value = $xMin  + (  $xMax  - $xMin ) * $y1 / ($y1 + $y2);
  	// if($value > $xMax) return $xMax;
  	// if(($value < $xMin) OR (($y1 + $y2) == 0))  return $xMin;
    $sommeYs = ($y1 + $y2);
    if($sommeYs == 0) {
      $sommeYs = $xMin + 1;
    }

    $value = $xMin  + (  $xMax  - $xMin ) * $y1 / $sommeYs;
  	if($value > $xMax) return $xMax;
  	if($value < $xMin) return $xMin;

    return $xMin  + (  $xMax  - $xMin ) * $y1 / $sommeYs;
  	//return $xMin  + (  $xMax  - $xMin ) * $y1 / ($y1 + $y2);
  }


  function gamma($a, $b, $c)
  {
  	if(assert($b > 0 && $c > 0))
  	{

  		$A =  1 / sqrt( 2 * $c - 1 );
  		$B = $c - log( 4. );
  		$Q = $c + 1. / $A;
  		$T = 4.5;
  		$D = 1. + log( $T );
  		$C = 1. + $c / M_E;

  		if($c < 1)
  		{
  			while(1)
  			{
  				$p = $C * $this->uniform(0, 1);
  				if($p>1)
  				{
  					$y = -log(($C - $p) / $c ) ;
  					if ( $this->uniform( 0, 1 ) <= pow( $y, $c - 1. ) )
  						return $a + $b * $y;
  				}else
  				{
  					$y = pow( $p, 1. / $c );
  					if ( $this->uniform( 0., 1. ) <= exp( -$y ) )
  						return $a + $b * $y;
  				}
  			}
  		}
   		else if ($c == 1)
  		{
   			return pow( $a, $b ) ;
   		}
  		else
  		{
  			while(true)
  			{
  				$p1 = $this->uniform( 0., 1 );
  				$p2 = $this->uniform( 0., 1 );
  					$v = $A * log( $p1 / ( 1. - $p1 ) );
  					$y = $c * exp( $v );
  					$z = $p1 * $p1 * $p2;
  					$w = $B + $Q * $v - $y;
  					if ( $w + $D - $T * $z >= 0. || $w >= log( $z ) ) return $a + $b * $y;


  			}
  		}
  	}
  }
}

?>
