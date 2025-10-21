<?php
// Inicia a sessão para armazenar variáveis do usuário logado
session_start();

// Inclui a configuração do banco de dados (conexão $mysqli)
include('../config/config.php');


// ----------------------------
// 🔹 Verifica se o formulário foi enviado via POST
// ----------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Recebe os dados enviados pelo formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // ----------------------------
    // 🧩 Consulta o usuário pelo e-mail
    // ----------------------------

    // Prepara uma query segura para buscar o usuário pelo e-mail
    // 🔹 O '?' é um placeholder que será substituído pelo valor do e-mail
    $stmt = $mysqli->prepare("SELECT * FROM usuario WHERE email = ?");

    // Liga o valor do e-mail ao placeholder da query
    // "s" indica que o parâmetro é do tipo string
    $stmt->bind_param("s", $email);

    // Executa a query já com o parâmetro seguro
    $stmt->execute();

    // Recupera o resultado da query como se fosse um objeto mysqli_result
    // Permite usar $result->num_rows e $result->fetch_assoc()
    $result = $stmt->get_result();



    // ----------------------------
    // 🔒 Verifica se o usuário existe
    // ----------------------------
    if ($result->num_rows > 0) {
        // Recupera os dados do usuário em um array associativo
        $user = $result->fetch_assoc();

        // ----------------------------
        // 🔑 Verifica a senha
        // ----------------------------
        // Compara a senha digitada com o hash armazenado no banco usando password_verify
        if (password_verify($senha, $user['senha'])) {

            // ----------------------------
            // 📝 Define as variáveis de sessão do usuário
            // ----------------------------
            $_SESSION['usuario_id'] = $user['id'];         // ID do usuário
            $_SESSION['nome'] = $user['nome'];            // Nome completo
            $_SESSION['nivel_acesso'] = $user['nivel_acesso']; // Nível de acesso (ex: 1 ou 2)

            // Redireciona para o painel administrativo após login bem-sucedido
            header("Location: dashboard.php");
            exit;
        } else {
            // Senha incorreta
            echo "Senha incorreta!";
        }
    } else {
        // Usuário não encontrado no banco de dados
        echo "Usuário não encontrado!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AEMPA - Login</title>
  <!-- Importa o CSS do portal -->
  <link rel="stylesheet" href="../assets/style.css"/>
</head>
<body>
  <div class="login-container">
    <h2>PORTAL ADMINISTRATIVO</h2>

    <!-- Formulário de login -->
    <form action="" method="POST">
      <input type="text" placeholder="E-mail" name="email" required />
      <input type="password" placeholder="Senha" name="senha" required />
      <button type="submit">Entrar</button>
    </form>
  </div>
</body>
</html>
<!--
Inicia a sessão para armazenar informações do usuário durante a navegação.

Recebe os dados do formulário (email e senha) via POST.

Consulta o banco de dados para localizar o usuário pelo e-mail.

Verifica se a senha digitada confere com o hash armazenado no banco (password_verify).

Cria variáveis de sessão para controlar o login e acesso do usuário.

Redireciona para o dashboard se o login for válido.

Exibe mensagens de erro caso o e-mail ou a senha estejam incorretos.
-->