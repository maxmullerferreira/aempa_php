<?php
// Inicia a sessão — isso é necessário para utilizar variáveis de sessão,
// que guardam informações do usuário logado.
session_start();

// Inclui o arquivo de configuração que contém a conexão com o banco de dados ($mysqli)
include('../config/config.php');

// Verifica se existe um usuário logado.
// Se não existir a variável de sessão 'usuario_id', o usuário é redirecionado para o login.
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redireciona para a página de login
    exit; // Interrompe a execução do script
}

// Verifica o nível de acesso do usuário.
// Somente usuários com nível 2 podem acessar esta página.
if (empty($_SESSION['nivel_acesso']) || intval($_SESSION['nivel_acesso']) !== 2) {
    // Caso o usuário não tenha permissão, exibe mensagem de erro e encerra o script.
    echo "<p style='color:red;'>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}


// Verifica se o formulário foi enviado (ou seja, se o campo 'cpf' foi preenchido no POST)
if(isset($_POST['cpf'])){
  
  // Inclui novamente a configuração do banco de dados (pode ser omitido se já incluído antes)
  include('../config/config.php');

  // Captura os dados enviados pelo formulário via método POST
  $cpf = $_POST['cpf'];
  $nome_completo = $_POST['nome_completo'];
  $data_nascimento = $_POST['data_nascimento'];
  $endereco = $_POST['endereco'];
  $bairro = $_POST['bairro'];
  $telefone = $_POST['telefone'];
  $email = $_POST['email'];

  // Executa o comando SQL para inserir os dados do novo associado no banco de dados
  // ⚠️ Observação: aqui os valores estão sendo inseridos diretamente, o que é funcional
  // mas pode ser vulnerável a SQL Injection. O ideal é usar prepared statements (mysqli->prepare).
  $mysqli->query("
    INSERT INTO associado (cpf, nome_completo, data_nascimento, endereco, bairro, telefone, email)
    VALUES('$cpf', '$nome_completo', '$data_nascimento', '$endereco', '$bairro', '$telefone', '$email')
  ");

  // Verifica se o CPF foi informado e redireciona o usuário após o cadastro
  // (A condição "$cpf == $cpf" é sempre verdadeira, então sempre redireciona)
  if($cpf == $cpf){
    header("Location: cadastro_associado.php"); // Recarrega a página após inserir
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Associados - AEMPA</title>
  <!-- Importa o arquivo de estilos CSS -->
  <link rel="stylesheet" href="../assets/style.css">
</head>
<!-- Estrutura principal do layout -->
<div class="dashboard">
  
  <!-- Barra lateral (menu de navegação) -->
  <aside class="sidebar">
    <!-- Logo e título da associação -->
    <img src="logo.png" alt="AEMPA Logo" class="logo-small">
    <h2>AEMPA</h2>

    <!-- Menu de navegação lateral -->
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
  <!-- Área principal da página -->
  <main class="main-content">
    <div class="form-container">
      <h2>Cadastro de Associados</h2>

      <!-- Formulário para envio dos dados de um novo associado -->
      <form action="" method="post">
        <input type="text" name="cpf" placeholder="CPF" required>
        <input type="text" name="nome_completo" placeholder="Nome completo" required>
        <input type="date" name="data_nascimento" placeholder="Data de nascimento" required>
        <input type="text" name="endereco" placeholder="Endereço" required>
        <input type="text" name="bairro" placeholder="Bairro" required>
        <input type="text" name="telefone" placeholder="Telefone" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <button type="submit">Lançar</button>
      </form>
    </div>
  </main>
</body>
</html>


<!--
Resumo explicativo

Controle de Sessão:	Garante que apenas usuários logados e com permissão (nível 2) possam acessar.

Formulário HTML:	Coleta os dados de um novo associado.

Processamento PHP:	Insere os dados no banco de dados MySQL via objeto $mysqli.

Redirecionamento:	Após o cadastro, recarrega a página de cadastro (cadastro_associado.php).

Menu Lateral:	Permite navegação para outras páginas do sistema AEMPA.
-->