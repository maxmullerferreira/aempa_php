<?php
session_start();
include('../config/config.php');

// Verifica se o usuário está logado
if(!isset($_SESSION['usuario_id'])){
    header("Location: login.php");
    exit;
}

// Apenas usuários com nível 2 podem acessar
if(!isset($_SESSION['nivel_acesso']) || $_SESSION['nivel_acesso'] != 2){
    echo "<p>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Controle de Acesso - AEMPA</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<header>
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
  </div>
</header>

<main>
  <div class="form-container">
    <h2>Controle de Acesso</h2>
    <form method="POST" action="salvar_acesso.php">
      <input type="text" name="cpf" placeholder="CPF do usuário" required>
      <input type="number" name="nivel_acesso" placeholder="Nível de acesso" required min="1" max="2">
      <button type="submit">Salvar</button>
    </form>
  </div>
</main>
</body>
</html>
