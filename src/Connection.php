<?php

namespace App;

use PDO;
use PDOException;

class Connection
{
    private $host = 'mysql';
    private $dbname = 'ecommerce';
    private $user = 'root';
    private $pass = '1234';

    public $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Erro de conexão com o banco de dados: ' . $e->getMessage();
        }
    }
}
