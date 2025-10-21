<?php
// ----------------------------
// 🔹 Inicia a sessão para verificar login do usuário
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
    header("Location: login.php"); // Redireciona para login se não estiver logado
    exit;
}

// ----------------------------
// 🔒 Apenas usuários com nível 2 podem acessar esta página
// ----------------------------
if (empty($_SESSION['nivel_acesso']) || intval($_SESSION['nivel_acesso']) !== 2) {
    echo "<p style='color:red;'>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}

// ----------------------------
// 🔹 Se o formulário foi enviado via POST
// ----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = $_POST['valor'];                     // Valor da saída
    $especificacao = $_POST['especificacao'];     // Descrição da saída
    $dia = $_POST['dia'];                         // Data da saída

    // 🔹 Pega o e-mail do usuário logado (quem fez o lançamento)
    $usuario_email = $_SESSION['usuario_email'];

    // ----------------------------
    // 🛡️ Inserção segura usando Prepared Statement para evitar SQL Injection
    // ----------------------------
    $stmt = $mysqli->prepare("INSERT INTO saida (valor, especificacao, usuario_email, dia) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("dsss", $valor, $especificacao, $usuario_email, $dia);
    $stmt->execute();
    $stmt->close();

    // Redireciona para a página de saída após inserir
    header("Location: saida.php");
    exit;
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

<body>
  <main class="main-content">
    <!-- Formulário para lançar nova saída financeira -->
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

<!--
Resumo do funcionamento:

Autenticação e Autorização: verifica se o usuário está logado e tem nível 2.

Inserção de Saída: permite cadastrar uma saída financeira com valor, descrição e data.

Registro do usuário: vincula o e-mail do usuário logado à saída lançada.

Redirecionamento: após inserir, retorna para a própria página de saída.

Aviso de segurança: o bloco que faz inserção direta via $mysqli->query é redundante e não seguro, podendo ser removido. 
-->