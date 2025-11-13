<?php
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['produtos_a_excluir'])) {
    
    if (!isset($_SESSION['id_usuario'])) {
        die("Você precisa estar logado para fazer uma solicitação.");
    }

    $ids_a_excluir = $_POST['produtos_a_excluir'];
    $id_req = $_SESSION['id_usuario'];
    
    $sql_insert_request = "INSERT INTO requests (id_user_req, tipo_acao, recurso, id_alvo) 
                           VALUES (?, 'delete', 'produto', ?)";
    $stmt = $mysqli->prepare($sql_insert_request);

    $erros = [];
    $sucessos = 0;

    foreach ($ids_a_excluir as $id_alvo) {
        $stmt->bind_param("ii", $id_req, $id_alvo);
        
        if ($stmt->execute()) {
            $sucessos++;
        } else {
            $erros[] = $stmt->error;
        }
    }

    if ($sucessos > 0) {
        echo "<p id='mensagem-status' style='color: green; font-weight: bold;'>"
            . $sucessos . " solicitação(ões) de exclusão enviada(s) com sucesso.</p>";
    }
    if (!empty($erros)) {
        echo "<p id='mensagem-status' style='color: red; font-weight: bold;'>Erros: " . implode(", ", $erros) . "</p>";
    }

    $stmt->close();
}

// Query para exibir os produtos.
$sql_select = "SELECT id_produto, nome_produto, categoria, tipo, valor, data_validade_produto 
               FROM produto ORDER BY nome_produto ASC";
$result_select = $mysqli->query($sql_select);

if (!$result_select) {
    die("Erro na consulta SQL: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="css/tabela.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/produtos.css?v=<?php echo time(); ?>"> 
</head>
<body>
    <div class="container-produtos">
        <h2>Gerenciamento de Produtos</h2>
        
        <div class="botoes-produto">
            <a href="paginas/adicionar_produtos.php">
                <button class="btn-acao">Adicionar Produto</button>
            </a>
            <button class="btn-acao" onclick="alert('Funcionalidade de Editar não implementada.')">Editar Produto</button>
        </div>

        <form method="POST" action="painel.php?pagina=produtos">
            <p>
                <button type="submit" class="botao-deletar">Solicitar Exclusão de Produto</button>
            </p>

            <table>
                <tr>
                    <th>Selecionar</th>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Tipo</th>
                    <th>Valor (R$)</th>
                    <th>Validade</th>
                </tr>

                <?php
                if ($result_select->num_rows > 0) {
                    while ($produto = $result_select->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='produtos_a_excluir[]' value='" 
                             . htmlspecialchars($produto['id_produto']) . "'></td>";
                        echo "<td>" . htmlspecialchars($produto['id_produto']) . "</td>";
                        echo "<td>" . htmlspecialchars($produto['nome_produto']) . "</td>";
                        echo "<td>" . htmlspecialchars($produto['categoria']) . "</td>";
                        echo "<td>" . htmlspecialchars($produto['tipo']) . "</td>";
                        echo "<td>" . htmlspecialchars(number_format($produto['valor'], 2, ',', '.')) . "</td>";
                        echo "<td>" . htmlspecialchars(date('d/m/Y', strtotime($produto['data_validade_produto']))) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Nenhum produto cadastrado.</td></tr>";
                }
                ?>
            </table>
        </form>
    </div>
</body>
<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const mensagemStatus = document.getElementById('mensagem-status');
    if (mensagemStatus) {
        setTimeout(() => {
            mensagemStatus.remove();
        }, 3000);
    }
});
</script>
</html>
<?php
$result_select->free();
?>