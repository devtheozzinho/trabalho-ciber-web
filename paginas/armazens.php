<?php
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['armazens_a_excluir'])) {
    
    if (!isset($_SESSION['id_usuario'])) {
        die("Você precisa estar logado para fazer uma solicitação.");
    }

    $ids_a_excluir = $_POST['armazens_a_excluir'];
    $id_req = $_SESSION['id_usuario'];
    
    $sql_insert_request = "INSERT INTO requests (id_user_req, tipo_acao, recurso, id_alvo) 
                           VALUES (?, 'delete', 'armazem', ?)";
    $stmt = $mysqli->prepare($sql_insert_request);

    $erros = [];
    $sucessos = 0;

    foreach ($ids_a_excluir as $id_alvo) {
        $stmt->bind_param("ii", $id_req, $id_alvo);
        
        if ($stmt->execute()) {
            $sucessos++;
        } else {
            $erros[] = $stmt->error . " (Verifique se você rodou o ALTER TABLE na coluna 'recurso')";
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

// Puxando os armazéns e ordenando.
$sql_select = "SELECT id_armazem, endereco_armazem, capacidade, responsavel 
               FROM armazem ORDER BY id_armazem ASC";
$result_select = $mysqli->query($sql_select);

if (!$result_select) {
    die("Erro na consulta SQL: " . $mysqli->error);
}
?>

<h1>Gerenciamento de Armazéns</h1>
<p>Informações e operações com armazéns.</p>