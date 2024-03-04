<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Connection;
use App\Usuario;

session_start();

$erro_registro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nome'], $_POST['email'], $_POST['senha'])) {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $conexao = new Connection();
        $usuario = new Usuario($conexao->conn);

        // Verifica se o e-mail já está em uso
        $usuario_existente = $usuario->buscarUsuarioPorEmail($email);

        if (!$usuario_existente) {
            // Cria o hash da senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            // Cria o usuário no banco de dados
            $usuario_criado = $usuario->criarUsuario($nome, $email, $senha_hash);

            if ($usuario_criado) {
                // Redireciona para a página de login após o registro bem-sucedido
                header("Location: login.php");
                exit;
            } else {
                $erro_registro = "Ocorreu um erro ao criar o usuário. Por favor, tente novamente.";
            }
        } else {
            $erro_registro = "Este email já está em uso. Por favor, escolha outro.";
        }
    } else {
        $erro_registro = "Por favor, preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
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

        .login-link {
            text-align: center;
            margin-top: 10px;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <h2>Registro</h2>
    <?php if (!empty($erro_registro)) : ?>
        <div class="error-message"><?php echo $erro_registro; ?></div>
    <?php endif; ?>
    <input type="text" name="nome" placeholder="Nome" required>
    <input type="text" name="email" placeholder="Email" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <input type="submit" value="Registrar">
</form>
<div class="login-link">
    Já tem uma conta? <a href="login.php">Faça login</a>
</div>
</body>
</html>