<?php
// Inicia a sessão para permitir o uso de variáveis de sessão
session_start();

// Inclui o arquivo de configuração com a conexão ao banco de dados (mysqli)
include('../config/config.php');


// ----------------------------
// 🔒 Verificação de login
// ----------------------------

// Verifica se o usuário está logado.
// Caso contrário, redireciona para a página de login.
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit; // Interrompe a execução para evitar acesso indevido
}


// ----------------------------
// 📦 Verificação dos parâmetros recebidos
// ----------------------------

// Verifica se foram passados os parâmetros 'tipo' e 'id' pela URL (via método GET)
if (isset($_GET['tipo']) && isset($_GET['id'])) {

    // Captura o tipo (entrada ou saída) e o ID do registro
    $tipo = strtolower(trim($_GET['tipo'])); // Converte para minúsculo e remove espaços
    $id = intval($_GET['id']); // Converte o ID para inteiro (evita injeção SQL)


    // ----------------------------
    // 🧩 Determina a tabela correta
    // ----------------------------

    // Define qual tabela será usada com base no tipo recebido
    if ($tipo === 'entrada') {
        $tabela = 'entrada';
    } 
    // Aceita tanto "saída" (com acento) quanto "saida" (sem acento)
    elseif ($tipo === 'saída' || $tipo === 'saida') {
        $tabela = 'saida';
    } 
    // Caso o tipo seja diferente, interrompe o script com uma mensagem de erro
    else {
        die("Tipo inválido.");
    }


    // ----------------------------
    // 🗑️ Execução da exclusão no banco
    // ----------------------------

    // Cria uma query preparada para evitar SQL Injection
    // O nome da tabela é definido dinamicamente, mas o ID é passado como parâmetro seguro
    $stmt = $mysqli->prepare("DELETE FROM $tabela WHERE id = ?");
    $stmt->bind_param('i', $id); // 'i' = tipo inteiro


    // Executa o comando e verifica o resultado
    if ($stmt->execute()) {
        // Caso a exclusão seja bem-sucedida, exibe mensagem e redireciona para relatórios
        echo "<script>
                alert('Registro excluído com sucesso!');
                window.location.href='relatorios.php';
              </script>";
    } else {
        // Caso ocorra algum erro na exclusão
        echo "<script>
                alert('Erro ao excluir o registro.');
                window.location.href='relatorios.php';
              </script>";
    }

    // Fecha o statement após o uso
    $stmt->close();
} 
// Caso os parâmetros esperados não tenham sido enviados
else {
    echo "<script>
            alert('Dados inválidos para exclusão.');
            window.location.href='relatorios.php';
          </script>";
}
?>

<!--
| Etapa                                        | Descrição                                                                         |
| -------------------------------------------- | --------------------------------------------------------------------------------- |
| **1️⃣ Sessão iniciada**                      | Garante que apenas usuários autenticados possam excluir dados.                    |
| **2️⃣ Verificação de parâmetros**            | Confere se `tipo` e `id` estão presentes na URL (`?tipo=entrada&id=5`).           |
| **3️⃣ Definição da tabela**                  | Usa o parâmetro `tipo` para decidir se vai excluir de `entrada` ou `saida`.       |
| **4️⃣ Execução segura (prepared statement)** | Protege contra SQL Injection, passando o ID como parâmetro.                       |
| **5️⃣ Mensagem ao usuário**                  | Exibe `alert()` no navegador e redireciona automaticamente para `relatorios.php`. |
| **6️⃣ Tratamento de erros**                  | Mostra mensagens apropriadas caso os parâmetros ou a exclusão falhem.             |
-->