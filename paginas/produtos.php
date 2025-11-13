<?php
include("conexao.php");

function refValues($arr)
{
    if (strnatcmp(phpversion(), '5.3') >= 0) {
        $refs = [];
        foreach ($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    return $arr;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['produto_a_excluir'])) {
    $ids_a_excluir = $_POST['produto_a_excluir'];  

    $placeholders = implode(',', array_fill(0, count($ids_a_excluir), '?'));
    $sql_delete = "DELETE FROM produto WHERE id_produto IN ($placeholders)";

    $stmt = $mysqli->prepare($sql_delete);
    $tipos = str_repeat('i', count($ids_a_excluir));
    $bind_params = array_merge([$tipos], $ids_a_excluir);
    call_user_func_array([$stmt, 'bind_param'], refValues($bind_params));

    if ($stmt->execute()) {
        echo "<p id='mensagem-status' style='color: green; font-weight: bold;'>"
            . $stmt->affected_rows . " produto(s) excluído(s) com sucesso.</p>";
    } else {
        echo "<p id='mensagem-status' style='color: red; font-weight: bold;'>Erro ao excluir: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Consulta para listar produtos (com informações principais)
$sql = "SELECT 
            p.id_produto, 
            p.nome_produto, 
            p.tipo, 
            p.valor, 
            p.data_fabricacao_produto, 
            p.data_validade_produto, 
            p.categoria,
            f.razao_social AS fornecedor,
            a.endereco_armazem AS armazem
        FROM produto p
        LEFT JOIN fornecedor f ON p.id_fornecedor = f.id_fornecedor
        LEFT JOIN armazem a ON p.id_armazem = a.id_armazem
        ORDER BY p.nome_produto ASC";

$result = $mysqli->query($sql);

if (!$result) {
    die("Erro na consulta SQL: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="css/tabela.css?v=<?php echo time(); ?>">
</head>

<body>
    <h2>Produtos cadastrados</h2>

    <a href="paginas/adicionar_produtos.php"><button>Adicionar Produto</button></a>

    <form method="POST" action="">
        <p>
            <button type="submit" class="botao-deletar">Deletar Produto</button>
        </p>

        <table>
            <tr>
                <th>Selecionar</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Valor (R$)</th>
                <th>Data Fabricação</th>
                <th>Data Validade</th>
                <th>Categoria</th>
                <th>Fornecedor</th>
                <th>Armazém</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                while ($produto = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><input type='checkbox' name='produto_a_excluir[]' value='" . htmlspecialchars($produto['id_produto']) . "'></td>";
                    echo "<td>" . htmlspecialchars($produto['nome_produto']) . "</td>";
                    echo "<td>" . htmlspecialchars($produto['tipo']) . "</td>";
                    echo "<td>" . number_format($produto['valor'], 2, ',', '.') . "</td>";
                    echo "<td>" . htmlspecialchars($produto['data_fabricacao_produto']) . "</td>";
                    echo "<td>" . htmlspecialchars($produto['data_validade_produto']) . "</td>";
                    echo "<td>" . htmlspecialchars($produto['categoria']) . "</td>";
                    echo "<td>" . htmlspecialchars($produto['fornecedor'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($produto['armazem'] ?? 'N/A') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>Nenhum produto cadastrado.</td></tr>";
            }

            $result->free();
            $mysqli->close();
            ?>
        </table>
    </form>
</body>

<script>
// Faz a mensagem de sucesso/erro sumir depois de 3 segundos
const mensagemStatus = document.getElementById('mensagem-status');
if (mensagemStatus) {
    setTimeout(() => {
        mensagemStatus.remove();
    }, 3000);
}
</script>

</html>
