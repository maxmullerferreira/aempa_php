<?php
// ----------------------------
// 🔹 Verifica se a sessão já foi iniciada
// ----------------------------
if(!isset($_SESSION)){
    // Se a sessão não estiver iniciada, inicia uma nova sessão
    session_start();
}

// ----------------------------
// 🔒 Encerra a sessão atual
// ----------------------------
// Remove todas as variáveis de sessão e efetivamente "desloga" o usuário
session_destroy();

// ----------------------------
// 🔄 Redireciona o usuário para a página de login
// ----------------------------
header("Location: login.php");
exit; // Boa prática: garante que o script pare após o redirecionamento
?>

<!--
Resumo do funcionamento:

Inicia a sessão, caso ainda não tenha sido iniciada.

Destrói todas as variáveis e dados da sessão atual (session_destroy()), efetivamente deslogando o usuário.

Redireciona para a página de login (login.php).

Dica: Sempre usar exit; após header("Location: ...") para evitar que o restante do script seja executado.
-->