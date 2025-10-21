<?php
// ----------------------------
// 🔹 Inicia a sessão do usuário para verificar login e acesso
// ----------------------------
session_start();

// ----------------------------
// 🔹 Inclui arquivo de configuração do banco de dados
// ----------------------------
include('../config/config.php');

// ----------------------------
// 🔒 Verifica se o usuário está logado
// ----------------------------
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redireciona se não estiver logado
    exit;
}

// ----------------------------
// 🔒 Verifica se o usuário tem nível de acesso permitido (>0)
// ----------------------------
if (empty($_SESSION['nivel_acesso']) || intval($_SESSION['nivel_acesso']) <= 0) {
    echo "<p style='color:red;'>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}

// ----------------------------
// 🔍 Consulta todas as entradas financeiras
// ----------------------------
$sql_entradas = "SELECT id, valor, nome_completo AS descricao, usuario_email, dia, 'Entrada' AS tipo FROM entrada";
$result_entradas = $mysqli->query($sql_entradas);

// ----------------------------
// 🔍 Consulta todas as saídas financeiras
// ----------------------------
$sql_saidas = "SELECT id, valor, especificacao AS descricao, usuario_email, dia, 'Saída' AS tipo FROM saida";
$result_saidas = $mysqli->query($sql_saidas);

// ----------------------------
// 🧾 Junta todas as movimentações em um único array
// ----------------------------
$movimentacoes = [];

if ($result_entradas && $result_entradas->num_rows > 0) {
    while ($row = $result_entradas->fetch_assoc()) {
        $movimentacoes[] = $row; // Adiciona cada entrada no array
    }
}

if ($result_saidas && $result_saidas->num_rows > 0) {
    while ($row = $result_saidas->fetch_assoc()) {
        $movimentacoes[] = $row; // Adiciona cada saída no array
    }
}

// ----------------------------
// 📅 Ordena todas as movimentações por data (mais recentes primeiro)
// ----------------------------
usort($movimentacoes, function ($a, $b) {
    return strtotime($b['dia']) - strtotime($a['dia']);
});

// ----------------------------
// 💰 Calcula totais de entradas e saídas
// ----------------------------
$totalEntradas = 0;
$totalSaidas = 0;

foreach ($movimentacoes as $mov) {
    if ($mov['tipo'] === 'Entrada') {
        $totalEntradas += $mov['valor'];
    } else {
        $totalSaidas += $mov['valor'];
    }
}

// ----------------------------
// 🔹 Calcula saldo geral
// ----------------------------
$saldo = $totalEntradas - $totalSaidas;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório Financeiro - AEMPA</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <!-- 🧭 MENU LATERAL -->
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

    <!-- 📋 CONTEÚDO PRINCIPAL -->
    <div class="saldo-container">
        <div class="saldo-card">
            <!-- Mostra totais de entradas, saídas e saldo -->
            <div class="totais">
                <p><strong>Total de Entradas:</strong> R$ <?= number_format($totalEntradas, 2, ',', '.'); ?></p>
                <p><strong>Total de Saídas:</strong> R$ <?= number_format($totalSaidas, 2, ',', '.'); ?></p>
                <p><strong>Saldo Geral:</strong>
                    <span style="color: <?= ($saldo >= 0) ? 'green' : 'red'; ?>">
                        R$ <?= number_format($saldo, 2, ',', '.'); ?>
                    </span>
                </p>
            </div>

            <h2>📋 Relatório Financeiro</h2>

            <!-- Tabela com todas as movimentações -->
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Tipo</th>
                        <th>Descrição</th>
                        <th>Usuário</th>
                        <th>Valor (R$)</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Verifica se existem movimentações -->
                    <?php if (!empty($movimentacoes)): ?>
                        <?php foreach ($movimentacoes as $mov): ?>
                            <tr>
                                <!-- Formata data -->
                                <td><?= date('d/m/Y', timestamp: strtotime($mov['dia'])); ?></td>
                                <!-- Tipo com cor (verde = entrada, vermelho = saída) -->
                                <td style="font-weight:bold; color: <?= ($mov['tipo'] === 'Entrada') ? '#28a745' : '#dc3545'; ?>">
                                    <?= $mov['tipo']; ?>
                                </td>
                                <!-- Descrição e usuário -->
                                <td><?= htmlspecialchars($mov['descricao']); ?></td>
                                <td><?= htmlspecialchars($mov['usuario_email']); ?></td>
                                <!-- Valor formatado com sinal -->
                                <td style="font-weight:bold;">
                                    <?php
                                    $valor_formatado = number_format($mov['valor'], 2, ',', '.');
                                    echo ($mov['tipo'] === 'Saída')
                                        ? "- R$ $valor_formatado"
                                        : "+ R$ $valor_formatado";
                                    ?>
                                </td>
                                <!-- Link para excluir registro -->
                                <td>
                                    <a href="excluir_mov.php?tipo=<?= strtolower($mov['tipo']); ?>&id=<?= $mov['id']; ?>"
                                       onclick="return confirm('Tem certeza que deseja excluir este registro?')">
                                        🗑️
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Caso não haja movimentações -->
                        <tr><td colspan="6" style="text-align:center;">Nenhuma movimentação registrada.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<!--
Resumo do funcionamento:

Verifica login e nível de acesso do usuário.

Consulta entradas e saídas financeiras no banco de dados.

Junta tudo em um array $movimentacoes e ordena por data (mais recentes primeiro).

Calcula totais de entradas, saídas e saldo.

Mostra tabela com todas as movimentações, incluindo usuário que realizou a ação.

Permite exclusão de registros com confirmação.

Aplica cores e sinais para entradas (+ verde) e saídas (- vermelho).
-->