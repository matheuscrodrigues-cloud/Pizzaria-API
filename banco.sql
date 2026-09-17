CREATE DATABASE IF NOT EXISTS pizzaria_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE pizzaria_db;

CREATE TABLE IF NOT EXISTS pizzas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    ingredientes TEXT NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO pizzas (nome, ingredientes, preco) VALUES 
('Calabresa', 'Molho de tomate, mussarela, calabresa e orégano', 45.00),
('Quatro Queijos', 'Mussarela, gorgonzola, parmesão e catupiry', 52.50);