<?php
// server/connection.php

// Configuração do banco de dados
$host = 'localhost';
$dbname = 'cms_db';
$username = 'root';
$password = '';

// Conectar ao banco de dados
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}

?>
