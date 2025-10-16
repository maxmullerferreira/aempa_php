<?php
include('../config/config.php');

if(isset($_POST['cpf'])){
    $cpf = $_POST['cpf'];

    // Atualiza o associado para ativo
    $stmt = $mysqli->prepare("UPDATE associado SET ativo = 'S' WHERE cpf = ?");
    $stmt->bind_param("s", $cpf);

    if($stmt->execute()){
        $mensagem = "Associado reativado com sucesso.";
    } else {
        $mensagem = "Erro ao reativar o associado.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Reativar Associado - AEMPA</title>
  <link rel="stylesheet" href="../assets/style.css">
  <script>
    function confirmarReativacao(){
      return confirm("Deseja realmente reativar o associado?");
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
    <h2>Reativar Associado</h2>
    <form method="POST" onsubmit="return confirmarReativacao();">
      <input type="text" name="cpf" placeholder="CPF" required>
      <button type="submit">Reativar</button>
    </form>

    <?php
    if(isset($mensagem)){
        echo "<p>$mensagem</p>";
    }
    ?>
  </div>
</body>
</html>
