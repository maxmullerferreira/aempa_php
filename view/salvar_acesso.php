<?php
include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $nivel_acesso = intval($_POST['nivel_acesso']);

    // Verifica se o nível está dentro do permitido
    if ($nivel_acesso < 1 || $nivel_acesso > 2) {
        echo "<script>alert('Nível de acesso inválido! Deve ser 1 ou 2.'); window.location='controle_acesso.php';</script>";
        exit;
    }

    // Atualiza o nível de acesso no banco
    $stmt = $mysqli->prepare("UPDATE usuario SET nivel_acesso = ? WHERE email = ?");
    $stmt->bind_param("is", $nivel_acesso, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Nível de acesso atualizado com sucesso!'); window.location='controle_acesso.php';</script>";
    } else {
        echo "<script>alert('Usuário não encontrado ou sem alterações.'); window.location='controle_acesso.php';</script>";
    }
}
?>
