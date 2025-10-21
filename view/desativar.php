<?php
// Inicia a sessão para acessar as variáveis de sessão do usuário logado
session_start();

// Inclui o arquivo de configuração do banco de dados (conexão MySQL)
include('../config/config.php');

// 🚫 Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Caso não esteja logado, redireciona para a página de login
    header("Location: login.php");
    exit;
}

// 🔒 Verifica se o usuário possui nível de acesso 2 (administrador)
// Apenas administradores podem acessar esta página
if (empty($_SESSION['nivel_acesso']) || intval($_SESSION['nivel_acesso']) !== 2) {
    echo "<p style='color:red;'>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}



// Verifica se o formulário foi enviado (campo CPF foi preenchido)
if(isset($_POST['cpf'])){
    $cpf = $_POST['cpf']; // Recebe o CPF digitado no formulário

    // 🛡️ Prepara o comando SQL para evitar SQL Injection
    // Atualiza o campo "ativo" para 'N' (inativo) do associado com o CPF informado
    $stmt = $mysqli->prepare("UPDATE associado SET ativo = 'N' WHERE cpf = ?");
    $stmt->bind_param("s", $cpf); // "s" indica que o parâmetro é uma string (CPF)

    // Executa a query e verifica se funcionou
    if($stmt->execute()){
        $mensagem = "Associado desativado com sucesso."; // Mensagem de sucesso
    } else {
        $mensagem = "Erro ao desativar o associado."; // Mensagem de erro
    }

    // Fecha o comando preparado (libera recursos)
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Desativar Associado - AEMPA</title>
  <link rel="stylesheet" href="../assets/style.css"> <!-- Importa o arquivo CSS -->

  <!-- Script JavaScript para confirmar a desativação -->
  <script>
    function confirmarDesativacao(){
      // Exibe uma janela de confirmação antes de enviar o formulário
      return confirm("Deseja realmente desativar o associado?");
    }
  </script>
</head>

<!-- Estrutura principal do painel administrativo -->
<div class="dashboard">
  <!-- Barra lateral com menu de navegação -->
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
      <h2>Desativar Associado</h2>

      <!-- Formulário para desativar o associado -->
      <!-- Chama a função JavaScript confirmarDesativacao() antes de enviar -->
      <form method="POST" onsubmit="return confirmarDesativacao();">
        <input type="text" name="cpf" placeholder="CPF" required> <!-- Campo obrigatório -->
        <button type="submit" class="danger">Desativar</button> <!-- Botão de ação -->
      </form>

      <?php
      // Exibe mensagem de sucesso ou erro após a tentativa de desativação
      if(isset($mensagem)){
          echo "<p>$mensagem</p>";
      }
      ?>
    </div>
  </main>
</body>
</html>
<!--

Explicação geral do funcionamento:

✅ 1. Controle de Acesso (PHP inicial)

Garante que apenas usuários logados e administradores (nível 2) possam acessar.

Caso contrário, o sistema mostra uma mensagem e encerra o script.

✅ 2. Desativação no Banco (PHP principal)

Recebe o CPF via formulário.

Usa prepared statement (segurança contra SQL Injection).

Atualiza o campo ativo do associado para 'N'.

Exibe uma mensagem informando o resultado da operação.

✅ 3. Interface (HTML + CSS)

Mostra um menu lateral com acesso às principais seções da AEMPA.

Exibe um formulário simples para desativar um associado.

Usa JavaScript para confirmar a ação antes de enviar os dados.

✅ 4. Segurança extra implementada:

Sessão protegida.

Verificação de permissão.

SQL seguro (prepared statement).

Confirmação via confirm() para evitar cliques acidentais.
-->