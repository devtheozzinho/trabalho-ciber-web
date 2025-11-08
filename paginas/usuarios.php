<?php

include("conexao.php");

if (!isset($mysqli) || $mysqli->connect_error) {
    die("Erro: Falha na conexão com o banco de dados. Verifique conexao.php.");

    include("../conexao.php");

    $sql = "SELECT nome, email_usuario, cpf_usuario, sexo, nacionalidade FROM usuario ORDER BY nome ASC";

    $result = $mysqli->query($sql);

// Verifica se a consulta foi executada com sucesso
if (!$result) {
    die("Erro na consulta: " . $mysqli->error);
    }
}

?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário - PHP Puro</title>


        <style>
            button {
            width: 200px;
            padding: 10px 20px;
            margin-top: 10px;
            border: none;
            border-radius: 8px;
            background-color: green;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        </style>
    
</head>
    <h2 class="titulo">Lista de usuário</h2>
    
<body>

<a href="paginas/adicionar_usuarios.php">
<button>Adicionar Usuário</button>
</a>
<p>

<style>
        body { font-family: Arial; background: #f4f4f4; }
        table { border-collapse: collapse; width: 50%; margin: 50px auto; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background: #007bff; color: white; }
    </style>


<table>
    <tr>
        <th>Nome</th>
        
        <th>Email</th>
        
        <th>CPF</th>

        <th>Sexo</th>

        <th>Nacionalidade</th>
        
    </tr>


  
    
</html>