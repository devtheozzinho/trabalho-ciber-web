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

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['usuarios_a_excluir'])) {
    $ids_a_excluir = $_POST['usuarios_a_excluir'];

    // Cria a query dinâmica com placeholders
    $placeholders = implode(',', array_fill(0, count($ids_a_excluir), '?'));
    $sql_delete = "DELETE FROM usuario WHERE id_usuario IN ($placeholders)";

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

// --- Consulta os usuários novamente ---
$sql = "SELECT id_usuario, nome, email_usuario, cpf_usuario, sexo, nacionalidade, usuario_telefone, endereco_usuario, cargo 
        FROM usuario ORDER BY nome ASC";

$result = $mysqli->query($sql);

if (!$result) {
    die("Erro na consulta SQL: " . $mysqli->error);
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
    <h2>Lista de Usuários</h2>

    <a href="paginas/adicionar_usuarios.php"><button>Adicionar Usuário</button></a>

    <form method="POST" action="">
        <p>
            <button type="submit" class="botao-deletar">Deletar Usuário</button>
        </p>

        <table>
            <tr>
                <th>Selecionar</th>
                <th>Nome</th>
                <th>Email</th>
                <th>CPF</th>
                <th>Sexo</th>
                <th>Nacionalidade</th>
                <th>Telefone</th>
                <th>Endereço</th>
                <th>Cargo</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                while ($usuario = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><input type='checkbox' name='usuarios_a_excluir[]' value='" . htmlspecialchars($usuario['id_usuario']) . "'></td>";
                    echo "<td>" . htmlspecialchars($usuario['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($usuario['email_usuario']) . "</td>";
                    echo "<td>" . htmlspecialchars($usuario['cpf_usuario']) . "</td>";
                    echo "<td>" . htmlspecialchars($usuario['sexo']) . "</td>";
                    echo "<td>" . htmlspecialchars($usuario['nacionalidade']) . "</td>";
                    echo "<td>" . htmlspecialchars($usuario['usuario_telefone']) . "</td>";
                    echo "<td>" . htmlspecialchars($usuario['endereco_usuario']) . "</td>";
                    echo "<td>" . htmlspecialchars($usuario['cargo']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>Nenhum usuário cadastrado.</td></tr>";
            }

            $result->free();
            $mysqli->close();
            ?>
        </table>
    </form>

</body>
<script>
// Verifica se a mensagem de status (a que tem o ID 'mensagem-status') existe
const mensagemStatus = document.getElementById('mensagem-status');

if (mensagemStatus) {
    const tempoParaDesaparecer = 3000;

    setTimeout(() => {
        mensagemStatus.remove();

    }, tempoParaDesaparecer);
}
</script>

</html>