<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Pedido;
use App\Connection;
use App\PedidoProduto;
use App\Produto; // Adicione a classe Produto

session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Verifica se os dados do formulário foram submetidos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se todos os campos do formulário estão presentes e não estão vazios
    $required_fields = ['numero_cartao', 'nome_cartao', 'data_validade', 'cvv'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            // Se algum campo estiver ausente ou vazio, redireciona de volta ao formulário de pagamento com uma mensagem de erro
            $_SESSION['error_message'] = "Todos os campos são obrigatórios.";
            header("Location: pagamento.php");
            exit;
        }
    }

    // Sanitiza os dados do formulário
    $numeroCartao = htmlspecialchars($_POST['numero_cartao']);
    $nomeCartao = htmlspecialchars($_POST['nome_cartao']);
    $dataValidade = htmlspecialchars($_POST['data_validade']);
    $cvv = htmlspecialchars($_POST['cvv']);

    // Obtém o ID do usuário da sessão
    $usuarioId = $_SESSION['usuario']['id'] ?? null;

    if ($usuarioId) {
        $conn = new Connection();
        $pedidoClass = new Pedido($conn->conn);
        $pedidoProdutoClass = new PedidoProduto($conn->conn);
        $produtoClass = new Produto($conn->conn); // Instância da classe Produto

        // Define o status como "Pendente"
        $status = 'pendente';

        // Obtém a data e hora atual no formato MySQL
        $dataPedido = date('Y-m-d H:i:s');

        // Insere o pedido no banco de dados
        $pedidoInserido = $pedidoClass->inserirPedido($usuarioId, $status, $dataPedido, $numeroCartao, $dataValidade, $cvv, $nomeCartao);

        if ($pedidoInserido) {
            // Obtém o código do pedido inserido
            $codigoPedido = $pedidoClass->obterCodigo();

            // Adiciona os produtos associados ao pedido na tabela pedido_produto
            foreach ($_SESSION['carrinho'] as $produto_id => $quantidade) {
                // Verifica se o ID do produto é válido
                if (isset($produto_id) && !empty($produto_id)) {
                    // Obtém informações completas do produto
                    $produto = $produtoClass->buscarProdutoPorId($produto_id);
                    if ($produto) {
                        // Insere o produto do carrinho na tabela pedido_produto
                        $pedidoProdutoInserido = $pedidoProdutoClass->adicionarProdutoPedido($codigoPedido, $produto_id, $quantidade, $produto['preco']);
                        if (!$pedidoProdutoInserido) {
                            // Tratar falha ao adicionar o produto ao pedido
                            echo "Falha ao adicionar o produto ao pedido.<br>";
                        }
                    } else {
                        // Produto não encontrado ou inválido
                        echo "Produto não encontrado ou inválido.<br>";
                    }
                } else {
                    // Tratar produto sem ID válido
                    echo "Produto sem ID válido.<br>";
                }
            }

            // Define o código do pedido na sessão
            $_SESSION['codigo_pedido'] = $codigoPedido;

            // Redireciona para a página de sucesso
            header("Location: sucesso.php");
            exit;
        } else {
            // Define um valor padrão para $_SESSION['codigo_pedido']
            $_SESSION['codigo_pedido'] = null;

            // Se houver um erro ao inserir o pedido, redireciona de volta ao formulário de pagamento com uma mensagem de erro
            $_SESSION['error_message'] = "Erro ao processar o pedido. Por favor, tente novamente.";
            header("Location: pagamento.php");
            exit;
        }
    }
}



