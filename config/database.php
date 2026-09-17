<?php

namespace Config;

use PDO;
use PDOException;

class Database {
    private static ?PDO $conn = null;

    public static function getConnection(): PDO {
        if (self::$conn === null) {
            // Carrega variáveis do arquivo .env se existirem
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $dbName = $_ENV['DB_NAME'] ?? 'pizzaria_db';
            $username = $_ENV['DB_USER'] ?? 'root';
            $password = $_ENV['DB_PASS'] ?? '';

            try {
                self::$conn = new PDO(
                    "mysql:host={$host};dbname={$dbName};charset=utf8mb4",
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode([
                    "sucesso" => false,
                    "mensagem" => "Erro na conexão com o banco de dados: " . $e->getMessage()
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        return self::$conn;
    }
}