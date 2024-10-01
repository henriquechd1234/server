<?php
$usuario = 'avnadmin';
$senha = 'AVNS_6zJohm7nIe9u_7o21HU';
$dbname= 'defaultdb';
$port = '11991';
$host ='mysql-5e358e5-henricaua18-0424.k.aivencloud.com';

$mysqli = new mysqli($host,$usuario,$senha,$dbname, $port);

if ($mysqli ->error){
    die("ERRO AO SE CONECTAR AO BANCO" . $mysql -> error);
}




?>