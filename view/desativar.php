<?php
session_start();
include('../config/config.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Apenas usuários com nível 2 podem acessar
if (empty($_SESSION['nivel_acesso']) || intval($_SESSION['nivel_acesso']) !== 2) {
    echo "<p style='color:red;'>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}
?>

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
                <li><a href="lista_associados.php">📋 Associados</a></li>
                <li><a href="entrada.php">💰 Entradas</a></li>
                <li><a href="saida.php">💸 Saídas</a></li>
                <li><a href="saldo.php">💼 Saldo</a></li>
                <li><a href="logout.php">🚪 Logout</a></li>
            </ul>
      </nav>
    </aside>


<body>

  <main class="main-content">
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
  </main>
</body>
</html>
