<?php
session_start();
include('../config/config.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Apenas usuários com nível 2 podem acessar
if (empty($_SESSION['nivel_acesso']) || intval($_SESSION['nivel_acesso']) <= 0) {
    echo "<p style='color:red;'>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Administrativo - AEMPA</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="dashboard">
    <aside class="sidebar">
      <img src="logo.png" alt="AEMPA Logo" class="logo-small">
      <h2>Portal Administrativo</h2>
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

    <main class="main-content">
      <section>
        <h2>Menu de Acesso</h2>
        <h3>
          <ul class="submenu">
            <li><a href="cadastro_associado.php">Cadastro de Associados</a></li>
            <li><a href="cadastro_e-mail.php">Cadastro de Usuário</a></li>
            <li><a href="reativacao.php">Reativação de Associados</a></li>
            <li><a href="atualizacao.php">Atualização de Associado</a></li>
            <li><a href="desativar.php">Desativar Associado</a></li>
            <li><a href="relatorios.php">Relatório Financeiro</a></li>
            <li><a href="controle_acesso.php">Controle de Acesso</a></li>
            <li><a href="entrada.php">Entrada</a></li>
            <li><a href="saida.php">Saída</a></li>
          </ul>
        </H3>
      </section>
    </main>
</div>
</body>
</html>
