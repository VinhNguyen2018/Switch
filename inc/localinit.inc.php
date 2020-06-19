<?php
session_start();
//session_start() : se positionne TOUJOURS en haut et en PREMIER avant les traitements php !

//------------------------------------------------
//Connexion à la BDD :
$pdo = new PDO('mysql:host=localhost;dbname=switch', 'root', 'root', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES UTF8") );
//var_dump($pdo);

//------------------------------------------------
//Definition d'une constante :
define('URL', 'http://localhost:8888/cours_ifocop_php/Switch/');

//------------------------------------------------
//déclaration de variables :
$content = '';
$error = '';

//------------------------------------------------
require_once "fonction.inc.php";
