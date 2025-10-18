<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  include('../config/config.php');

  $nome = trim($_POST['nome']);
  $email = trim($_POST['email']);
  $senha = trim($_POST['senha']);

  // Verifica se os campos foram preenchidos
  if (!empty($nome) && !empty($email) && !empty($senha)) {

    // Criptografa a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Verifica se o e-mail já está cadastrado
    $verificar = $mysqli->prepare("SELECT id FROM usuario WHERE email = ?");
    $verificar->bind_param("s", $email);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows > 0) {
      echo "<script>alert('E-mail já cadastrado!'); window.location='cadastro.php';</script>";
    } else {
      // Inserir novo usuário
      $stmt = $mysqli->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $nome, $email, $senha_hash);
      
      if ($stmt->execute()) {
        header("Location: login.php");
        exit;
      } else {
        echo "Erro ao cadastrar: " . $stmt->error;
      }
    }

  } else {
    echo "Por favor, preencha todos os campos.";
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de E-mail - AEMPA</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
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
  </div>
<main class="main-content">
  
    <div class="form-container">
      <h2>Cadastro de Usuário</h2>
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
