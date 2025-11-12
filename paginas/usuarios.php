<?php
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['usuarios_a_excluir'])) {
    
    if (!isset($_SESSION['id_usuario'])) {
        die("Você precisa estar logado para fazer uma solicitação.");
    }

    $ids_a_excluir = $_POST['usuarios_a_excluir'];
    $id_req = $_SESSION['id_usuario'];
    
    $sql_insert_request = "INSERT INTO requests (id_user_req, tipo_acao, recurso, id_alvo) 
                           VALUES (?, 'delete', 'usuario', ?)";
    $stmt = $mysqli->prepare($sql_insert_request);

    $erros = [];
    $sucessos = 0;

    foreach ($ids_a_excluir as $id_alvo) {
        if ($id_alvo == $_SESSION['id_usuario']) {
            $erros[] = "Você não pode solicitar a exclusão de si mesmo (ID: $id_alvo).";
            continue;
        }
        
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

// LÓGICA DE EXIBIÇÃO DA TABELA (ESTAVA FALTANDO)
$sql_select = "SELECT id_usuario, nome, email_usuario, cpf_usuario, cargo, admin 
               FROM usuario ORDER BY nome ASC";
$result_select = $mysqli->query($sql_select);

if (!$result_select) {
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

    <form method="POST" action="painel.php?pagina=usuarios">
        <p>
            <button type="submit" class="botao-deletar">Solicitar Exclusão do usuário</button>
        </p>

        <table>
            <tr>
                <th>Selecionar</th>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>CPF</th>
                <th>Cargo</th>
                <th>Admin</th>
            </tr>

            <?php
            if ($result_select->num_rows > 0) {
                while ($usuario = $result_select->fetch_assoc()) {
                    echo "<tr>";
                    // adicioneii disabled pro usuário n se selecionar.
                    $disabled = ($usuario['id_usuario'] == $_SESSION['id_usuario']) ? 'disabled' : '';
                    echo "<td><input type='checkbox' name='usuarios_a_excluir[]' value='" 
                         . htmlspecialchars($usuario['id_usuario']) . "' $disabled></td>";
                    echo "<td>" . htmlspecialchars($usuario['id_usuario']) . "</td>";
                    echo "<td>" . htmlspecialchars($usuario['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($usuario['email_usuario']) . "</td>";
                    echo "<td>" . htmlspecialchars($usuario['cpf_usuario']) . "</td>";
                    echo "<td>" . htmlspecialchars($usuario['cargo']) . "</td>";
                    echo "<td>" . ($usuario['admin'] ? 'Sim' : 'Não') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Nenhum usuário cadastrado.</td></tr>";
            }
            ?>
        </table>
    </form>
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
?>