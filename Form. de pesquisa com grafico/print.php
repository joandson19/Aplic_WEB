<!DOCTYPE html>
<html>
<head>
  <title>Resultados da Pesquisa</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
  <img src="../images/logo.png" alt="Logo da Empresa" class="logo">
  <h1>Resultados da Pesquisa</h1>
  <?php
  
require('dados.php');

  $conn = mysqli_connect($servername, $username, $password, $dbname);

  // Verifica a conexão com o banco de dados
  if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
  }

  // Consulta os dados do banco de dados
  $sql = "SELECT servico, COUNT(*) AS quantidade FROM clientes GROUP BY servico";

  $result = mysqli_query($conn, $sql);

  // Cria os arrays para os dados do gráfico
  $labels = [];
  $data = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row["servico"];
    $data[] = $row["quantidade"];
  }

  mysqli_close($conn);
  ?>
  <canvas id="grafico" width="4%" height="4%">
  <script>
    var ctx = document.getElementById('grafico').getContext('2d');
    var chart = new Chart(ctx, {
	  type: 'pie',
      data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
          label: 'Porcentagem',
          data: <?php echo json_encode($data); ?>,
          backgroundColor: [
            'rgba(255, 99, 132, 0.8)',
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)'
          ],
          borderWidth: 1
        }]
      },
options: {
  responsive: true,
  maintainAspectRatio: true,
  plugins: {
    legend: {
      position: 'right'
    }
  },
  layout: {
    padding: {
      left: 0,
      right: 0,
      top: 50,
      bottom: 50
    }
  },
  aspectRatio: 1.5
}
    });
	  window.addEventListener('resize', function() {
    chart.resize();
  });
  </script>
</body>
</html>
