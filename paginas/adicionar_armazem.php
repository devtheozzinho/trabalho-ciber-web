<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Erro: Você precisa estar logado para fazer uma requisição.");
}

include("../conexao.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_req = $_SESSION['id_usuario'];

    $dados_formulario = [
        "endereco_armazem" => $_POST["endereco_armazem"],
        "capacidade" => $_POST["capacidade"],
        "responsavel" => $_POST["responsavel"]
    ];
    
    $dados_json = json_encode($dados_formulario);
    
    $sql = "INSERT INTO requests (id_user_req, tipo_acao, recurso, dados) 
            VALUES (?, 'create', 'armazem', ?)";    

    $stmt = $mysqli->prepare($sql); 
    if ($stmt === false) {
        die("Erro na preparação da Query: " . $mysqli->error);
    }
    
    $stmt->bind_param("is", $id_req, $dados_json);  
    
    if ($stmt->execute()) {
        header("Location: sucesso.php");
        exit;
    } else {
        echo "Erro ao criar solicitação: " . $stmt->error . " (Verifique se você rodou o ALTER TABLE na coluna 'recurso')";
    }

    $stmt->close();
    $mysqli->close();
} 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Armazém</title>
    <link rel="stylesheet" href="../css/adicionar_fornecedores.css?v=<?php echo time(); ?>">
</head>
<h2 class="titulo">Cadastro de Novo Armazém</h2>
<body>
    <div class="container">
        <br>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="endereco_armazem" placeholder="Endereço Completo" required>
            <p>
                <input type="number" name="capacidade" placeholder="Capacidade (unidades)" required>
            <p>
                <input type="text" name="responsavel" placeholder="Nome do Responsável" required>
            <p>
                <button type="submit">Solicitar Cadastro</button>
        </form>
    </div>
</body>
</html>