<?php
include('../config/config.php');

// Consulta o total de entradas
$sql_entradas = "SELECT COALESCE(SUM(valor), 0) AS total_entradas FROM entrada";
$result_entradas = $mysqli->query($sql_entradas);
$row_entradas = $result_entradas ? $result_entradas->fetch_assoc() : ['total_entradas' => 0];
$total_entradas = $row_entradas['total_entradas'];

// Consulta o total de saídas
$sql_saidas = "SELECT COALESCE(SUM(valor), 0) AS total_saidas FROM saida";
$result_saidas = $mysqli->query($sql_saidas);
$row_saidas = $result_saidas ? $result_saidas->fetch_assoc() : ['total_saidas' => 0];
$total_saidas = $row_saidas['total_saidas'];

// Calcula o saldo
$saldo_disponivel = $total_entradas - $total_saidas;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Saldo Disponível - AEMPA</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
  <!-- MENU LATERAL -->
  <aside class="sidebar">
    <img src="logo.png" alt="AEMPA Logo" class="logo-small">
    <h2>AEMPA</h2>
    <nav>
      <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="lista_associados.php">Associados</a></li>
        <li><a href="entrada.php">Financeiro</a></li>
        <li><a href="saldo.php">Saldo disponível</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </aside>
  
  <main class="main-content">
    <section>
      <!-- CONTEÚDO PRINCIPAL -->
      <div class="saldo-container">
        <div class="saldo-card">
          <h2>💰 Saldo Disponível</h2>
          <p class="valor <?= ($saldo_disponivel < 0) ? 'negativo' : 'positivo' ?>">
            R$ <?= number_format($saldo_disponivel, 2, ',', '.') ?>
          </p>

          <div class="saldo-info">
            <p><strong>Total de Entradas:</strong> R$ <?= number_format($total_entradas, 2, ',', '.') ?></p>
            <p><strong>Total de Saídas:</strong> R$ <?= number_format($total_saidas, 2, ',', '.') ?></p>
          </div>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
