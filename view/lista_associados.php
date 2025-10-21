<?php
// Inicia a sessão — necessário para verificar se o usuário está logado
session_start();

// Inclui o arquivo de configuração que contém a conexão com o banco de dados ($mysqli)
include('../config/config.php');


// ----------------------------
// 🔒 Verificação de login
// ----------------------------

// Verifica se o usuário está logado através da variável de sessão 'usuario_id'.
// Caso não esteja, o sistema redireciona para a tela de login e encerra a execução.
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}


// ----------------------------
// 🧩 Controle de acesso por nível
// ----------------------------

// Apenas usuários com nível de acesso 2 (administradores) podem acessar esta página.
// Caso contrário, o sistema exibe uma mensagem e encerra a execução.
if (empty($_SESSION['nivel_acesso']) || intval($_SESSION['nivel_acesso']) <= 0) {
    echo "<p style='color:red;'>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}


// ----------------------------
// 📊 Consulta dos associados
// ----------------------------

// Cria uma query SQL para buscar todos os associados cadastrados no sistema,
// ordenados alfabeticamente pelo nome completo.
$query = "SELECT 
            cpf, 
            nome_completo, 
            data_nascimento, 
            endereco, 
            bairro, 
            telefone, 
            email, 
            data_criacao, 
            ativo 
          FROM associado 
          ORDER BY nome_completo ASC";

// Executa a consulta no banco de dados MySQL usando o objeto $mysqli
$result = $mysqli->query($query);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Associados - AEMPA</title>
    <!-- Importa o arquivo de estilo CSS para aplicar o design -->
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <!-- ============================
         🧭 BARRA LATERAL (SIDEBAR)
         ============================ -->
    <aside class="sidebar">
        <!-- Logo da AEMPA -->
        <img src="logo.png" alt="AEMPA Logo" class="logo-small">

        <!-- Nome da instituição -->
        <div class="logo-area">        
            <h2>AEMPA</h2>
        </div>

        <!-- Menu de navegação lateral -->
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
                    <!-- Verifica se há registros no resultado da consulta -->
                    <?php if($result->num_rows > 0): ?>

                        <!-- Loop para exibir cada associado em uma linha da tabela -->
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <!-- Exibe os dados de cada campo -->
                                <td><?php echo $row['cpf']; ?></td>
                                <td><?php echo $row['nome_completo']; ?></td>
                                <td><?php echo $row['data_nascimento']; ?></td>
                                <td><?php echo $row['endereco']; ?></td>
                                <td><?php echo $row['bairro']; ?></td>
                                <td><?php echo $row['telefone']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['data_criacao']; ?></td>

                                <!-- Define a classe CSS conforme o status do associado -->
                                <td class="<?php echo $row['ativo'] == 'S' ? 'ativo-sim' : 'ativo-nao'; ?>">
                                    <!-- Mostra o texto de status -->
                                    <?php echo $row['ativo'] == 'S' ? 'Ativo' : 'Inativo'; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <!-- Caso não haja associados cadastrados -->
                    <?php else: ?>
                        <tr><td colspan="9">Nenhum associado encontrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
<!--
Sugestões de melhoria:

✅ Adicionar um campo de busca por nome ou CPF usando LIKE no SQL.

✅ Exibir um botão para editar ou reativar associados inativos.

✅ Implementar paginação (caso a lista cresça muito).

✅ Usar prepared statements na consulta para mais segurança (embora não haja entrada do usuário direta aqui).
-->