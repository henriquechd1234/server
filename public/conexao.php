<?php
$usuario = 'ezyro_37413001';
$senha = '';
$dbname= 'ezyro_37413001_cauaaa';
$port = '3306';
$host ='sql306.ezyro.com';

$mysqli = new mysqli($host,$usuario,$senha,$dbname, $port);

if ($mysqli ->error){
    die("ERRO AO SE CONECTAR AO BANCO" . $mysql -> error);
}




?>