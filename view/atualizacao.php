<?php
// Inicia a sessão — isso é necessário para acessar as variáveis de sessão do usuário logado
session_start();

// Inclui o arquivo de configuração, onde provavelmente estão as credenciais do banco e a conexão ($mysqli)
include('../config/config.php');

// Verifica se o usuário está logado — caso não esteja, redireciona para a página de login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redireciona o usuário
    exit; // Encerra a execução do script
}

// Verifica se o usuário tem nível de acesso 2 (provavelmente um administrador)
if (empty($_SESSION['nivel_acesso']) || intval($_SESSION['nivel_acesso']) !== 2) {
    // Caso o nível de acesso não seja 2, mostra mensagem e bloqueia o acesso
    echo "<p style='color:red;'>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}


// Verifica se o formulário foi enviado (ou seja, se o campo 'cpf' foi enviado via POST)
if(isset($_POST['cpf'])){
    // Recebe os dados do formulário
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $bairro = $_POST['bairro'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    // Prepara um comando SQL para atualizar os dados do associado com o CPF informado
    $stmt = $mysqli->prepare("
        UPDATE associado 
        SET endereco = ?, bairro = ?, telefone = ?, email = ? 
        WHERE cpf = ?
    ");
    // Substitui os "?" pelos valores enviados no formulário, de forma segura
    $stmt->bind_param("sssss", $endereco, $bairro, $telefone, $email, $cpf);
    $stmt->execute(); // Executa o comando SQL

    // Verifica se alguma linha foi afetada (ou seja, se o CPF existia e foi atualizado)
    if($stmt->affected_rows > 0){
        $mensagem = "Associado atualizado com sucesso.";
    } else {
        // Caso não exista um registro com esse CPF, insere um novo associado
        $stmt_insert = $mysqli->prepare("
            INSERT INTO associado (cpf, endereco, bairro, telefone, email) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt_insert->bind_param("sssss", $cpf, $endereco, $bairro, $telefone, $email);

        // Tenta executar a inserção
        if($stmt_insert->execute()){
            $mensagem = "Associado cadastrado com sucesso.";
        } else {
            $mensagem = "Erro ao cadastrar ou atualizar o associado.";
        }

        // Fecha o statement da inserção
        $stmt_insert->close();
    }

    // Fecha o statement da atualização
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro/Atualização de Associado - AEMPA</title>
  <!-- Importa o arquivo CSS externo -->
  <link rel="stylesheet" href="../assets/style.css">
</head>
<!-- Barra lateral de navegação -->
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
<body>
  <!-- Conteúdo principal -->
  <main class="main-content">
    
    <div class="form-container">
      <h2>Cadastro/Atualização de Associado</h2>

      <!-- Formulário para cadastrar ou atualizar dados de um associado -->
      <form method="POST">
        <input type="text" name="cpf" placeholder="CPF" required>
        <input type="text" name="endereco" placeholder="Endereço" required>
        <input type="text" name="bairro" placeholder="Bairro" required>
        <input type="text" name="telefone" placeholder="Telefone" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <button type="submit">Salvar</button>
      </form>

      <!-- Exibe a mensagem de sucesso ou erro após o envio do formulário -->
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
Resumo do funcionamento

Controle de acesso: só permite usuários logados com nível 2.

Formulário: coleta CPF, endereço, bairro, telefone e e-mail.

Banco de dados:

Se o CPF já existe → atualiza os dados.

Se o CPF não existe → insere um novo associado.

Feedback: exibe mensagem de sucesso ou erro.

Interface: layout com menu lateral e formulário limpo. -->