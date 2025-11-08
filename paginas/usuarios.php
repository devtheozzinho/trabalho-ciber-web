<?php
include("conexao.php");


if (!isset($mysqli) || $mysqli->connect_error) {
    // Se a conexão falhar, o script PARA aqui e exibe a mensagem de erro.
    die("Erro: Falha na conexão com o banco de dados. Verifique conexao.php. Erro: " . $mysqli->connect_error);
}

$sql = "SELECT id_usuario, nome, email_usuario, cpf_usuario, sexo, nacionalidade, usuario_telefone, endereco_usuario, cargo FROM usuario ORDER BY nome ASC";

$result = $mysqli->query($sql);

if (!$result) {
    die("Erro na consulta SQL: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuário - PHP Puro</title>
</head>
<h2 class="titulo">Lista de usuário</h2>
<body>

<a href="paginas/adicionar_usuarios.php">
<button>Adicionar Usuário</button>
</a>
<p>
    
<form method="POST" action="excluir_usuarios.php"> 
    <p>
    <button type="button" id="selecionarTodos">Deletar Usuário</button>
    </p>

    <table>
        <tr>
        <th>Sel.</th> 
        <th>Nome</th>
        <th>Email</th>
        <th>CPF</th>
        <th>Sexo</th>
        <th>Nacionalidade</th>
        <th>Telefone</th>
        <th>Endereço</th>
        <th>Cargo</th>

        <?php
        if ($result->num_rows > 0) {
            while ($usuario = $result->fetch_assoc()) {
                echo "<tr>";

                echo "<tr>";
                echo "<td>" . htmlspecialchars($usuario['nome']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['email_usuario']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['cpf_usuario']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['sexo']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['nacionalidade']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['usuario_telefone']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['endereco_usuario']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['cargo']) . "</td>";
                echo "</tr>";
               
                echo "<td><input type='checkbox' name='usuarios_a_excluir[]' value='" . htmlspecialchars($usuario['id_usuario']) . "'></td>";
                
                echo "<td>" . htmlspecialchars($usuario['nome']) . "</td>";
                // ... Resto das colunas ...
                echo "</tr>";
            }
        }
        // ...
        ?>
    </table>
</form>

<style>
    button {
            width: 200px;
            padding: 10px 20px;
            margin-top: 10px;
            border: none;
            border-radius: 8px;
            background-color: green;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        body { 
            font-family:Arial; 
            background: #f4f4f4; 
        }

        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin: 50px auto; 
            background: #fff; 
        }

        th, td { 
            border: 1px solid #ddd;  
            padding: 10px; text-align: center; 
        }

        th { 
            background: #000000ff; 
            color: white; 
        }
</style>

</form>

<script>
    document.getElementById('selecionarTodos').onclick = function() {
        // Usa o estado 'data-checked' para controlar o botão de toggle
        var isChecked = this.getAttribute('data-checked') !== 'true';
        
        var checkboxes = document.getElementsByName('usuarios_a_excluir[]');
        
        for (var checkbox of checkboxes) {
            checkbox.checked = isChecked;
        }

        // Atualiza o estado
        this.setAttribute('data-checked', isChecked);
        // Opcional: Altera o texto do botão
        this.textContent = isChecked ? 'Deselecionar Todos' : 'Selecionar Todos';
    }
</script>

</body>
</html>