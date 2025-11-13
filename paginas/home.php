<?php
include("conexao.php");
?>
<head>
    <meta charset="UTF-8">
    <title>Dashboard Principal</title>
    <link rel="stylesheet" href="../css/home.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Dashboard Principal</h1>
    <p>Visão geral do estoque.</p>

    <div class="tabela">
        <?php
        $sql_categoria = "SELECT categoria, COUNT(*) AS total FROM produto GROUP BY categoria";
        $result_categoria = $mysqli->query($sql_categoria);

        if (!$result_categoria) {
            die("Erro na consulta SQL (categoria): " . $mysqli->error);
        }

        $categorias = [];
        $totais_categoria = [];

        while ($row = $result_categoria->fetch_assoc()) {
            $categorias[] = $row['categoria'];
            $totais_categoria[] = $row['total'];
        }

        $sql_tipo = "SELECT tipo, COUNT(*) AS total FROM produto GROUP BY tipo";
        $result_tipo = $mysqli->query($sql_tipo);

        if (!$result_tipo) {
            die("Erro na consulta SQL (tipo): " . $mysqli->error);
        }

        $tipos = [];
        $totais_tipo = [];

        while ($row = $result_tipo->fetch_assoc()) {
            $tipos[] = $row['tipo'];
            $totais_tipo[] = $row['total'];
        }

        $result_categoria->free();
        $result_tipo->free();
        $mysqli->close();
        ?>
        <canvas id="graficoCategoria"></canvas>
        <script>
        const ctxCategoria = document.getElementById('graficoCategoria');
        new Chart(ctxCategoria, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($categorias); ?>,
                datasets: [{
                    label: 'Produtos por categoria',
                    data: <?php echo json_encode($totais_categoria); ?>,
                    backgroundColor: 'rgba(129, 188, 228, 0.71)',
                    borderColor: 'rgba(27, 33, 37, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } }
            }
        });
        </script>
        <canvas id="graficoTipo"></canvas>
        <script>
        const ctxTipo = document.getElementById('graficoTipo');
        new Chart(ctxTipo, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($tipos); ?>,
                datasets: [{
                    label: 'Produtos por tipo',
                    data: <?php echo json_encode($totais_tipo); ?>,
                    backgroundColor: 'rgba(187, 167, 171, 0.5)',
                    borderColor: 'rgba(23, 23, 19, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } }
            }
        });
        </script>
    </div>
</body>
</html>


        