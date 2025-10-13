<?php
if(isset($_POST['email'])){
  include('../config/config.php');

  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

  $mysqli->query("INSERT INTO usuario (nome, email, senha) VALUES('$nome', '$email', '$senha')");
    if($nome == $nome){
      header("Location: login.php");
    }
}else{
  echo "Falha ao cadastrar usuário";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de e-mail - AEMPA</title>
    <link rel="stylesheet" href="../assets/style.css">
    
</head>

<header>
  <div class="dashboard">
    <aside class="sidebar">
      <img src="logo.png" alt="AEMPA Logo" class="logo-small">
      <h2>AEMPA</h2>
      <nav>
        <ul>
          <li><a href="dashboard.php">📊 Dashboard</a></li>
          <li><a href="logout.php">🚪 Logout</a></li>
        </ul>
      </nav>
    </aside>

</header>

<body>

<div class="form-container">
    <h2>Cadastro de E-mail</h2>
    <form action="" method="post">
  <input type="text" name="nome" placeholder="Nome">
  <input type="text" name="email" placeholder="e-mail">
  <input type="text" name="senha" placeholder="Senha">
  <button type="submit">Cadastrar E-mail</button>
</form>

  </div>

</body>
</html>