<?php
session_start();
//session_start() : se positionne TOUJOURS en haut et en PREMIER avant les traitements php !

//------------------------------------------------
//Connexion à la BDD :

// -- 1on1 bdd id --
$servername = 'db5000561366.hosting-data.io';
$bddname = 'dbs538933';
$username = 'dbu167259';
$password = 'Brioche01*';


// localhost id
// $servername = 'localhost';
// $bddname = 'switch';
// $username = 'root';
// $password = 'root';

$pdo = new PDO("mysql:host=$servername;dbname=$bddname", $username, $password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES UTF8") );
//var_dump($pdo);

//------------------------------------------------
//Definition d'une constante :

// URL online
define('URL', 'https://switch.francois-nguyen.fr/');

// URL localhost

// define('URL', 'http://localhost:8888/cours_ifocop_php/Switch/');

//------------------------------------------------
//déclaration de variables :
$content = '';
$error = '';

//------------------------------------------------
require_once "fonction.inc.php";
