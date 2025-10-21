<?php
// ----------------------------
// 🔹 Inicia a sessão do usuário
// ----------------------------
session_start();

// ----------------------------
// 🔹 Inclui arquivo de configuração do banco de dados
// ----------------------------
include('../config/config.php');

// ----------------------------
// 🔒 Verifica se o usuário está logado
// ----------------------------
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redireciona para a página de login
    exit;
}

// ----------------------------
// 🔒 Apenas usuários com nível 2 podem acessar
// ----------------------------
if (empty($_SESSION['nivel_acesso']) || intval($_SESSION['nivel_acesso']) <= 0) {
    echo "<p style='color:red;'>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}

// ----------------------------
// 🔍 Consulta o total de entradas no banco de dados
// COALESCE garante que, se não houver entradas, o valor será 0
// ----------------------------
$sql_entradas = "SELECT COALESCE(SUM(valor), 0) AS total_entradas FROM entrada";
$result_entradas = $mysqli->query($sql_entradas);
$row_entradas = $result_entradas ? $result_entradas->fetch_assoc() : ['total_entradas' => 0];
$total_entradas = $row_entradas['total_entradas'];

// ----------------------------
// 🔍 Consulta o total de saídas no banco de dados
// ----------------------------
$sql_saidas = "SELECT COALESCE(SUM(valor), 0) AS total_saidas FROM saida";
$result_saidas = $mysqli->query($sql_saidas);
$row_saidas = $result_saidas ? $result_saidas->fetch_assoc() : ['total_saidas' => 0];
$total_saidas = $row_saidas['total_saidas'];

// ----------------------------
// 💰 Calcula o saldo disponível
// ----------------------------
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
        <li><a href="dashboard.php">📊 Dashboard</a></li>
        <li><a href="lista_associados.php">📋 Associados</a></li>
        <li><a href="entrada.php">💰 Entradas</a></li>
        <li><a href="saida.php">💸 Saídas</a></li>
        <li><a href="saldo.php">💼 Saldo</a></li>
        <li><a href="logout.php">🚪 Logout</a></li>
      </ul>
    </nav>
  </aside>
  
  <!-- CONTEÚDO PRINCIPAL -->
  <section>
      <div class="saldo-container">
        <main class="main-content">
          
          <div class="saldo-card">
            <h2>💰 Saldo Disponível</h2>
            
            <!-- Valor total do saldo com cor condicional -->
            <p class="valor <?= ($saldo_disponivel < 0) ? 'negativo' : 'positivo' ?>">
              R$ <?= number_format($saldo_disponivel, 2, ',', '.') ?>
            </p>
            
            <!-- Informações detalhadas -->
            <div class="saldo-info">
              <p><strong>Total de Entradas:</strong> R$ <?= number_format($total_entradas, 2, ',', '.') ?></p>
              <p><strong>Total de Saídas:</strong> R$ <?= number_format($total_saidas, 2, ',', '.') ?></p>
            </div>
          </div>
        </main>
      </div>
  </section>

</body>
</html>

<!-- Resumo do funcionamento

Autenticação e Autorização: garante que apenas usuários logados com nível 2 possam acessar.

Consulta ao banco: busca o total de entradas e saídas financeiras, tratando caso não haja registros (COALESCE).

Cálculo do saldo: subtrai total de saídas do total de entradas.

Exibição: mostra o saldo disponível em destaque, com cores diferentes se o saldo for negativo ou positivo, além de exibir os totais detalhados.

Design responsivo: menu lateral com links de navegação.
-->