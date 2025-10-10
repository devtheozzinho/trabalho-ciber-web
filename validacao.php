<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// realiza a conexao com o banco de dados.
include('conexao.php');

// Verifica se os campos não estão vazios
if(empty($_POST['login']) || empty($_POST['senha'])) {
    // Se entrou é porque está vazio, então redirecionamos de volta para a página de login.
    header('Location: login.html');
    exit();
}

// Armazenamos em um variável os post para utilizar mais tarde.
$login = $_POST['login'];
$senha = $_POST['senha'];

// Criamos uma variável contendo a consulta que iremos realizar.
// Essa consulta seleciona o id_usuario e nome da tabela usuario APENAS de onde o email e a senha forem iguais as do banco de dados (WHERE).
// Dessa forma, é realizado a verificação do login e senha. 
$sql = "SELECT id_usuario, nome FROM Usuario WHERE email_usuario = '$login' AND senha = '$senha'";

// Executamos a consulta da variável.
$resultado = $mysqli->query($sql) or die("Falha na execução do código SQL.");

// Verifica se encontrou algum usuário.
if($resultado->num_rows == 1) {
    // Guarda os dados da consulta na variável.
    $usuario_encontrado = $resultado->fetch_assoc();
        
    // Guarda as informações do usuário na sessão.
    $_SESSION['id_usuario'] = $usuario_encontrado['id_usuario'];
    $_SESSION['nome_usuario'] = $usuario_encontrado['nome'];

    header('Location: painel.php');

}else {
    // Se não encontrou nenhum usuário com aquele login no banco.
    echo "<h1>Falha ao logar! Login ou senha incorretos.</h1>";
    echo "<a href='login.html'>Voltar</a>";
}

?>
