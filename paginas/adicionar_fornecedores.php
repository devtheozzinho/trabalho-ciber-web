<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário - PHP Puro</title>
     <link rel="stylesheet" href="../css/users.css?v=<?php echo time(); ?>">
    
</head>
    <h2 class="titulo">Cadastro de Novo Fornecedor</h2>
<body>

<div class="container">
  
    <br>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                
        <input type="text" placeholder="Nome">
        <p>
        
        <input type="text" placeholder="Senha">
        <p>

        <input type="text" placeholder="CNPJ">
        <p>

        <input type="text" placeholder="Razão Social">
        <p>

        <input type="text" placeholder="Departamento">
        <p>
 
        <input type="text" placeholder="Telefone">
        <p>
         
        <input type="text" placeholder="Email">
        <p>

        <button type="button" onclick="alert('Sucesso!')">Cadastrar</button>

    </form>
</div>

</body>
</html>