
<?php
include('../config/config.php');

// Consulta todas as entradas
$sql_entradas = "SELECT valor, nome_completo AS descricao, dia, 'Entrada' AS tipo FROM entrada";

// Consulta todas as saídas
$sql_saidas = "SELECT valor, especificacao AS descricao, dia, 'Saída' AS tipo FROM saida";

// Combina as duas tabelas
$sql_movimentacoes = "($sql_entradas) UNION ALL ($sql_saidas) ORDER BY dia DESC";
$result = $mysqli->query($sql_movimentacoes);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Relatório Financeiro - AEMPA</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
  <aside class="sidebar">
    <img src="logo.png" alt="AEMPA Logo" class="logo-small">
    <h2>AEMPA</h2>
    <nav>
      <ul>
        <li><a href="dashboard.php">📊 Dashboard</a></li>
        <li><a href="entrada.php">💰 Entradas</a></li>
        <li><a href="saida.php">💸 Saídas</a></li>
        <li><a href="saldo.php">💼 Saldo</a></li>
        <li><a href="logout.php">🚪 Logout</a></li>
      </ul>
    </nav>
  </aside>


  <div class="saldo-container">
    <div class="saldo-card">
      <h2>📋 Relatório Financeiro</h2>

      <table class="styled-table" >
        <thead>
          <tr>
            <th>Data</th>
            <th>Tipo</th>
            <th>Descrição</th>
            <th>Valor (R$)</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?php echo date('d/m/Y', strtotime($row['dia'])); ?></td>
                <td style="font-weight:bold; color: <?php echo ($row['tipo'] == 'Entrada') ? '#28a745' : '#dc3545'; ?>">
                  <?php echo $row['tipo']; ?>
                </td>
                <td><?php echo htmlspecialchars($row['descricao']); ?></td>
                <td style="font-weight:bold;">
                  <?php
                    $valor_formatado = number_format($row['valor'], 2, ',', '.');
                    echo ($row['tipo'] == 'Saída') ? "- R$ $valor_formatado" : "+ R$ $valor_formatado";
                  ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="4">Nenhuma movimentação encontrada.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  </main>
</body>
</html>
