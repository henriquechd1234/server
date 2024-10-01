<?php 
if(!isset($_SESSION)){
    session_start();
}
if (!isset ($_SESSION['id'])){
    die ("Voce N ten uma conta, crie uma ou entre");

}
?>