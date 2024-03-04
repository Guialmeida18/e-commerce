<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Connection;
use App\Usuario;

session_start();

// Verifica se o usuário está logado e se há uma sessão de carrinho anterior
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['email'])) {
    // Verifica se há uma sessão de carrinho anterior para este usuário
    if (isset($_SESSION['carrinho'][$_SESSION['email']])) {
        // Transfere os produtos do carrinho da sessão anterior para a sessão atual do usuário logado
        $_SESSION['carrinho'] = $_SESSION['carrinho'][$_SESSION['email']];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['senha'])) {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $conexao = new Connection();
        $usuario = new Usuario($conexao->conn);
        $usuario_encontrado = $usuario->buscarUsuarioPorEmail($email);

        if ($usuario_encontrado && password_verify($senha, $usuario_encontrado['senha'])) {
            // Define a variável de sessão "logged_in" como true
            $_SESSION['logged_in'] = true;
            // Define a variável de sessão "email" com o email do usuário
            $_SESSION['email'] = $email;
            $_SESSION['usuario'] = $usuario_encontrado;

            // Redireciona o usuário para a página "meu carrinho"
            header("Location: meu_carrinho.php");
            exit;
        } else {
            $erro_login = "Email ou senha incorretos. Tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        form {
            background-color: #fff;
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: calc(100% - 22px);
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 3px;
            border: 1px solid #ccc;
            box-sizing: border-box; /* Garante que a largura inclui padding e borda */
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #ff0000;
            margin-bottom: 10px;
        }

        .register-link {
            text-align: center;
            margin-top: 10px;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <h2>Login</h2>
    <?php if (isset($erro_login)) : ?>
        <div class="error-message"><?php echo $erro_login; ?></div>
    <?php endif; ?>
    <input type="text" name="email" placeholder="Email" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <input type="submit" value="Login">
</form>
<div class="register-link">
    Ainda não tem uma conta? <a href="registro.php">Registrar-se</a>
</div>
</body>
</html>



