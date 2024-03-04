<?php
session_start();

// Verifica se o parâmetro produto_id foi passado na URL
if (isset($_GET['produto_id'])) {
    $produto_id = $_GET['produto_id'];
    // Adiciona o produto a ser removido à sessão remover_produto
    $_SESSION['remover_produto'][] = $produto_id;
}

// Redireciona de volta para a página do carrinho
header("Location: meu_carrinho.php");
exit;
