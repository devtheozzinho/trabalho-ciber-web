<?php
include("../conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_produto = $_POST["nome_produto"];
    $tipo = $_POST["tipo"];
    $valor = $_POST["valor"];
    $data_validade = $_POST["data_validade"];
    $data_fabricacao = $_POST["data_fabricacao"];
    $categoria = $_POST["categoria"];
    $id_lote = $_POST["id_lote"];
    $id_fornecedor = $_POST["id_fornecedor"];
    $id_usuario = $_POST["id_usuario"];
    $id_armazem = $_POST["id_armazem"];

    $sql = "INSERT INTO produto (
                nome_produto, tipo, valor, data_validade_produto, 
                data_fabricacao_produto, categoria, id_lote, id_fornecedor, id_usuario, id_armazem
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação da Query: " . $mysqli->error);
    }

    $stmt->bind_param("ssdsssiiii",
        $nome_produto,
        $tipo,
        $valor,
        $data_validade,
        $data_fabricacao,
        $categoria,
        $id_lote,
        $id_fornecedor,
        $id_usuario,
        $id_armazem
    );

    if ($stmt->execute()) {
        header("Location: sucesso.php");
        exit;
    } else {
        echo "Erro ao cadastrar no banco de dados: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="../css/produtos.css?v=<?php echo time(); ?>">
</head>

<body>
    <h2 class="titulo">Cadastro de Novo Produto</h2>
    <div class="container">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="nome_produto" placeholder="Nome do Produto" required><p>
            <input type="text" name="tipo" placeholder="Tipo" required><p>
            <input type="number" step="0.01" name="valor" placeholder="Valor (R$)" required><p>
            <label>Data de Fabricação:</label>
            <input type="date" name="data_fabricacao" required><p>
            <label>Data de Validade:</label>
            <input type="date" name="data_validade" required><p>
            <input type="text" name="categoria" placeholder="Categoria" required><p>
            <input type="number" name="id_lote" placeholder="ID do Lote" required><p>
            <input type="number" name="id_fornecedor" placeholder="ID do Fornecedor" required><p>
            <input type="number" name="id_usuario" placeholder="ID do Usuário" required><p>
            <input type="number" name="id_armazem" placeholder="ID do Armazém" required><p>

            <button type="submit">Cadastrar Produto</button>
        </form>
    </div>
</body>
</html>
