<?php
if (empty($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    echo "<h1>Acesso Negado</h1>";
    exit(); 
}

include('conexao.php');

$sql = "SELECT * FROM req_user";
$resultado = $mysqli ->query($sql);

?>

<h2>Aprovação de Requisições para Novos Usuários</h2>

