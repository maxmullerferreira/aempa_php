<?php
if(isset($_POST['valor'])){
  include('../config/config.php');

  $valor = $_POST['valor'];
  $especificacao = $_POST['especificacao'];
  $dia = $_POST['dia'];

  $mysqli->query("INSERT INTO saida (valor, especificacao, dia) VALUES('$valor', '$especificacao', '$dia')");

  if ($mysqli->affected_rows > 0) {
    header("Location: saida.php");
    exit();
  } else {
    echo "Erro ao lançar no banco de dados.";
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Saída Financeira - AEMPA</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>


  <div class="dashboard">
    <aside class="sidebar">
      <img src="logo.png" alt="AEMPA Logo" class="logo-small">
      <h2>AEMPA</h2>
      <nav>
        <ul>
          <li><a href="dashboard.php">📊 Dashboard</a></li>
          <li><a href="logout.php">🚪 Logout</a></li>
        </ul>
      </nav>
    </aside>


<body>
  <div class="form-container">
    <h2>Saída</h2>
    <form>
      <input type="number" name="valor" placeholder="Valor" required>
      <input type="text" name="especificacao" placeholder="Especificação" required>
      <input type="date" name="dia" placeholder="Data" required>
      <button type="submit">Lançar</button>
    </form>
  </div>
</body>
</html>