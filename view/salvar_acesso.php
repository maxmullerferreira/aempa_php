<?php
// ----------------------------
// 🔹 Inclui a configuração do banco de dados
// ----------------------------
include('../config/config.php');

// ----------------------------
// 🔹 Verifica se o formulário foi enviado via POST
// ----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 🔹 Captura os dados enviados pelo formulário
    $email = trim($_POST['email']);               // Remove espaços extras do e-mail
    $nivel_acesso = intval($_POST['nivel_acesso']); // Converte o nível de acesso para inteiro

    // ----------------------------
    // 🔒 Validação do nível de acesso
    // Apenas níveis 1 ou 2 são válidos
    // ----------------------------
    if ($nivel_acesso < 1 || $nivel_acesso > 2) {
        // Alerta o usuário e redireciona de volta ao controle de acesso
        echo "<script>alert('Nível de acesso inválido! Deve ser 1 ou 2.'); window.location='controle_acesso.php';</script>";
        exit;
    }

    // ----------------------------
    // 🔧 Atualiza o nível de acesso do usuário no banco
    // ----------------------------
    $stmt = $mysqli->prepare("UPDATE usuario SET nivel_acesso = ? WHERE email = ?");
    $stmt->bind_param("is", $nivel_acesso, $email); // "i" = inteiro, "s" = string
    $stmt->execute();

    // ----------------------------
    // ✅ Verifica se a alteração foi realizada
    // ----------------------------
    if ($stmt->affected_rows > 0) {
        // Sucesso: nível de acesso alterado
        echo "<script>alert('Nível de acesso atualizado com sucesso!'); window.location='controle_acesso.php';</script>";
    } else {
        // Falha: usuário não encontrado ou nível já era igual
        echo "<script>alert('Usuário não encontrado ou sem alterações.'); window.location='controle_acesso.php';</script>";
    }
}
?>

<!--
Resumo do funcionamento

Inclui configuração do banco: para conectar ao MySQL.

Recebe dados do formulário: email e nivel_acesso.

Valida o nível: garante que seja 1 ou 2, evitando valores inválidos.

Atualiza o banco: usa Prepared Statement para segurança contra SQL Injection.

Feedback ao usuário: usa alert() do JavaScript para informar sucesso ou falha e redirecionar de volta à página de controle de acesso.
-->