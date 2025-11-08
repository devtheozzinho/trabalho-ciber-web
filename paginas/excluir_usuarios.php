<?php
// excluir_usuarios.php

include("conexao.php"); // Inclua sua conexão

// 1. Verifica a conexão
if (!isset($mysqli) || $mysqli->connect_error) {
    die("Erro de Conexão: Falha na conexão com o banco de dados.");
}

// 2. Verifica se o formulário foi enviado e se há IDs para excluir
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['usuarios_a_excluir'])) {
    
    $ids_a_excluir = $_POST['usuarios_a_excluir'];
    
    $placeholders = implode(',', array_fill(0, count($ids_a_excluir), '?')); 
    
    $sql_delete = "DELETE FROM usuario WHERE id IN ($placeholders)";
     
    $stmt = $mysqli->prepare($sql_delete);
    
    $tipos = str_repeat('i', count($ids_a_excluir));
    
    $bind_params = array_merge([$tipos], $ids_a_excluir);
    call_user_func_array([$stmt, 'bind_param'], $bind_params);
    
    if ($stmt->execute()) {
        $linhas_afetadas = $stmt->affected_rows;
        // Redireciona de volta para a lista, mostrando uma mensagem de sucesso
        header("Location: usuarios.php?msg=" . urlencode("$linhas_afetadas usuário(s) excluído(s) com sucesso."));
        exit;
    } else {
        echo "Erro ao excluir: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    // Redireciona se nada foi selecionado
    header("Location: usuarios.php?msg=" . urlencode("Nenhum usuário foi selecionado para exclusão."));
    exit;
}

$mysqli->close();
?>