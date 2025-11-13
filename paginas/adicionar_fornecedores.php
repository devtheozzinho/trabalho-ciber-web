<?php

session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Erro: Você precisa estar logado para fazer uma requisição.");
}

include("../conexao.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_req = $_SESSION['id_usuario'];

    // Pega os dados do post para um array
    $dados_formulario = [
        "razao_social" => $_POST["razao_social"],
        "email" => $_POST["email"],
        "cnpj" => $_POST["cnpj"],
        "telefone" => $_POST["telefone"],
        "departamento" => $_POST["departamento"]
    ];
    
    // transforma o array em JSON
    $dados_json = json_encode($dados_formulario);
    
    $sql = "INSERT INTO requests (id_user_req, tipo_acao, recurso, dados) 
            VALUES (?, 'create', 'fornecedor', ?)";    

    $stmt = $mysqli->prepare($sql); 
    if ($stmt === false) {
        die("Erro na preparação da Query: " . $mysqli->error);
    }
    
    $stmt->bind_param("is", $id_req, $dados_json);  
    
    if ($stmt->execute()) {
        header("Location: sucesso.php");
        exit;
    } else {
        echo "Erro ao criar solicitação: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
} 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Fornecedor -</title>
    <link rel="stylesheet" href="../css/adicionar_fornecedores.css?v=<?php echo time(); ?>">
</head>
<h2 class="titulo">Cadastro de Novo Fornecedor</h2>
<body>
    <div class="container">
        <br>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="razao_social" placeholder="Razão Social" required>
            <p>

                <input type="text" name="cnpj" id="cnpj" maxlength="16" placeholder="CNPJ">
            <p>
                <input type="text" name="email" placeholder="Email" required>
            <p>

                <input type="text" id="telefone" name="telefone" maxlength="15" placeholder="Telefone">
            <p>
                <input type="text" name="departamento" placeholder="Departamento" required>
            <p>
                <button type="submit">Solicitar Cadastro</button>
        </form>
    </div>
</body>

<script>

// Máscara CNPJ
document.getElementById('cnpj').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não for número
    value = value.slice(0, 14);
    value = value.replace(/^(\d{2})(\d)/, '$1.$2');
    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
    value = value.replace(/\.(\d{3})(\d)/, '.$1.$2');
    value = value.replace(/\/(\d{4})(\d)/, '/$1-$2');
    e.target.value = value;
});

// Máscara Telefone
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