<?php

namespace App;

use PDO;

class PedidoProduto
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function adicionarProdutoPedido($pedido_id, $produto_id, $quantidade, $preco_unitario)
    {
        $query = "INSERT INTO pedido_produtos (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $pedido_id);
        $stmt->bindParam(2, $produto_id);
        $stmt->bindParam(3, $quantidade);
        $stmt->bindParam(4, $preco_unitario);
        return $stmt->execute();

    }

    public function buscarProdutosPorPedido($pedido_id)
    {
        $query = "SELECT * FROM pedido_produtos WHERE pedido_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$pedido_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function atualizarProdutoPedido($id, $quantidade, $preco_unitario)
    {
        $query = "UPDATE pedido_produtos SET quantidade = :quantidade, preco_unitario = :preco_unitario WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':preco_unitario', $preco_unitario);
        return $stmt->execute();
    }

    public function excluirProdutoPedido($id)
    {
        $query = "DELETE FROM pedido_produtos WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
