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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = $_POST['valor'];
    $especificacao = $_POST['especificacao'];
    $dia = $_POST['dia'];

    // Pega o e-mail do usuário logado
    $usuario_email = $_SESSION['usuario_email'];

    $stmt = $mysqli->prepare("INSERT INTO saida (valor, especificacao, usuario_email, dia) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("dsss", $valor, $especificacao, $usuario_email, $dia);
    $stmt->execute();
    $stmt->close();

    header("Location: saida.php");
    exit;
}

if(isset($_POST['especificacao'])){
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
          <li><a href="lista_associados.php">📋 Associados</a></li>
          <li><a href="entrada.php">💰 Entradas</a></li>
          <li><a href="saida.php">💸 Saídas</a></li>
          <li><a href="saldo.php">💼 Saldo</a></li>
          <li><a href="logout.php">🚪 Logout</a></li>
        </ul>
      </nav>
    </aside>


<body>
  <main class="main-content">
    <div class="form-container">
      <h2>Saída</h2>
      <form method="POST" action="saida.php">
        <input type="number" step="0.01" name="valor" placeholder="Valor" required>
        <input type="text" name="especificacao" placeholder="Especificação" required>
        <input type="date" name="dia" placeholder="Data" required>
        <button type="submit">Lançar</button>
      </form>
    </div>
  </main>
</body>
</html>