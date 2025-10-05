<?php
if(isset($_POST['cpf'])){
  include('../config/config.php');

  $cpf = $_POST['cpf'];
  $nome_completo = $_POST['nome_completo'];
  $data_nascimento = $_POST['data_nascimento'] ;
  $endereco = $_POST['endereco'];
  $bairro = $_POST['bairro'];
  $telefone = $_POST['telefone'];
  $email = $_POST['email'];

  $mysqli->query("INSERT INTO associado (cpf, nome_completo, data_nascimento, endereco, bairro, telefone, email) VALUES('$cpf', '$nome_completo', '$data_nascimento', '$endereco', '$bairro', '$telefone', '$email')");
    if($cpf == $cpf){
      header("Location: cadastro_associado.php");
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Associados - AEMPA</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>

<header>
  <div class="dashboard">
    <aside class="sidebar">
      <img src="logo.png" alt="AEMPA Logo" class="logo-small">
      <h2>AEMPA</h2>
      <nav>
        <ul>
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="logout.php">logout</a></li>
        </ul>
      </nav>
    </aside>

</header>

<body>
  
  <div class="form-container">
    <h2>Cadastro de Associados</h2>
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
</body>
</html>