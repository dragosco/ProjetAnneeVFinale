<?php
require("lois/LoiProbabilite.php");
require("lois/LoiTriangulaire.php");
require("lois/LoiNormale.php");
require("lois/LoiNormaleTronquee.php");
require("lois/LoiBeta.php");
require("lois/LoiRand.php");

$nbEchantillons = 10000;

$lb = new LoiBeta(0,60,1.5,2); //tache1
$lnt = new LoiNormaleTronquee(0,50,25,5); //tache2
$lt = new LoiTriangulaire(0,55,1.5);
$lr = new LoiRand(0,45);

$max = 60 + 50 + 55 + 45;
$largeurIntervalle = 10;
$nbCat = floor(($max / $largeurIntervalle) + 1);
echo($nbCat);

//
for($cc = 0; $cc <= $nbCat; $cc++)
	$distrib[$cc] = 0;

for($i=0; $i<$nbEchantillons; $i++)
{
	$ech1 = $lb->generate();
	$ech2 = $lnt->generate();
	$ech3 = $lt->generate();
	$ech4 = $lr->generate();
	$ech = $ech1 + $ech2 + $ech3 + $ech4;
	$index = floor($ech/$largeurIntervalle);
	$distrib[$index] ++;
}

for($cc = 0; $cc < $nbCat; $cc++)
{
	for($ll = 0; $ll < ($distrib[$cc]); $ll++)
		echo('X');
	echo("<br>");
}

for($cc = 0; $cc < $nbCat; $cc++)
{
	echo($cc*10);
	echo(' - ');
	echo($cc*10 + 9);
	echo(' : ');
	echo($distrib[$cc]/10000);
	echo("<br>");
}
?>