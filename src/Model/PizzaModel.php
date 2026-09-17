<?php

namespace App\Model;

use Config\Database;
use PDO;

class PizzaModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function buscarTodas(): array {
        $stmt = $this->db->query("SELECT * FROM pizzas ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function buscarPorId(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM pizzas WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function criar(array $dados): int {
        $sql = "INSERT INTO pizzas (nome, ingredientes, preco, tamanho) VALUES (:nome, :ingredientes, :preco, :tamanho)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nome' => $dados['nome'],
            ':ingredientes' => $dados['ingredientes'],
            ':preco' => $dados['preco'],
            ':tamanho' => $dados['tamanho'] ?? 'Média'
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function atualizar(int $id, array $dados): bool {
        $sql = "UPDATE pizzas SET nome = :nome, ingredientes = :ingredientes, preco = :preco, tamanho = :tamanho WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nome' => $dados['nome'],
            ':ingredientes' => $dados['ingredientes'],
            ':preco' => $dados['preco'],
            ':tamanho' => $dados['tamanho'] ?? 'Média'
        ]);
    }

    public function deletar(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM pizzas WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}