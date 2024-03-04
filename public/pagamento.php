<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
use App\Connection;
use App\Usuario;

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['email'])){
    header("Location: login.php");
    exit;
}

// Obtém o total da compra da sessão anterior
$totalCompra = isset($_SESSION['total_Compra']) ? $_SESSION['total_Compra'] : 0;

$conn = new Connection();
$usuarioClass = new Usuario($conn->conn);

// Obtém o nome do usuário do banco de dados
$emailUsuario = $_SESSION['email'];
$pedido_id = $_SESSION['pedido_id'] ?? null;
$usuarioEncontrado = $usuarioClass->buscarUsuarioPorEmail($emailUsuario);

// Verifica se a função retornou um array e se o índice 'nome' está definido no array
if (is_array($usuarioEncontrado) && isset($usuarioEncontrado['nome'])) {
    $nomeUsuario = $usuarioEncontrado['nome'];
} else {
    // Define um valor padrão se não for possível obter o nome do usuário
    $nomeUsuario = "Usuário";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pagamento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 15px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"] {
            padding: 10px;
            margin-bottom: 15px;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            padding: 12px 20px;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin-top: 20px;
        }

        ul li {
            margin-bottom: 10px;
        }

        ul li a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        ul li a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Pagamento</h2>
    <!-- Boas-vindas e nome do usuário -->
    <p>Bem-vindo, <?php echo $nomeUsuario; ?>!</p>
    <!-- Exibe o total da compra -->
    <p>Total da Compra: R$ <?php echo number_format($totalCompra, 2, ',', '.'); ?></p>
    <!-- Formulário de pagamento -->
    <form action="processar_pagamento.php" method="post">
        <input type="text" name="numero_cartao" placeholder="Número do Cartão">
        <input type="text" name="nome_cartao" placeholder="Nome no Cartão">
        <input type="text" name="data_validade" placeholder="Data de Validade (MM/AA)">
        <input type="text" name="cvv" placeholder="CVV">
        <!-- Campo oculto para enviar o pedido_id -->
        <input type="hidden" name="pedido_id" value="<?php echo $pedido_id; ?>">
        <input type="submit" value="Finalizar Compra">
    </form>
    <!-- Links para "Meus Pedidos", "Pedidos" e "Sair" -->
    <ul>
        <li><a href="meus_pedidos.php">Meus Pedidos</a></li>
        <li><a href="pedidos.php">Pedidos</a></li>
        <li><a href="logout.php">Sair</a></li>
    </ul>
</div>
</body>
</html>


