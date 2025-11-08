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

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['fornecedor_a_excluir'])) {
    $ids_a_excluir = $_POST['fornecedor_a_excluir'];  

    $placeholders = implode(',', array_fill(0, count($ids_a_excluir), '?'));
    $sql_delete = "DELETE FROM fornecedor WHERE id_fornecedor IN ($placeholders)";  // Requisição para excluir do banco 

    $stmt = $mysqli->prepare($sql_delete);
    $tipos = str_repeat('i', count($ids_a_excluir));
    $bind_params = array_merge([$tipos], $ids_a_excluir);
    call_user_func_array([$stmt, 'bind_param'], refValues($bind_params));


    if ($stmt->execute()) {
    echo "<p id='mensagem-status' style='color: green; font-weight: bold;'>"
        . $stmt->affected_rows . " usuário(s) excluído(s) com sucesso.</p>";
} else {
    echo "<p id='mensagem-status' style='color: red; font-weight: bold;'>Erro ao excluir: " . $stmt->error . "</p>";
}

$stmt->close();
}

$sql = "SELECT id_fornecedor, razao_social, email_fornecedor, cnpj, telefone_fornecedor, departamento
        FROM fornecedor ORDER BY razao_social ASC";  // Query que mostra os forncedores que tem no banco e ordena em ordem alfabética

$result = $mysqli->query($sql);    // resultado da query

if (!$result) {
    die("Erro na consulta SQL: " . $mysqli->error);  // mensagem caso de erro na query
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
    <link rel="stylesheet" href="css/tabela.css?v=<?php echo time(); ?>">

</head>

<body>
    <h2>Fornecedores cadastrados</h2>

    <a href="paginas/adicionar_fornecedores.php"><button>Adicionar Fornecedor</button></a>

    <form method="POST" action="">
        <p>
            <button type="submit" class="botao-deletar">Deletar Fornecedor</button>
        </p>

        <table>
            <tr>
                <th>Selecionar</th>
                <th>Razão Social</th>
                <th>Email</th>
                <th>CNPJ</th>
                <th>Telefone</th>
                <th>Departamento</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                while ($fornecedor = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><input type='checkbox' name='fornecedor_a_excluir[]' value='" . htmlspecialchars($fornecedor['id_fornecedor']) . "'></td>";
                    echo "<td>" . htmlspecialchars($fornecedor['razao_social']) . "</td>";
                    echo "<td>" . htmlspecialchars($fornecedor['email_fornecedor']) . "</td>";
                    echo "<td>" . htmlspecialchars($fornecedor['cnpj']) . "</td>";
                    echo "<td>" . htmlspecialchars($fornecedor['telefone_fornecedor']) . "</td>";
                    echo "<td>" . htmlspecialchars($fornecedor['departamento']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>Nenhum fornecedor cadastrado.</td></tr>";
            }

            $result->free();
            $mysqli->close();
            ?>
        </table>
    </form>

</body>
<script>
const mensagemStatus = document.getElementById('mensagem-status');

if (mensagemStatus) {
    const tempoParaDesaparecer = 3000;

    setTimeout(() => {
        mensagemStatus.remove();

    }, tempoParaDesaparecer);
}
</script>




</html>