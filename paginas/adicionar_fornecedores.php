<?php

include("../conexao.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $razao_social = $_POST["razao_social"];
    $email = $_POST["email"];
    $cnpj = $_POST["cnpj"];
    $telefone = $_POST["telefone"];
    $departamento = $_POST["departamento"]; // Origem das váriaveis que estao sendo usadas

    
    $sql = "INSERT INTO fornecedor (razao_social, email_fornecedor, cnpj, telefone_fornecedor, departamento) VALUES (?, ?, ?, ?, ?)";    

    $stmt = $mysqli->prepare($sql); 
    if ($stmt === false) {
        die("Erro na preparação da Query: " . $mysqli->error);
    }
    $stmt->bind_param("sssss", $razao_social, $email, $cnpj, $telefone, $departamento);  
    if ($stmt->execute()) {
        header("Location: sucesso.php");
        exit;
    } else {
        echo "Erro ao cadastrar no banco de dados: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
} 
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Fornecedor -</title>
    <link rel="stylesheet" href="../css/adicionar_fornecedores.css?v=<?php echo time(); ?>">

</head>
<h2 class="titulo">Cadastro de Novo Fornecedor</h2>

<body>

    <div class="container">

        <br>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="razao_social" placeholder="Razão Social">
            <p>

                <input type="text" name="cnpj" placeholder="CNPJ">
            <p>

                <input type="text" name="email" placeholder="Email">
            <p>

                <input type="text" name="telefone" placeholder="Telefone">
            <p>

                <input type="text" name="departamento" placeholder="Departamento">
            <p>

                <button type="submit">Cadastrar</button>
        </form>
    </div>

</body>

</html>