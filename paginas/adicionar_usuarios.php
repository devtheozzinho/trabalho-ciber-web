<?php

session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Erro: Você precisa estar logado para fazer uma requisição.");
}

include("../conexao.php");
// requisição com os dados que vão ser usados no cadastro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $senha = password_hash( $_POST["senha"], PASSWORD_DEFAULT);
    $email = $_POST["email"];
    $cpf = $_POST["cpf"];
    $sexo = $_POST["sexo"];
    $nacionalidade = $_POST["nacionalidade"];
    $telefone = $_POST["telefone"];
    $endereco = $_POST["endereco"];
    $cargo = $_POST["cargo"];
    $id_req = $_SESSION['id_usuario'];
    
    
    //query que vai ser executado com os inserts sendo inseridos no banco
    $sql = "INSERT INTO req_user (nome, senha, email, cpf, sexo, nacionalidade, telefone, endereco, cargo, id_user_req) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssssssssi", $nome, $senha, $email, $cpf, $sexo, $nacionalidade, $telefone, $endereco, $cargo, $id_req);

    if ($stmt->execute()) {
        header("Location: sucesso.php");   // na linha de cima executa a query e o sucesso.php é a tela que irá redirecionar caso de certo o cadastro.
        exit;
    } else {
        echo "Erro ao cadastrar: " . $stmt->error; // caso de erro no cadastro 
    }

    $stmt->close();
    $mysqli->close(); //encerra a conexão com o banco
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário - PHP Puro</title>
    <link rel="stylesheet" href="../css/adicionar_usuarios.css?v=<?php echo time(); ?>">

</head>
<h2 class="titulo">Cadastro de Novo Usuário</h2>

<body>

    <div class="container">

        <br>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="nome" placeholder="Nome" required>
            <p>

                <input type="password" name="senha" placeholder="Senha" required>
            <p>

                <input type="email" name="email" placeholder="Email" required>
            <p>

                <input type="text" name="cpf" placeholder="CPF" required>
            <p>

                <input type="text" name="sexo" placeholder="Sexo" required>
            <p>

                <input type="text" name="nacionalidade" placeholder="Nacionalidade" required>
            <p>

                <input type="text" name="telefone" placeholder="Telefone" required>
            <p>

                <input type="text" name="endereco" placeholder="Endereço" required>
            <p>

                <input type="text" name="cargo" placeholder="Cargo" required>
            <p>
                <button type="submit">Cadastrar</button>
        </form>
    </div>

</body>

</html>