<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel Administrativo - AEMPA</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header>
  <div class="logout"><a href="logout.php">Logout</a></div>
</header>
<div class="dashboard">
    <aside class="sidebar">
      <img src="logo.png" alt="AEMPA Logo" class="logo-small">
      <h2>Portal Administrativo</h2>
      <nav>
        <ul>
          <li><a href="lista_associados.php">📋 Associados</a></li>
          <li><a href="entrada.php">💰 Financeiro</a></li>
          <li><a href="saldo.php">💳 Saldo disponível</a></li>
          <li><a href="logout.php">🚪 Logout</a></li>
        </ul>
      </nav>
    </aside>

    <main class="main-content">
      <section>
        <h3>Menu de Acesso</h3>
        <ul class="submenu">
          <li><a href="cadastro_associado.php">Cadastro de Associados</a></li>
          <li><a href="reativacao.php">Reativação de Associados</a></li>
          <li><a href="atualizacao.php">Atualização de Associado</a></li>
          <li><a href="desativar.php">Desativar Associado</a></li>
          <li><a href="relatorios.php">Relatório Financeiro</a></li>
          <li><a href="controle_acesso.php">Controle de Acesso</a></li>
          <li><a href="entrada.php">Entrada</a></li>
          <li><a href="saida.php">Saída</a></li>
        </ul>
      </section>
    </main>
</div>
</body>
</html>
