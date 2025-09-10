-- Arquivo de setup do banco de dados para o projeto Automotiva

-- É recomendado rodar este script através do phpMyAdmin ou de um cliente MySQL.

-- Cria o banco de dados se ele não existir, com o conjunto de caracteres correto.
CREATE DATABASE IF NOT EXISTS automotiva_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Seleciona o banco de dados para as operações seguintes.
USE automotiva_db;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    imagem_url VARCHAR(255) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Inserindo dados de exemplo na tabela `produtos`
--

-- Insere os produtos apenas se a tabela estiver vazia para evitar duplicação.
INSERT INTO produtos (nome, descricao, preco, imagem_url)
SELECT * FROM (
    SELECT
        'Cera de Carnaúba Premium' AS nome,
        'Brilho profundo e proteção para a pintura.' AS descricao,
        89.90 AS preco,
        'https://via.placeholder.com/300x200.png/111/FFF?text=Cera+Premium' AS imagem_url
    UNION ALL
    SELECT
        'Pretinho para Pneus',
        'Acabamento acetinado e duradouro.',
        49.90,
        'https://via.placeholder.com/300x200.png/111/FFF?text=Limpa+Pneus'
    UNION ALL
    SELECT
        'Shampoo pH Neutro',
        'Limpeza segura sem agredir a proteção.',
        39.90,
        'https://via.placeholder.com/300x200.png/111/FFF?text=Shampoo+Automotivo'
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM produtos);
