<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Connection;
use App\Produto;
use App\Usuario;

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['email'])){
    header("Location: login.php");
    exit;
}

$conn = new Connection();
$produtoClass = new Produto($conn->conn);

// Obtém os produtos adicionados ao carrinho, se existirem
$carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : array();

// Cria uma instância da classe de usuário
$usuarioClass = new Usuario($conn->conn);

// Obtém informações completas dos produtos adicionados ao carrinho
$produtosNoCarrinho = array();
$totalCompra = 0; // Inicializa o total da compra
foreach ($carrinho as $produto_id => $quantidade) {
    $produto = $produtoClass->buscarProdutoPorId($produto_id);
    if ($produto) {
        $produto['quantidade'] = $quantidade;
        $subtotal = $produto['preco'] * $quantidade; // Calcula o subtotal do produto
        $produto['subtotal'] = $subtotal; // Adiciona o subtotal ao produto
        $totalCompra += $subtotal; // Adiciona o subtotal ao total da compra
        $produtosNoCarrinho[] = $produto;
    }
}

// Armazena o total da compra na sessão
$_SESSION['total_Compra'] = $totalCompra;

// Verifica se o formulário de remoção foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['produto_id'])) {
    // Obtém o ID do produto a ser removido
    $produto_id = $_POST['produto_id'];

    // Remove o produto do carrinho na sessão
    if (array_key_exists($produto_id, $_SESSION['carrinho'])) {
        unset($_SESSION['carrinho'][$produto_id]);
    }

    // Redireciona de volta para esta página para atualizar a exibição do carrinho
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Busca o nome do usuário com base no email da sessão
$nomeUsuario = $usuarioClass->buscarUsuarioPorEmail($_SESSION['email']);

if ($nomeUsuario !== null) {
    // Obtém o nome do usuário
    $nomeUsuario = isset($nomeUsuario['nome']) ? $nomeUsuario['nome'] : "Usuário";
    // Não precisa acessar $nomeUsuario['nome'] aqui, pois já tem o nome do usuário
} else {
    // Defina um valor padrão para o nome do usuário se não for encontrado
    $nomeUsuario = "Usuário";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Carrinho</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa; /* Altere a cor de fundo conforme necessário */
        }

        .top-right {
            position: absolute;
            top: 20px;
            right: 20px;
            color: #fff;
        }

        .top-right a {
            color: #007bff;
            text-decoration: none;
            margin-left: 20px;
            font-size: 1rem;
            transition: color 0.3s;
        }

        .top-right a:hover {
            color: #0056b3;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh; /* Altere conforme necessário */
        }

        form {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 90%; /* Altere conforme necessário */
            max-width: 600px; /* Limite a largura máxima conforme necessário */
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 2rem; /* Altere o tamanho da fonte conforme necessário */
        }

        ul {
            list-style: none;
            padding: 0;
            margin-bottom: 30px;
        }

        li {
            background-color: #f8f9fa; /* Altere a cor de fundo conforme necessário */
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            padding: 20px;
        }

        input[type="hidden"],
        input[type="number"],
        button {
            width: calc(100% - 22px);
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #007bff; /* Altere a cor da borda conforme necessário */
            box-sizing: border-box;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 1rem; /* Altere o tamanho da fonte conforme necessário */
        }

        input[type="hidden"],
        input[type="number"] {
            background-color: #fff;
            color: #333;
        }

        button:hover {
            background-color: #0056b3;
        }

        .payment-button {
            background-color: #28a745;
            margin-top: 30px;
        }

        .payment-button:hover {
            background-color: #218838;
        }

        p {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
    </style>
</head>
<body>
<div class="top-right">
    <?php if (isset($nomeUsuario)): ?>
        <p>Bem-vindo, <?php echo $nomeUsuario; ?></p>
    <?php endif; ?>
    <a href="meu_carrinho.php">Meu Carrinho</a>
    <a href="meus_pedidos.php">Meus Pedidos</a>
    <a href="logout.php">Sair</a>
</div>
<div class="container">
    <form action="pagamento.php" method="post">
        <h2>Meu Carrinho</h2>
        <ul>
            <?php foreach ($produtosNoCarrinho as $produto): ?>
                <li>
                    <h3><?php echo $produto['nome']; ?></h3>
                    <p>Descrição: <?php echo $produto['descricao']; ?></p>
                    <p>Preço: R$ <?php echo $produto['preco']; ?></p>
                    <p>Quantidade: <?php echo $produto['quantidade']; ?></p>
                    <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                    <button type="submit">Remover</button>
                </li>
            <?php endforeach; ?>
        </ul>
        <p>Total da compra: R$ <?php echo number_format($totalCompra, 2, ',', '.'); ?></p>
        <button class="payment-button" type="submit">Ir para pagamento</button>
    </form>
</div>
</body>
</html>






