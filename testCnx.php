<?php
require("models/cnx.php");
session_start();

$email = $_GET['email'];
$pass = $_GET['pass'];


$bdd = new PDO('mysql:host=localhost;dbname=m1bdv2;charset=utf8', 'root', '');

// Vérification des identifiants
$this->bdd = getBdd();
$req = $bdd->prepare('SELECT email FROM membre WHERE email = :email AND pass = :pass');
$req->execute(array(
    'email' => $email,
    'pass' => $pass));

$resultat = $req->fetch();

if (!$resultat)
{
    $_SESSION['Error'] = "The username and password don't match";
    header('Location: login.php');}
else
{
    $_SESSION['email'] = $resultat['email'];
    header('Location: index.php');
}
?>
