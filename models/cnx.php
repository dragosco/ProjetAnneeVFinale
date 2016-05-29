<?php
function getBdd() {
	try
	{
		// On se connecte à MySQL
		$bdd = new PDO('mysql:host=localhost;dbname=m1bdv2;charset=utf8', 'root', '');
	}
	catch(Exception $e)
	{
		// En cas d'erreur, on affiche un message et on arrête tout
	        die('Erreur : '.$e->getMessage());
	}

  //$bdd = new PDO('mysql:host=localhost;dbname=monblog;charset=utf8', 'root', '');
  return $bdd;
}
// Si tout va bien, on peut continuer
?>
