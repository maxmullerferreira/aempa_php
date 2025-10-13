<?php
include('../config/config.php');

if(isset($_POST['cpf'])){
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $bairro = $_POST['bairro'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    // Tenta atualizar o registro primeiro
    $stmt = $mysqli->prepare("
        UPDATE associado 
        SET endereco = ?, bairro = ?, telefone = ?, email = ? 
        WHERE cpf = ?
    ");
    $stmt->bind_param("sssss", $endereco, $bairro, $telefone, $email, $cpf);
    $stmt->execute();

    if($stmt->affected_rows > 0){
        $mensagem = "Associado atualizado com sucesso.";
    } else {
        // Se não encontrou CPF, insere novo
        $stmt_insert = $mysqli->prepare("
            INSERT INTO associado (cpf, endereco, bairro, telefone, email) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt_insert->bind_param("sssss", $cpf, $endereco, $bairro, $telefone, $email);

        if($stmt_insert->execute()){
            $mensagem = "Associado cadastrado com sucesso.";
        } else {
            $mensagem = "Erro ao cadastrar ou atualizar o associado.";
        }

        $stmt_insert->close();
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro/Atualização de Associado - AEMPA</title>
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
    <h2>Cadastro/Atualização de Associado</h2>
    <form method="POST">
      <input type="text" name="cpf" placeholder="CPF" required>
      <input type="text" name="endereco" placeholder="Endereço" required>
      <input type="text" name="bairro" placeholder="Bairro" required>
      <input type="text" name="telefone" placeholder="Telefone" required>
      <input type="email" name="email" placeholder="E-mail" required>
      <button type="submit">Salvar</button>
    </form>

    <?php
    if(isset($mensagem)){
        echo "<p>$mensagem</p>";
    }
    ?>
  </div>
</body>
</html>
