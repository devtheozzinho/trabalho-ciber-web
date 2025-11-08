<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('conexao.php');

// Verifica se os campos não estão vazios
if(empty($_POST['login']) || empty($_POST['senha'])) {
    header('Location: login.html');
    exit();
}

$login = $_POST['login'];
$senha_digitada = $_POST['senha'];

// Criamos uma variável contendo a consulta que iremos realizar.
// Essa consulta seleciona o id_usuario, nome e senha (com hash) da tabela usuario onde email_usuario = ? (definido posteriormente).
$sql = "SELECT id_usuario, nome, senha, admin FROM usuario WHERE email_usuario = ?";
$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    die("Erro ao preparar a consulta: " . $mysqli->error);
}

$stmt-> bind_param("s", $login);

$stmt->execute();

// Executamos a consulta da variável.
$resultado = $stmt->get_result();

// Verifica se encontrou algum usuário.
if($resultado->num_rows == 1) {
    // Guarda os dados da consulta na variável.
    $usuario_encontrado = $resultado->fetch_assoc();
    $senha_hashada = $usuario_encontrado['senha'];

    // Por meio do password_verify() é verificado se a senha digitado (texto puro) bate com a senha do banco (com hash).
    if (password_verify($senha_digitada,$senha_hashada)) {
        session_regenerate_id(true);

        // Guarda as informações do usuário na sessão.
        $_SESSION['id_usuario'] = $usuario_encontrado['id_usuario'];
        $_SESSION['nome_usuario'] = $usuario_encontrado['nome'];
        $_SESSION['admin'] = $usuario_encontrado['admin'];

        header('Location: painel.php');
        exit();
    }
        
    
}


echo "<h1>Falha ao logar! Login ou senha incorretos.</h1>";
echo "<a href='login.html'>Voltar</a>";

$stmt->close();
$mysqli->close();

?>