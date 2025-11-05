


<?php
// Inclui o arquivo de conexão
// include 'conexao.php';
require_once(__DIR__ . "/../conexao.php"); 

$mensagem = ""; // Variável para armazenar mensagens de status

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Recebe e sanitiza os dados do formulário
    $nome = $conn->real_escape_string($_POST['nome']); 
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha']; 
    
    // EXEMPLO DE HASHEAMENTO SEGURO (Recomendado)
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT); 
    

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha_hash')";
    
    // 3. Executa a query
    if ($conn->query($sql) === TRUE) {
        $mensagem = "<p style='color: green;'>Usuário cadastrado com sucesso!</p>";
    } else {
        $mensagem = "<p style='color: red;'>Erro ao cadastrar usuário: " . $conn->error . "</p>";
    }
}

// 4. Fecha a conexão
// $conn->close();
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


  
    
</html>