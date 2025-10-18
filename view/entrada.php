<?php
session_start();
include('../config/config.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Apenas usuários com nível 2 podem acessar
if (empty($_SESSION['nivel_acesso']) || intval($_SESSION['nivel_acesso']) !== 2) {
    echo "<p style='color:red;'>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}
?>

<?php
if(isset($_POST['nome_completo'])){
  include('../config/config.php');

  $valor = $_POST['valor'];
  $nome_completo = $_POST['nome_completo'];
  $dia = $_POST['dia'];

  $mysqli->query("INSERT INTO entrada (valor, nome_completo, dia ) VALUES('$valor', '$nome_completo', '$dia')");

  if ($mysqli->affected_rows > 0) {
    header("Location: entrada.php");
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
  <title>Entrada Financeira - AEMPA</title>
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
  <main class="main-content">
    <div class="form-container">
      <h2>Entrada</h2>
      <form method="POST" action="">
        <input type="number" name="valor" placeholder="Valor" required>
        <input type="text" name="nome_completo" placeholder="Nome completo" required>
        <input type="date" name="dia" required>
        <button type="submit">Lançar</button>
      </form>
    </div>
  </main>
</body>
</html>
