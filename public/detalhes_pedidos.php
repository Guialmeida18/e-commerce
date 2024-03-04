<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
use App\Connection;
use App\Pedido;
use App\PedidoProduto;

// Verificar se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Verificar se foi fornecido um ID de pedido válido na URL
if (!isset($_GET['pedido_id']) || !is_numeric($_GET['pedido_id'])) {
    header("Location: meus_pedidos.php");
    exit;
}

// Receber o ID do pedido da URL
$pedido_id = $_GET['pedido_id'];

// Inicializar a conexão com o banco de dados
$conn = new Connection();

// Instanciar as classes de Pedido e PedidoProduto
$pedidoClass = new Pedido($conn->conn);
$produtoPedidoClass = new PedidoProduto($conn->conn);

// Buscar os detalhes do pedido
$pedido = $pedidoClass->buscarPedidoPorId($pedido_id);

// Verificar se o pedido foi encontrado
if (!$pedido) {
    echo "Pedido não encontrado.";
    exit;
}

// Buscar os produtos associados a este pedido
$produtosPedido = $produtoPedidoClass->buscarProdutosPorPedido($pedido_id);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
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
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
            margin-bottom: 20px;
        }

        .pedido p {
            margin: 5px 0;
            font-size: 16px;
            color: #333;
        }

        .pedido-info {
            margin-top: 20px;
            text-align: center;
        }

        .pedido-info p {
            margin-bottom: 5px;
        }

        .user-info {
            text-align: right;
            margin-bottom: 20px;
        }

        .user-info a {
            margin-left: 10px;
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="user-info">
        <p>Olá, <?php echo $_SESSION['usuario']['nome']; ?> | <a href="meu_carrinho.php">Meu Carrinho</a> | <a href="meus_pedidos.php">Pedidos</a> | <a href="logout.php">Sair</a></p>
    </div>
    <h1>Detalhes do Pedido</h1>
    <h2>ID do Pedido: <?php echo $pedido['id']; ?></h2>

    <?php
    // Verificar se existem produtos associados a este pedido
    if ($produtosPedido) {
        // Iterar sobre os produtos do pedido
        foreach ($produtosPedido as $produto_pedido) {
            echo "<div class='pedido'>";
            echo "<p><strong>Preço:</strong> R$" . $produto_pedido['preco_unitario'] . "</p>";
            echo "<p><strong>Quantidade:</strong> " . $produto_pedido['quantidade'] . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>Nenhum produto encontrado para este pedido.</p>";
    }
    ?>

    <div class="pedido-info">
        <p><strong>Código do Pedido:</strong> <?php echo $pedido['id']; ?></p>
        <p><strong>Valor Total da Compra:</strong> R$<?php echo calcularTotalCompra($produtosPedido); ?></p>
        <p><strong>Data do Pedido:</strong> <?php echo $pedido['data_pedido']; ?></p>
        <p><strong>Método de Pagamento:</strong> Cartão de Crédito</p> <!-- Adicione aqui o método de pagamento -->
    </div>

    <div style="text-align: center; margin-top: 20px;">
    </div>
</div>
</body>
</html>

<?php
function calcularTotalCompra($produtosPedido) {
    $total = 0;
    foreach ($produtosPedido as $produto) {
        $total += $produto['preco_unitario'] * $produto['quantidade'];
    }
    return $total;
}
?>




