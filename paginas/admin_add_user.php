<?php
if (empty($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    echo "<h1>Acesso Negado</h1>";
    exit(); 
}

include('conexao.php');
$id_admin = $_SESSION['id_usuario'];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id'])) {
    
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    if ($action == 'aprovar') {
        $stmt = $mysqli->prepare("SELECT * FROM requests WHERE id = ? AND status = 'pendente'");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $req = $result->fetch_assoc();
            $dados = json_decode($req['dados'], true);

            $mysqli->begin_transaction(); 
            try {
                // Cria usuário
                if ($req['tipo_acao'] == 'create' && $req['recurso'] == 'usuario') {
                    $sql_exec = "INSERT INTO usuario (nome, senha, email_usuario, cpf_usuario, sexo, nacionalidade, usuario_telefone, endereco_usuario, cargo, admin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
                    $stmt_exec = $mysqli->prepare($sql_exec);
                    $stmt_exec->bind_param("sssssssss", $dados['nome'], $dados['senha'], $dados['email'], $dados['cpf'],  $dados['sexo'], $dados['nacionalidade'], $dados['telefone'], $dados['endereco'], $dados['cargo']
                    );
                    $stmt_exec->execute();
                } 
                
                // Cria fornecedor
                else if ($req['tipo_acao'] == 'create' && $req['recurso'] == 'fornecedor') {
                    $sql_exec = "INSERT INTO fornecedor (razao_social, email_fornecedor, cnpj, telefone_fornecedor, departamento) VALUES (?, ?, ?, ?, ?)";
                    $stmt_exec = $mysqli->prepare($sql_exec);
                    $stmt_exec->bind_param("sssss", $dados['razao_social'], $dados['email'], $dados['cnpj'], $dados['telefone'], $dados['departamento']
                    );
                    $stmt_exec->execute();
                }

                // Cria produto
                else if ($req['tipo_acao'] == 'create' && $req['recurso'] == 'produto') {
                    $sql_exec = "INSERT INTO produto (nome_produto, categoria, valor, tipo) VALUES (?, ?, ?, ?)"; 
                    $stmt_exec = $mysqli->prepare($sql_exec);
                    $stmt_exec->bind_param("ssds", $dados['nome'], $dados['categoria'], $dados['preco'], $dados['categoria']);
                    $stmt_exec->execute();
                }

                // Cria armazém
                else if ($req['tipo_acao'] == 'create' && $req['recurso'] == 'armazem') {
                    $sql_exec = "INSERT INTO armazem (endereco_armazem, capacidade, responsavel) VALUES (?, ?, ?)";
                    $stmt_exec = $mysqli->prepare($sql_exec);
                    $stmt_exec->bind_param("sis", $dados['endereco_armazem'], $dados['capacidade'], $dados['responsavel']);
                    $stmt_exec->execute();
                }
                
                // Deleta usuário
                else if ($req['tipo_acao'] == 'delete' && $req['recurso'] == 'usuario') {
                    if ($req['id_alvo'] == $id_admin) {
                        throw new Exception("O administrador não pode aprovar a exclusão de si mesmo.");
                    }
                    $sql_exec = "DELETE FROM usuario WHERE id_usuario = ?";
                    $stmt_exec = $mysqli->prepare($sql_exec);
                    $stmt_exec->bind_param("i", $req['id_alvo']);
                    $stmt_exec->execute();
                }
                
                // Deleta fornecedor
                else if ($req['tipo_acao'] == 'delete' && $req['recurso'] == 'fornecedor') {
                    $sql_exec = "DELETE FROM fornecedor WHERE id_fornecedor = ?";
                    $stmt_exec = $mysqli->prepare($sql_exec);
                    $stmt_exec->bind_param("i", $req['id_alvo']);
                    $stmt_exec->execute();
                }

                // Deleta produto
                else if ($req['tipo_acao'] == 'delete' && $req['recurso'] == 'produto') {
                    $sql_exec = "DELETE FROM produto WHERE id_produto = ?";
                    $stmt_exec = $mysqli->prepare($sql_exec);
                    $stmt_exec->bind_param("i", $req['id_alvo']);
                    $stmt_exec->execute();
                }

                // Deleta armazem
                else if ($req['tipo_acao'] == 'delete' && $req['recurso'] == 'armazem') {
                    $sql_exec = "DELETE FROM armazem WHERE id_armazem = ?";
                    $stmt_exec = $mysqli->prepare($sql_exec);
                    $stmt_exec->bind_param("i", $req['id_alvo']);
                    $stmt_exec->execute();
                }


                $sql_update = "UPDATE requests SET status = 'aprovado', id_admin_aprovou = ?, data_aprovacao = NOW() WHERE id = ?";
                $stmt_update = $mysqli->prepare($sql_update);
                $stmt_update->bind_param("ii", $id_admin, $request_id);
                $stmt_update->execute();

                $mysqli->commit(); 
                echo "<p id='mensagem-status' style='color:green; font-weight: bold;'>Requisição #$request_id aprovada com sucesso!</p>";

            } catch (Exception $e) {
                $mysqli->rollback(); 
                echo "<p id='mensagem-status' style='color:red; font-weight: bold;'>Erro ao aprovar requisição: " . $e->getMessage() . "</p>";
            }
        }
        
    } else if ($action == 'recusar') {
        $sql_update = "UPDATE requests SET status = 'recusado', id_admin_aprovou = ?, data_aprovacao = NOW() WHERE id = ?";
        $stmt_update = $mysqli->prepare($sql_update);
        $stmt_update->bind_param("ii", $id_admin, $request_id);
        $stmt_update->execute();
        echo "<p id='mensagem-status' style='color:orange; font-weight: bold;'>Requisição #$request_id recusada.</p>";
    }
}



$sql_select = "SELECT r.*, u.nome as nome_solicitante 
               FROM requests r
               JOIN usuario u ON r.id_user_req = u.id_usuario
               WHERE r.status = 'pendente'
               ORDER BY r.data_solicitacao ASC";
$resultado = $mysqli->query($sql_select);

?>

<h2>Aprovação de Requisições</h2>

<link rel="stylesheet" href="css/tabela.css?v=<?php echo time(); ?>">

<table>
    <tr>
        <th>ID</th>
        <th>Solicitante</th>
        <th>Ação</th>
        <th>Recurso</th>
        <th>ID Alvo</th>
        <th>Dados (Resumo)</th>
        <th>Data</th>
        <th>Aprovar / Recusar</th>
    </tr>

    <?php if ($resultado->num_rows > 0): ?>
        <?php while($req = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?php echo $req['id']; ?></td>
                <td><?php echo htmlspecialchars($req['nome_solicitante']); ?></td>
                <td><strong><?php echo strtoupper($req['tipo_acao']); ?></strong></td>
                <td><?php echo htmlspecialchars($req['recurso']); ?></td>
                <td><?php echo $req['id_alvo'] ?? 'N/A'; ?></td>
                
                <td title="<?php echo htmlspecialchars($req['dados']); ?>">
                    <small><?php echo htmlspecialchars(substr($req['dados'], 0, 50)) . '...'; ?></small>
                </td>
                
                <td><?php echo date('d/m/Y H:i', strtotime($req['data_solicitacao'])); ?></td>
                
                <td style="display: flex; gap: 5px;">
                    <form method="POST" action="painel.php?pagina=admin_add_user">
                        <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                        <button type="submit" name="action" value="aprovar" style="background-color: green;">Aprovar</button>
                    </form>
                    <form method="POST" action="painel.php?pagina=admin_add_user">
                        <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                        <button type="submit" name="action" value="recusar" class="botao-deletar">Recusar</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="8">Nenhuma requisição pendente.</td></tr>
    <?php endif; ?>
    <?php $resultado->free(); $mysqli->close(); ?>
</table>

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