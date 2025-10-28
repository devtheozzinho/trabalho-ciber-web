


<?php
// Inclui o arquivo de conexão
// include 'conexao.php';
require_once(__DIR__ . "/../conexao.php"); 

$mensagem = ""; // Variável para armazenar mensagens de status

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Recebe e sanitiza os dados do formulário
    $nome = $conn->real_escape_string($_POST['nome']); // Método rudimentar de sanitização
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha']; // A senha deve ser *hasheada* antes de ser salva (veja a observação)
    
    // EXEMPLO DE HASHEAMENTO SEGURO (Recomendado)
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT); 
    
    // 2. Prepara a query SQL para inserção
    // Usaremos $senha_hash para a senha no banco de dados
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
    
</head>
    <h2 class="titulo">Cadastro de Novo Usuário</h2>
    <link rel="stylesheet" href="css/users.css">
<body>

<div class="container">
  
    <br>
    
    <?php echo $mensagem; ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <p>

        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        <p>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        <p>

        <label for="CPF">CPF:</label>
        <input type="number" id="CPF" name="CPF" required>
        <p>

        <button type="button" onclick="alert('Sucesso!')">Cadastrar</button>

    </form>
</div>

</body>
</html>