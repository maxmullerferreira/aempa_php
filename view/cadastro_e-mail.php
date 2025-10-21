<?php
// Verifica se o formulário foi enviado usando o método POST.
// Isso garante que o código abaixo só execute quando o usuário enviar o formulário.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Inclui o arquivo de configuração do banco de dados ($mysqli)
  include('../config/config.php');

  // Captura e remove espaços extras no início e fim das variáveis recebidas via POST
  $nome = trim($_POST['nome']);
  $email = trim($_POST['email']);
  $senha = trim($_POST['senha']);

  // Verifica se todos os campos foram preenchidos
  if (!empty($nome) && !empty($email) && !empty($senha)) {

    // Criptografa a senha antes de salvar no banco de dados
    // 'PASSWORD_DEFAULT' garante o uso do algoritmo mais seguro disponível (atualmente bcrypt)
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Prepara uma consulta SQL para verificar se o e-mail já está cadastrado
    $verificar = $mysqli->prepare("SELECT id FROM usuario WHERE email = ?");
    $verificar->bind_param("s", $email); // Substitui o "?" pelo valor da variável $email
    $verificar->execute(); // Executa a consulta
    $verificar->store_result(); // Armazena o resultado na memória

    // Se o e-mail já existir no banco, mostra um alerta e redireciona de volta para o cadastro
    if ($verificar->num_rows > 0) {
      echo "<script>alert('E-mail já cadastrado!'); window.location='cadastro.php';</script>";
    } else {
      // Caso o e-mail não exista, prepara uma nova consulta para inserir o novo usuário
      $stmt = $mysqli->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $nome, $email, $senha_hash); // "sss" significa três strings

      // Executa o comando SQL e verifica se deu certo
      if ($stmt->execute()) {
        // Se o cadastro for bem-sucedido, redireciona o usuário para a página de login
        header("Location: login.php");
        exit;
      } else {
        // Caso ocorra um erro, exibe a mensagem de erro do MySQL
        echo "Erro ao cadastrar: " . $stmt->error;
      }
    }

  } else {
    // Caso algum campo esteja vazio, exibe uma mensagem simples
    echo "Por favor, preencha todos os campos.";
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de E-mail - AEMPA</title>
  <!-- Importa o arquivo CSS externo -->
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
  <!-- Estrutura principal da página -->
  <div class="dashboard">
    <!-- Barra lateral (menu de navegação lateral) -->
    <aside class="sidebar">
      <img src="logo.png" alt="AEMPA Logo" class="logo-small">
      <h2>AEMPA</h2>
      <nav>
        <ul>
          <!-- Links do menu lateral -->
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
  <!-- Área principal da página -->
  <main class="main-content">
    <div class="form-container">
      <h2>Cadastro de Usuário</h2>

      <!-- Formulário para cadastrar novos usuários -->
      <form action="" method="post">
        <input type="text" name="nome" placeholder="Nome completo" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Cadastrar</button>
      </form>
    </div>
  </main>
</body>
</html>

<!--
Resumo do funcionamento

O usuário preenche nome, e-mail e senha.

O PHP:

Verifica se o formulário foi enviado via POST.

Garante que os campos não estejam vazios.

Criptografa a senha.

Checa se o e-mail já existe.

Se não existir, insere o novo usuário.

Após o cadastro, o usuário é redirecionado para o login.
-->
