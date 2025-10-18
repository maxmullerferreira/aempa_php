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
    $email = trim($_POST['email']); // mudou aqui
    $nivel_acesso = intval($_POST['nivel_acesso']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red;'>E-mail inválido!</p>";
        exit;
    }

    if ($nivel_acesso < 1 || $nivel_acesso > 2) {
        echo "<p style='color:red;'>Nível de acesso inválido! Use 1 ou 2.</p>";
        exit;
    }

    $stmt = $mysqli->prepare("UPDATE usuario SET nivel_acesso = ? WHERE email = ?");
    $stmt->bind_param("is", $nivel_acesso, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<p style='color:green;'>Nível de acesso alterado com sucesso para $email!</p>";
    } else {
        echo "<p style='color:red;'>Erro: usuário não encontrado ou nível já estava igual.</p>";
    }

    $stmt->close();
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

<main class="main-content">
  <div class="form-container">
    <h2>Controle de Acesso</h2>
    <form method="POST" action="salvar_acesso.php">
      <input type="text" name="email" placeholder="E-mail do usuário" required>
      <input type="number" name="nivel_acesso" placeholder="Nível de acesso" required min="1" max="2">
      <button type="submit">Salvar</button>
    </form>
  </div>
</main>
</body>
</html>
