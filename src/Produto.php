<?php

namespace App;

use PDO;
use PDOException;

class Produto
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllProdutos()
    {
        try {
            $query = "SELECT * FROM produtos";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Tratar erros aqui
            return false;
        }
    }

    public function criarProduto($nome, $descricao, $preco, $quantidade)
    {
        $query = "INSERT INTO produtos (nome, descricao, preco, quantidade) VALUES (:nome, :descricao, :preco, :quantidade)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':quantidade', $quantidade);
        return $stmt->execute();
    }

    public function buscarProdutoPorId($id)
    {
        $query = "SELECT * FROM produtos WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarProduto($id, $nome, $descricao, $preco, $quantidade) {
        try {
            $query = "UPDATE produtos SET nome = :nome, descricao = :descricao, preco = :preco, quantidade = :quantidade WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':preco', $preco);
            $stmt->bindParam(':quantidade', $quantidade);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Tratar erros aqui
            return false;
        }
    }
    public function salvarProduto($nome, $descricao, $preco, $codigoPedido)
    {
        try {
            // Prepare a declaração de inserção
            $stmt = $this->conn->prepare("INSERT INTO produtos (nome, descricao, preco, codigo_pedido) VALUES (:nome, :descricao, :preco, :codigo_pedido)");

            // Bind os parâmetros
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':preco', $preco);
            $stmt->bindParam(':codigo_pedido', $codigoPedido);

            // Executar a declaração
            $stmt->execute();

            // Verificar se a inserção foi bem-sucedida
            if ($stmt->rowCount() > 0) {
                return true; // Produto salvo com sucesso
            } else {
                return false; // Falha ao salvar o produto
            }
        } catch (\PDOException $e) {
            // Lidar com qualquer exceção de banco de dados
            // Por exemplo, você pode registrar o erro em um arquivo de log
            return false; // Retorna false em caso de erro
        }
    }

    public function excluirProduto($id)
{
    $query = "DELETE FROM produtos WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

}
