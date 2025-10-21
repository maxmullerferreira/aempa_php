<?php
// ----------------------------
// 🔹 Inicia a sessão para acessar variáveis do usuário logado
// ----------------------------
session_start();

// ----------------------------
// 🔹 Inclui arquivo de configuração do banco de dados
// ----------------------------
include('../config/config.php');

// ----------------------------
// 🔹 Verifica se o usuário está logado
// ----------------------------
if (!isset($_SESSION['usuario_id'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
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
// 🔹 Verifica se o formulário foi enviado
// ----------------------------
if(isset($_POST['cpf'])){
    $cpf = $_POST['cpf'];

    // ----------------------------
    // 🔄 Prepara query para reativar o associado (evita SQL Injection)
    // ----------------------------
    $stmt = $mysqli->prepare("UPDATE associado SET ativo = 'S' WHERE cpf = ?");
    $stmt->bind_param("s", $cpf);

    // ----------------------------
    // 🔹 Executa a query e define mensagem de sucesso ou erro
    // ----------------------------
    if($stmt->execute()){
        $mensagem = "Associado reativado com sucesso.";
    } else {
        $mensagem = "Erro ao reativar o associado.";
    }

    // ----------------------------
    // 🔹 Fecha o statement
    // ----------------------------
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Reativar Associado - AEMPA</title>
  <link rel="stylesheet" href="../assets/style.css">
  
  <!-- Script para confirmar ação antes de enviar o formulário -->
  <script>
    function confirmarReativacao(){
      return confirm("Deseja realmente reativar o associado?");
    }
  </script>
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
      <h2>Reativar Associado</h2>

      <!-- Formulário para enviar CPF do associado a ser reativado -->
      <form method="POST" onsubmit="return confirmarReativacao();">
        <input type="text" name="cpf" placeholder="CPF" required>
        <button type="submit">Reativar</button>
      </form>

      <!-- Exibe mensagem de sucesso ou erro após envio do formulário -->
      <?php
      if(isset($mensagem)){
          echo "<p>$mensagem</p>";
      }
      ?>
    </div>
  </main>
</body>
</html>

<!--
Resumo do funcionamento:

Verifica se o usuário está logado e tem nível de acesso 2 (admin).

Recebe o CPF do associado via formulário POST.

Prepara e executa uma query segura para atualizar o campo ativo para 'S' (reativar).

Exibe mensagem de sucesso ou erro.

Usa um alert de confirmação antes de enviar o formulário.

-->