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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cpf = preg_replace('/\D/', '', $_POST["cpf"]); // Remove pontos e traços
    $telefone = preg_replace('/\D/', '', $_POST["telefone"]); // Remove parênteses e hífens

    // Validação CPF (11 dígitos)
    if (!preg_match("/^[0-9]{11}$/", $cpf)) {
        die("CPF inválido. Deve conter 11 números.");
    }

    // Validação Telefone (10 ou 11 dígitos)
    if (!preg_match("/^[0-9]{10,11}$/", $telefone)) {
        die("Telefone inválido. Deve conter 10 ou 11 números.");
    }

    echo "✅ CPF e Telefone válidos!";
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
            <input type="text" name="nome" placeholder="Informe seu Nome" required>
            <p>

                <input type="password" name="senha" placeholder="Digite uma Senha" required>
            <p>

                <input type="email" name="email" placeholder="Insira seu email" required>
            <p>

                <input type="text" id="cpf" name="cpf" maxlength="14" placeholder="Insira CPF" required>
            <p>

                <input type="text" name="sexo" placeholder="Informe seu Sexo" required>
            <p>

                <input type="text" name="nacionalidade" maxlength="14" placeholder="informe sua Nacionalidade" required>
            <p>

                <input type="tel" name="telefone" id="telefone" maxlength="15" placeholder="Digite seu Telefone"
                    required>
            <p>

                <input type="text" name="endereco" placeholder="Digite um Endereço" required>
            <p>

                <input type="text" name="cargo" placeholder="Digite seu Cargo" required>
            <p>
                <button type="submit">Cadastrar</button>
        </form>
    </div>

</body>

<script>
// Mascara para formatar o cpf e deixão bunitão
document.getElementById('cpf').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não for número
    if (value.length > 11) value = value.slice(0, 11);
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d)/, '$1.$2');
    value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    e.target.value = value;
});

// Máscara  prara formatar o telefone e deixar bunitinho =
document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 11) value = value.slice(0, 11);
    if (value.length <= 10) {
        value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
    } else {
        value = value.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
    }
    e.target.value = value;
});
</script>

</html>