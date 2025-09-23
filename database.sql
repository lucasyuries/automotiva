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
        'https://m.media-amazon.com/images/I/518AI2DjHsL._AC_SX522_.jpg' AS imagem_url
    UNION ALL
    SELECT
        'Pretinho para Pneus',
        'Acabamento acetinado e duradouro.',
        49.90,
        'https://m.media-amazon.com/images/I/5113Ij52kRL.__AC_SX300_SY300_QL70_ML2_.jpg'
    UNION ALL
    SELECT
        'Shampoo pH Neutro',
        'Limpeza segura sem agredir a proteção.',
        39.90,
        'https://m.media-amazon.com/images/I/613IR7ZkFTL.__AC_SX300_SY300_QL70_ML2_.jpg'
    UNION ALL
    SELECT
        'Limpador de Interiores',
        'Limpa e protege plásticos, vinil e borracha.',
        55.00,
        'https://m.media-amazon.com/images/I/615lQB1DxoL._AC_SX522_.jpg'
    UNION ALL
    SELECT
        'Restaurador de Plásticos',
        'Restaura a cor e o brilho de plásticos externos.',
        65.90,
        'https://m.media-amazon.com/images/I/51HbfCMkyJL.__AC_SX300_SY300_QL70_ML2_.jpg'
    UNION ALL
    SELECT
        'Toalha de Secagem Magnética',
        'Super absorvente, para uma secagem rápida e sem riscos.',
        79.90,
        'https://m.media-amazon.com/images/I/71Hn5L6AIEL.__AC_SX300_SY300_QL70_ML2_.jpg'
    UNION ALL
    SELECT
        'Aplicador de Espuma',
        'Ideal para aplicação de ceras e selantes.',
        15.00,
        'https://m.media-amazon.com/images/I/71XiIg16vuL._AC_SX522_.jpg'
    UNION ALL
    SELECT
        'Escova para Rodas',
        'Design ergonômico para limpeza de rodas e pneus.',
        45.00,
        'https://m.media-amazon.com/images/I/61v+V75emjL._AC_SX300_SY300_.jpg'
    UNION ALL
    SELECT
        'Kit de Pincéis para Detalhamento',
        'Pincéis de cerdas macias para limpeza de áreas delicadas.',
        99.90,
        'https://m.media-amazon.com/images/I/61tc0A9gAVL.__AC_SY300_SX300_QL70_ML2_.jpg'
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM produtos);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_nascimento DATE,
    endereco TEXT,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidos`
--

CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valor_total DECIMAL(10, 2) NOT NULL,
    metodo_pagamento ENUM('cartao', 'vista') NOT NULL,
    status VARCHAR(50) DEFAULT 'Pendente',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedido_itens`
--

CREATE TABLE IF NOT EXISTS pedido_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_produto INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedidos(id),
    FOREIGN KEY (id_produto) REFERENCES produtos(id)
) ENGINE=InnoDB;
