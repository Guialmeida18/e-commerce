<?php
session_start();

// Verifica se o parâmetro produto_id foi passado na URL
if (isset($_GET['produto_id'])) {
    $produto_id = $_GET['produto_id'];
    // Remove o produto da sessão com base no ID
    unset($_SESSION['carrinho'][$produto_id]);
}

// Redireciona de volta para a página anterior (ou para alguma outra página)
header("Location: {$_SERVER['HTTP_REFERER']}");
exit;
?>
