<head>
    <title>Adicionar Produto - SOE</title>
    <link rel="stylesheet" href="../css/produtos.css?v=<?php echo time(); ?>">
</head>
<body>

    <div class="container-adicionar">
        <h2 class="titulo">Adicionar Novo Produto</h2>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            
            <input type="text" name="nome" placeholder="Nome do Produto" required>
            <p></p>

            <input type="text" name="categoria" placeholder="Categoria" required>
            <p></p>

            <input type="number" name="quantidade" placeholder="Quantidade em Estoque" required>
            <p></p>

            <input type="number" name="preco" step="0.01" placeholder="Preço (R$)" required>
            <p></p>

            <input type="text" name="fornecedor" placeholder="Fornecedor">
            <p></p>

            <button type="button" onclick="alert('Produto adicionado com sucesso!')">Cadastrar Produto</button>
        </form>
    </div>

</body>
</html>
