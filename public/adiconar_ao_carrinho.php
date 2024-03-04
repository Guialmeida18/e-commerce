<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Connection;
use App\Produto;

// Inicializa a sessão
session_start();

// Cria uma instância da classe de conexão
$conn = new Connection();

// Cria uma instância da classe Produto
$produtoClass = new Produto($conn->conn);

// Verifica se houve um envio de formulário via método GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Verifica se o produto_id foi enviado e se é um número válido
    if (isset($_GET['produto_id']) && is_numeric($_GET['produto_id'])) {
        // Obtém o ID do produto a ser adicionado ao carrinho
        $produto_id = $_GET['produto_id'];

        // Verifica se o produto já está no carrinho
        if (!isset($_SESSION['carrinho'][$produto_id])) {
            // Se o produto ainda não estiver no carrinho, adiciona com quantidade 1
            $_SESSION['carrinho'][$produto_id] = 1;
        } else {
            // Se o produto já estiver no carrinho, incrementa a quantidade
            $_SESSION['carrinho'][$produto_id]++;
        }

        // Redireciona de volta para esta mesma página para evitar o reenvio do formulário
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Obtém todos os produtos
$produtos = $produtoClass->getAllProdutos();

// Armazena os produtos na sessão
$_SESSION['produtos'] = $produtos;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar ao Carrinho</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            position: relative;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 20px;
        }

        li {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }

        h2 {
            font-size: 1.4rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 15px;
            font-size: 1rem;
            color: #555;
        }

        form {
            display: inline;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            padding: 8px 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Posicionamento dos links no canto superior direito */
        .top-right {
            position: absolute;
            top: 20px;
            right: 20px;
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
    </style>
</head>
<body>
<div class="top-right">
    <a href="login.php">Entrar</a>
    <a href="meu_carrinho.php">Meu Carrinho</a>
</div>
<h1>Adicionar ao Carrinho</h1>
<ul>
    <?php foreach ($produtos as $produto): ?>
        <li>
            <h2><?php echo $produto['nome']; ?></h2>
            <p>Descrição: <?php echo $produto['descricao']; ?></p>
            <p>Preço: R$ <?php echo $produto['preco']; ?></p>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                <button type="submit">Adicionar ao Carrinho</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>





