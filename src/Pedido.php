<?php

namespace App;

use PDO;

class Pedido
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function inserirPedido($usuarioId, $status, $dataPedido, $numeroCartao, $dataValidade, $cvv, $nome)
    {
        $sql = "INSERT INTO pedidos (usuario_id, status, data_pedido, numero_cartao, data_validade, cvv, nome) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if ($stmt) {
            $stmt->bindParam(1, $usuarioId, PDO::PARAM_INT);
            $stmt->bindParam(2, $status, PDO::PARAM_STR);
            $stmt->bindParam(3, $dataPedido, PDO::PARAM_STR);
            $stmt->bindParam(4, $numeroCartao, PDO::PARAM_STR);
            $stmt->bindParam(5, $dataValidade, PDO::PARAM_STR);
            $stmt->bindParam(6, $cvv, PDO::PARAM_STR);
            $stmt->bindParam(7, $nome, PDO::PARAM_STR);

            // Execute a query
            if ($stmt->execute()) {
                // Se a inserção for bem-sucedida, retorne o ID do último registro inserido
                return $this->conn->lastInsertId();
            } else {
                // Se ocorrer um erro ao executar a query, retorne false
                return false;
            }
        } else {
            // Se a preparação da query falhar, retorne false
            return false;
        }
    }

    public function buscarPedidoPorUsuarioId($usuario_id)
    {
        try {
            $query = "SELECT * FROM pedidos WHERE usuario_id = :usuario_id";
            $statement = $this->conn->prepare($query);
            $statement->bindParam(':usuario_id', $usuario_id, \PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetchAll();
        } catch (\PDOException $e) {
            // Log ou tratar o erro conforme necessário
            return false;
        }
    }

    public function buscarPedidoPorId($pedido_id)
    {
        $query = "SELECT * FROM pedidos WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$pedido_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarStatusPedido($id, $status)
    {
        $query = "UPDATE pedidos SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function excluirPedido($id)
    {
        $query = "DELETE FROM pedidos WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function obterCodigo()
    {
        $query = "SELECT MAX(id) as max_id FROM pedidos";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['max_id'];
    }

}

