<?php
require("LoiProbabilite.php");
require("LoiTriangulaire.php");
require("LoiNormale.php");
require("LoiNormaleTronquee.php");
require("LoiBeta.php");
require("LoiRand.php");

$nbEchantillons = 100000;
//tache1
$lb = new LoiBeta(0,10,1.5,2);

//tache2
$lnt = new LoiNormaleTronquee(0,10,1.5,2);

//tache3
$ln = new LoiNormale(0,10,1.5,2);

//tache4
$lt = new LoiTriangulaire(0,10,1.5);

//tache5
$lr = new LoiRand(0,10);


$nbCat = 100;
for($cc = 0; $cc <= $nbCat; $cc++)
	$distrib[$cc] = 0;

for($i=0; $i<$nbEchantillons; $i++)
{
	$ech1 = $lb->generate();
	$ech2 = $lnt->generate();
	$ech3 = $ln->generate();
	$ech4 = $lt->generate();
	$ech5 = $lr->generate();
	$ech = $ech1 + $ech2 + $ech3 + $ech4 + $ech5;
	$index = floor($ech*2);
	$distrib[$index] ++;
}

for($cc = 0; $cc < $nbCat; $cc++)
{
	for($ll = 0; $ll < ($distrib[$cc]/300); $ll++)
		echo('X');
	echo("<br>");
}
/*
for($i=0; $i<$nbEchantillons; $i++)
{
	$ech = $lt->generate();
	$index = floor($ech*2);
	$distrib[$index] ++;
}

for($cc = 0; $cc < $nbCat; $cc++)
{
	for($ll = 0; $ll < ($distrib[$cc]/300); $ll++)
		echo('X');
	echo("<br>");
}*/


?>