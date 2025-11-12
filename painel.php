<?php

session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.html');
    exit();
}

$nome_usuario = $_SESSION['nome_usuario'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAINEL</title>
    <link rel="stylesheet" href="css/estilo_painel.css">
</head>
<body>
    <header class="cabecalho">
        <h1> SOE - Sistema Organizador de Estoque</h1>
        <div class="info_usuario">
            <span>Olá, <?php echo $nome_usuario; ?>!</span>
            <a href="logout.php">Sair</a>
        </div>
    </header>

    <div class="container">

        <nav class="menu_lateral">
            <ul>
                <li><a href="painel.php?pagina=home">Início</a></li>
                <li><a href="painel.php?pagina=produtos">Produtos</a></li>
                <li><a href="painel.php?pagina=fornecedores">Fornecedores</a></li>
                <li><a href="painel.php?pagina=armazens">Armazéns</a></li>
                <li><a href="painel.php?pagina=usuarios">Usuários</a></li>
                <li><a href="painel.php?pagina=admin_add_user">Requests</a></li>
            </ul>
        </nav>

        <main class="conteudo">
            
            <?php
                $pagina = $_GET['pagina'] ?? 'home';
                $paginas_permitidas = [
                    'home',
                    'produtos',
                    'fornecedores',
                    'armazens',
                    'usuarios',
                    'admin_add_user',
                    'adicionar_armazem'
                ];


                
                if (in_array($pagina, $paginas_permitidas)) {
                    $caminho = "paginas/{$pagina}.php";

                    if (file_exists($caminho)) {
                        include $caminho;
                    } else {
                        echo "<h1>Página não encontrada</h1>";
                    }
                } else {
                    echo "<h1>Página não encontrada (Acesso negado).</h1>";
                }
            ?> 
        </main>

        
    </div>

</body>
</html>