<?php
if(isset($_POST['email'])) {
  include('../config/config.php');

  $email = $_POST['email'];
  $senha = $_POST['senha'];

  $sql_code = "SELECT * FROM usuario WHERE email = '$email' LIMIT 1";
  $sql_exec = $mysqli->query($sql_code) or die($mysqli->error);

  if($sql_exec->num_rows > 0){
    $usuario = $sql_exec->fetch_assoc();

    if(password_verify($senha, $usuario['senha'])){
      header("Location: nivel.php");
      exit;
    }
  }

  echo "Falha ao logar! E-mail ou senha incorretos";
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