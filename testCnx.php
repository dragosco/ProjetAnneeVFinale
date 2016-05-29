<?php
require("cnx.php");

$email = $_GET['email'];
$pass = $_GET['pass'];
// Vérification des identifiants
$req = $bdd->prepare('SELECT email FROM membre WHERE email = :email AND pass = :pass');
$req->execute(array(
    'email' => $email,
    'pass' => $pass));

$resultat = $req->fetch();

if (!$resultat)
{header('Location: login.php');}
else
{
    session_start();
    $_SESSION['email'] = $resultat['email'];
    header('Location: index.php');
    exit();
}
?>