<?php
/*
 * Loi Triangulaire
 *
 */

Class LoiTriangulaire extends LoiProbabilite
{
	var $c ;
	function __construct($valeurmin , $valeurmax , $c)
	{
		$this->valeurMax = $valeurmax ;
		$this->valeurMin = $valeurmin ;
		$this->c = $c ;

	}

	function generate()
	{
		//assert( $this->valeurMin  < $this->valeurMax  && $this->valeurMin <= $this->c && $this->c <= $this->valeurMax );
		$p = $this->uniform( 0, 1);
		$q = 1 - $p;

		if ( $p <= ( $this->c - $this->valeurMin ) / ( $this->valeurMax - $this->valeurMin ) )
			return $this->valeurMin + sqrt( ( $this->valeurMax - $this->valeurMin ) * ( $this->c - $this->valeurMin ) * $p );
		else
			return $this->valeurMax - sqrt( ( $this->valeurMax - $this->valeurMin ) * ( $this->valeurMax - $this->c ) * $q );

	}
}
?>
