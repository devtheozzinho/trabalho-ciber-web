<?php

session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Erro: Você precisa estar logado para fazer uma requisição.");
}

include("../conexao.php");
// requisição com os dados que vão ser usados no cadastro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    $id_req = $_SESSION['id_usuario'];

    // Criando o array com os dados do post.
    $dados_formulario = [
        "nome" => $_POST["nome"],
        "senha" => $senha_hashada = password_hash($_POST["senha"], PASSWORD_DEFAULT),
        "email" => $_POST["email"],
        "cpf" => $_POST["cpf"],
        "sexo" => $_POST["sexo"],
        "nacionalidade" => $_POST["nacionalidade"],
        "telefone" => $_POST["telefone"],
        "endereco" => $_POST["endereco"],
        "cargo" => $_POST["cargo"]
    ];

    // Transformando o array de cima em um JSON.
    $dados_json = json_encode($dados_formulario);
    
    $sql = "INSERT INTO requests (id_user_req, tipo_acao, recurso, dados) VALUES (?, 'create', 'usuario', ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("is", $id_req, $dados_json);


    if ($stmt->execute()) {
        header("Location: sucesso.php");   // na linha de cima executa a query e o sucesso.php é a tela que irá redirecionar caso de certo a criação da solicitação.
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