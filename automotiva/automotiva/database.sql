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
