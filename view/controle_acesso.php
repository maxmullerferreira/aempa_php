<?php 
// Inicia a sessão — necessário para acessar as variáveis de sessão criadas no login
session_start();

// Inclui o arquivo de configuração do banco de dados
include('../config/config.php');

// 🔒 Verifica se o usuário está logado
// Se não houver um "usuario_id" na sessão, o usuário é redirecionado para a página de login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// 🔐 Verifica o nível de acesso do usuário
// Somente usuários com nível 2 (administrador) podem acessar esta página
if (empty($_SESSION['nivel_acesso']) || intval($_SESSION['nivel_acesso']) !== 2) {
    echo "<p style='color:red;'>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}

// 📨 Se o formulário foi enviado (método POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Captura o e-mail digitado e remove espaços extras
    $email = trim($_POST['email']); 

    // Captura e converte o nível de acesso para número inteiro
    $nivel_acesso = intval($_POST['nivel_acesso']);

    // 📧 Validação do e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red;'>E-mail inválido!</p>";
        exit;
    }

    // 🔎 Validação do nível de acesso — deve ser 1 (usuário comum) ou 2 (administrador)
    if ($nivel_acesso < 1 || $nivel_acesso > 2) {
        echo "<p style='color:red;'>Nível de acesso inválido! Use 1 ou 2.</p>";
        exit;
    }

    // ⚙️ Atualiza o nível de acesso do usuário no banco de dados
    $stmt = $mysqli->prepare("UPDATE usuario SET nivel_acesso = ? WHERE email = ?");
    $stmt->bind_param("is", $nivel_acesso, $email); // "i" = inteiro, "s" = string
    $stmt->execute();

    // 💬 Exibe mensagem de acordo com o resultado da atualização
    if ($stmt->affected_rows > 0) {
        echo "<p style='color:green;'>Nível de acesso alterado com sucesso para $email!</p>";
    } else {
        echo "<p style='color:red;'>Erro: usuário não encontrado ou nível já estava igual.</p>";
    }

    // Fecha a declaração
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Controle de Acesso - AEMPA</title>
  <!-- Importa o CSS externo -->
  <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
  <!-- Cabeçalho e menu lateral -->
  <header>
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
    </div>
  </header>

  <!-- Conteúdo principal -->
  <main class="main-content">
    <div class="form-container">
      <h2>Controle de Acesso</h2>
      <!-- Formulário para alterar o nível de acesso do usuário -->
      <form method="POST" action="salvar_acesso.php">
        <input type="text" name="email" placeholder="E-mail do usuário" required>
        <input type="number" name="nivel_acesso" placeholder="Nível de acesso" required min="1" max="2">
        <button type="submit">Salvar</button>
      </form>
    </div>
  </main>
</body>
</html>

<!--
Explicação geral

Objetivo do arquivo:
Permitir que apenas administradores (nível 2) atualizem o nível de acesso de outros usuários.

Fluxo completo:

O sistema verifica se há um usuário logado.

Checa se o nível dele é 2 (admin).

Exibe o formulário.

Quando o admin envia o formulário, o sistema:

Valida o e-mail e o nível de acesso.

Atualiza o banco (UPDATE usuario SET nivel_acesso = ? WHERE email = ?).

Mostra mensagem de sucesso ou erro.
-->