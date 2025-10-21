<?php
// Inicia a sessão — necessária para acessar as variáveis da sessão (como usuário logado)
session_start();

// Inclui o arquivo de configuração que contém a conexão com o banco de dados
include('../config/config.php');

// 🔒 Verifica se o usuário está logado
// Caso não exista a variável de sessão 'usuario_id', redireciona o usuário para a tela de login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit; // Encerra o script após o redirecionamento
}

// 🔐 Verifica o nível de acesso do usuário
// Somente usuários com nível maior que 0 (ex: administradores) podem acessar esta página
if (empty($_SESSION['nivel_acesso']) || intval($_SESSION['nivel_acesso']) <= 0) {
    // Exibe uma mensagem de acesso negado, caso o usuário não tenha permissão
    echo "<p style='color:red;'>Acesso negado. Você não tem permissão para acessar esta página.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8"> <!-- Define a codificação de caracteres -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Torna o site responsivo -->
  <title>Painel Administrativo - AEMPA</title> <!-- Título que aparece na aba do navegador -->
  <link rel="stylesheet" href="../assets/style.css"> <!-- Importa o arquivo CSS externo -->
</head>

<body>
  
<!-- Estrutura principal do painel -->
<div class="dashboard">
  
  <!-- Barra lateral (menu fixo) -->
  <aside class="sidebar">
    <!-- Logo da AEMPA -->
    <img src="logo.png" alt="AEMPA Logo" class="logo-small">

    <!-- Título da seção lateral -->
    <h2>Portal Administrativo</h2>

    <!-- Menu de navegação principal -->
    <nav>
      <ul>
        <!-- Cada item leva a uma página diferente do sistema -->
        <li><a href="dashboard.php">📊 Dashboard</a></li>
        <li><a href="lista_associados.php">📋 Associados</a></li>
        <li><a href="entrada.php">💰 Entradas</a></li>
        <li><a href="saida.php">💸 Saídas</a></li>
        <li><a href="saldo.php">💼 Saldo</a></li>
        <li><a href="logout.php">🚪 Logout</a></li>
      </ul>
    </nav>
  </aside>

  <!-- Conteúdo principal da página -->
  <main class="main-content">
    <section>
      <!-- Título da seção principal -->
      <h2>Menu de Acesso</h2>

      <!-- Submenu com os atalhos administrativos -->
      <h3>
        <ul class="submenu">
          <!-- Links para as funções administrativas do sistema -->
          <li><a href="cadastro_associado.php">Cadastro de Associados</a></li>
          <li><a href="cadastro_e-mail.php">Cadastro de Usuário</a></li>
          <li><a href="reativacao.php">Reativação de Associados</a></li>
          <li><a href="atualizacao.php">Atualização de Associado</a></li>
          <li><a href="desativar.php">Desativar Associado</a></li>
          <li><a href="relatorios.php">Relatório Financeiro</a></li>
          <li><a href="controle_acesso.php">Controle de Acesso</a></li>
          <li><a href="entrada.php">Entrada</a></li>
          <li><a href="saida.php">Saída</a></li>
        </ul>
      </h3>
    </section>
  </main>
</div>
</body>
</html>
<!--
Explicação geral do funcionamento:

✅ PHP (antes do HTML)

Garante que apenas usuários autenticados e com nível de acesso suficiente entrem.

Se o usuário não tiver permissão, o script interrompe a execução e exibe uma mensagem.

✅ HTML (interface visual)

Mostra o painel administrativo da AEMPA.

O menu lateral contém os atalhos principais do sistema.

A área central mostra o Menu de Acesso, com links diretos para páginas de cadastro, atualização e controle financeiro.

✅ CSS externo (../assets/style.css)

É responsável por todo o design e formatação da página (cores, espaçamento, layout etc.).
-->