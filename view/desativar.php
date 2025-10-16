<?php
include('../config/config.php');

if(isset($_POST['cpf'])){
    $cpf = $_POST['cpf'];

    // Preparando a query para evitar SQL Injection
    $stmt = $mysqli->prepare("UPDATE associado SET ativo = 'N' WHERE cpf = ?");
    $stmt->bind_param("s", $cpf);

    if($stmt->execute()){
        $mensagem = "Associado desativado com sucesso.";
    } else {
        $mensagem = "Erro ao desativar o associado.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Desativar Associado - AEMPA</title>
  <link rel="stylesheet" href="../assets/style.css">
  <script>
    function confirmarDesativacao(){
      return confirm("Deseja realmente desativar o associado?");
    }
  </script>
</head>


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


<body>
  <div class="form-container">
    <h2>Desativar Associado</h2>
    <form method="POST" onsubmit="return confirmarDesativacao();">
      <input type="text" name="cpf" placeholder="CPF" required>
      <button type="submit" class="danger">Desativar</button>
    </form>

    <?php
    if(isset($mensagem)){
        echo "<p>$mensagem</p>";
    }
    ?>
  </div>
</body>
</html>
