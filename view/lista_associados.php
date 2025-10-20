<?php
session_start();
include('../config/config.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Apenas usuários com nível 2 podem acessar
if (empty($_SESSION['nivel_acesso']) || intval($_SESSION['nivel_acesso']) <= 0) {
    echo "<p style='color:red;'>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}



// Consulta todos os associados em ordem alfabética pelo nome
$query = "SELECT cpf, nome_completo, data_nascimento, endereco, bairro, telefone, email, data_criacao, ativo 
          FROM associado 
          ORDER BY nome_completo ASC";

$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Associados - AEMPA</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <aside class="sidebar">
        <img src="logo.png" alt="AEMPA Logo" class="logo-small">
        <div class="logo-area">        
            <h2>AEMPA</h2>
        </div>
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

    <main class="main-content">

        <section class="table-container">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>CPF</th>
                        <th>Nome Completo</th>
                        <th>Data de Nascimento</th>
                        <th>Endereço</th>
                        <th>Bairro</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                        <th>Data de Criação</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['cpf']; ?></td>
                                <td><?php echo $row['nome_completo']; ?></td>
                                <td><?php echo $row['data_nascimento']; ?></td>
                                <td><?php echo $row['endereco']; ?></td>
                                <td><?php echo $row['bairro']; ?></td>
                                <td><?php echo $row['telefone']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['data_criacao']; ?></td>
                                <td class="<?php echo $row['ativo'] == 'S' ? 'ativo-sim' : 'ativo-nao'; ?>">
                                    <?php echo $row['ativo'] == 'S' ? 'Ativo' : 'Inativo'; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="9">Nenhum associado encontrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
