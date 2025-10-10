<?php

$usuario = 'root';
$senha = 'Threff.8040';
$database = 'meu_projeto_web';
$host = 'localhost';

$mysqli = new mysqli ($host, $usuario, $senha, $database);

if($mysqli->error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->error);
}