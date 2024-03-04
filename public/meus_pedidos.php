<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Connection;
use App\Pedido;
use App\PedidoProduto; // Importar a classe PedidoProduto

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$conn = new Connection();
$pedidoClass = new Pedido($conn->conn);

// Inicializa a variável $usuario_id e $nome_usuario
$usuario_id = $_SESSION['usuario']['id'];
$nome_usuario = $_SESSION['usuario']['nome'];

// Buscar pedidos por usuário
$pedidosUsuario = $pedidoClass->buscarPedidoPorUsuarioId($usuario_id);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .pedido {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 20px;
        }

        .pedido h2 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .pedido p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .pedido a {
            text-decoration: none;
            color: #007bff;
            margin-right: 10px;
            font-size: 14px;
        }

        .pedido a:hover {
            text-decoration: underline;
        }

        .link {
            text-align: center;
            margin-top: 20px;
        }

        .link a {
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
        }

        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Meus Pedidos</h1>
    <p>Olá, <?php echo $nome_usuario; ?>!</p> <!-- Exibe o nome do usuário -->
    <?php
    if ($pedidosUsuario) {
        foreach ($pedidosUsuario as $pedido) {
            echo "<div class='pedido'>";
            echo "<h2>ID do Pedido: " . $pedido['id'] . "</h2>";
            echo "<p>Data do Pedido: " . $pedido['data_pedido'] . "</p>";

            // Buscar produtos do pedido
            $produtoPedidoClass = new PedidoProduto($conn->conn);
            $produtosPedido = $produtoPedidoClass->buscarProdutosPorPedido($pedido['id']);

            if ($produtosPedido) {
                echo "<h3>Produtos do Pedido:</h3>";
                foreach ($produtosPedido as $produto) {
                    echo "<p>Quantidade: " . $produto['quantidade'] . "</p>";
                    echo "<p>Preço: R$" . $produto['preco_unitario'] . "</p>";
                    echo "<hr>";
                }
            } else {
                echo "<p>Nenhum produto encontrado para este pedido.</p>";
            }

            echo "<a href='detalhes_pedidos.php?pedido_id=" . $pedido['id'] . "&data_pedido=" . $pedido['data_pedido'] . "'>Detalhes</a>";
            echo "</div>";
        }
    } else {
        echo "<p>Nenhum pedido encontrado para este usuário.</p>";
    }
    ?>
    <div class="link">
        <a href="meu_carrinho.php">Meu Carrinho</a>
        <a href="meus_pedidos.php">Pedidos</a>
        <a href="logout.php">Sair</a>
    </div>
</div>
</body>
</html>
