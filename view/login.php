<?php
session_start();
include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $query = "SELECT * FROM usuario WHERE email = '$email'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($senha, $user['senha'])) {
            // Define as variáveis de sessão
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['nivel_acesso'] = $user['nivel_acesso']; // 🔹 importante!

            header("Location: dashboard.php");
            exit;
        } else {
            echo "Senha incorreta!";
        }
    } else {
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
  <link rel="stylesheet" href="../assets/style.css"/>
</head>
<body>
  <div class="login-container">
    <img src="logo.png" alt="AEMPA Logo" class="logo" />
    <h2>PORTAL ADMINISTRATIVO</h2>
    <form action="" method="POST">
      <input type="text" placeholder="E-mail" name="email" required />
      <input type="password" placeholder="Senha" name="senha" required />
      <button type="submit">Entrar</button>
    </form>
  </div>
</body>
</html>