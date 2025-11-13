<?php

session_start();

include("../conexao.php"); 

if (!isset($_SESSION['id_usuario'])) {
    die("Erro: Você precisa estar logado para fazer uma requisição.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_req = $_SESSION['id_usuario'];

    // Criando o array com os dados do post.
    $dados_formulario = [
        "nome" => $_POST["nome"],
        "categoria" => $_POST["categoria"],
        "quantidade" => $_POST["quantidade"],
        "preco" => $_POST["preco"],
        "fornecedor" => $_POST["fornecedor"]
    ];
    
    // Transformando o array de cima em um JSON.
    $dados_json = json_encode($dados_formulario);
    
    $sql = "INSERT INTO requests (id_user_req, tipo_acao, recurso, dados) 
            VALUES (?, 'create', 'produto', ?)";    

    $stmt = $mysqli->prepare($sql); 
    if ($stmt === false) {
        die("Erro na preparação da Query: " . $mysqli->error);
    }
    
    $stmt->bind_param("is", $id_req, $dados_json);  
    
    if ($stmt->execute()) {
        header("Location: sucesso.php");
        exit;
    } else {
        echo "Erro ao criar solicitação de produto: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
} 
?>
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="../css/produtos.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="container-adicionar">
        <h2 class="titulo">Adicionar Novo Produto</h2>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            
            <input type="text" name="nome" placeholder="Nome do Produto" required>
            <p></p>

            <input type="text" name="categoria" placeholder="Categoria" required>
            <p></p>

            <input type="number" name="quantidade" placeholder="Quantidade em Estoque" required>
            <p></p>

            <input type="number" name="preco" step="0.01" placeholder="Preço (R$)" required>
            <p></p>

            <input type="text" name="fornecedor" placeholder="Fornecedor">
            <p></p>

            <button type="submit">Solicitar Cadastro de Produto</button>
        </form>
    </div>
</body>
</html>