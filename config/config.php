<?php
$host = 'localhost';     // localhost é o proprio computador
$user = 'root';          // Nome do usuario do banco de dados
$pass = '';              // Senha sempre em branco
$db = 'db_aempa';           // Nome do seu banco de dados

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

?>

