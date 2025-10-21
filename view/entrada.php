<?php
// Inicia a sessão para acessar as variáveis de sessão do usuário
session_start();

// Inclui o arquivo de configuração com a conexão ao banco de dados
include('../config/config.php');

// Verifica se o usuário está logado
// Caso não esteja, redireciona para a página de login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Verifica se o usuário possui nível de acesso 2 (por exemplo, administrador)
// Caso contrário, exibe mensagem de acesso negado e encerra a execução
if (empty($_SESSION['nivel_acesso']) || intval($_SESSION['nivel_acesso']) !== 2) {
    echo "<p style='color:red;'>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}

// Verifica se o formulário foi enviado via método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados enviados pelo formulário
    $valor = $_POST['valor'];
    $nome_completo = $_POST['nome_completo'];
    $dia = $_POST['dia'];

    // Captura o e-mail do usuário logado armazenado na sessão
    $usuario_email = $_SESSION['usuario_email'];

    // Prepara a query SQL para evitar injeção de SQL (uso de prepared statement)
    $stmt = $mysqli->prepare("INSERT INTO entrada (valor, nome_completo, usuario_email, dia) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("dsss", $valor, $nome_completo, $usuario_email, $dia); // "dsss" = double, string, string, string
    $stmt->execute();  // Executa o comando SQL
    $stmt->close();    // Fecha o statement

    // Após inserir, redireciona o usuário de volta para a página de entradas
    header("Location: entrada.php");
    exit;
}

/* 
  OBS: O bloco abaixo repete parte da lógica acima, porém sem uso de prepared statements.
  Isso deixa o código redundante e menos seguro.
  Recomenda-se manter apenas o bloco anterior.
*/

if (isset($_POST['nome_completo'])) {
  include('../config/config.php');

  $valor = $_POST['valor'];
  $nome_completo = $_POST['nome_completo'];
  $dia = $_POST['dia'];

  // Insere os dados diretamente no banco (não é seguro — vulnerável a SQL Injection)
  $mysqli->query("INSERT INTO entrada (valor, nome_completo, dia) VALUES('$valor', '$nome_completo', '$dia')");

  // Verifica se houve sucesso na operação
  if ($mysqli->affected_rows > 0) {
    header("Location: entrada.php");
    exit();
  } else {
    echo "Erro ao lançar no banco de dados.";
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Entrada Financeira - AEMPA</title>
  <!-- Importa o arquivo CSS externo -->
  <link rel="stylesheet" href="../assets/style.css">
</head>

<!-- Estrutura principal do dashboard -->
<div class="dashboard">
  <aside class="sidebar">
    <!-- Logo e título lateral -->
    <img src="logo.png" alt="AEMPA Logo" class="logo-small">
    <h2>AEMPA</h2>

    <!-- Menu de navegação -->
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
      <h2>Entrada</h2>
      <!-- Formulário de lançamento de entrada financeira -->
      <form method="POST" action="">
        <input type="number" step="0.01" name="valor" placeholder="Valor" required>
        <input type="text" name="nome_completo" placeholder="Nome completo" required>
        <input type="date" name="dia" required>
        <button type="submit">Lançar</button>
      </form>
    </div>
  </main>
</body>
</html>

<!-- 
Resumo geral:

✅ Controle de sessão: garante que apenas usuários logados e com nível 2 acessem a página.

✅ Segurança: há uma parte com prepared statement (boa prática), mas outra duplicada sem proteção.

⚠️ Melhoria sugerida: remover o segundo bloco if(isset($_POST['nome_completo'])), pois é redundante e menos seguro.

✅ Interface limpa: contém menu lateral fixo, formulário simples e integrado ao estilo CSS externo.
-->