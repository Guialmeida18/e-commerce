<?php
// Inicia a sessão antes de qualquer saída HTML
session_start();

// Verifica se o usuário está logado
$nomeUsuario = isset($_SESSION['usuario']['nome']) ? $_SESSION['usuario']['nome'] : "Usuário";

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Realizada com Sucesso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .welcome-message {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .success-icon {
            color: #fff;
            background-color: #28a745;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            line-height: 100px;
            margin: 0 auto 20px;
            font-size: 48px;
        }
        .order-code {
            font-size: 20px;
            margin-bottom: 20px;
        }
        .success-message {
            font-size: 18px;
            margin-bottom: 20px;
            color: #28a745;
        }
        .links a {
            display: inline-block;
            margin: 5px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .links a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Saudação com o nome do usuário -->
    <div class="welcome-message">Bem-vindo, <?php echo $nomeUsuario; ?>!</div>

    <!-- Ícone de sinal de positivo na bola verde -->
    <div class="success-icon">&#10003;</div>

    <!-- Código do pedido com "#" -->
    <div class="order-code">Pedido: #<?php echo $_SESSION['codigo_pedido']; ?></div>

    <!-- Mensagem de compra bem-sucedida -->
    <div class="success-message">Compra realizada com sucesso!</div>

    <!-- Links para Meu Carrinho, Meus Pedidos e Sair -->
    <div class="links">
        <a href="meu_carrinho.php">Meu Carrinho</a>
        <a href="meus_pedidos.php">Meus Pedidos</a>
        <a href="sair.php">Sair</a>
    </div>
</div>
</body>
</html>


