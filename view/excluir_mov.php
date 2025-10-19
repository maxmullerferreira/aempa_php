<?php
session_start();
include('../config/config.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Verifica se recebeu os parâmetros corretos
if (isset($_GET['tipo']) && isset($_GET['id'])) {
    $tipo = strtolower(trim($_GET['tipo']));
    $id = intval($_GET['id']);

    // Define a tabela com base no tipo
    if ($tipo === 'entrada') {
        $tabela = 'entrada';
    } elseif ($tipo === 'saída' || $tipo === 'saida') {
        $tabela = 'saida';
    } else {
        die("Tipo inválido.");
    }

    // Executa a exclusão
    $stmt = $mysqli->prepare("DELETE FROM $tabela WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "<script>alert('Registro excluído com sucesso!'); window.location.href='relatorios.php';</script>";
    } else {
        echo "<script>alert('Erro ao excluir o registro.'); window.location.href='relatorios.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Dados inválidos para exclusão.'); window.location.href='relatorios.php';</script>";
}
?>
