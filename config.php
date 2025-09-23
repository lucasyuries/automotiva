<?php
// Arquivo de configuração do banco de dados

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Usuário padrão do XAMPP
define('DB_PASSWORD', '');     // Senha padrão do XAMPP é vazia
define('DB_NAME', 'automotiva_db');
define('DB_CHARSET', 'utf8mb4');

// --- Configuração da conexão PDO ---

// DSN (Data Source Name) - define a conexão com o banco
$dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

// Opções para a conexão PDO para garantir consistência e segurança
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna os resultados como arrays associativos
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desativa a emulação de prepared statements para mais segurança
];

try {
    // Tenta criar a instância do PDO para conectar ao banco de dados
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
} catch (\PDOException $e) {
    // Se a conexão falhar, exibe uma mensagem de erro e interrompe o script
    // Em um ambiente de produção, seria ideal logar este erro em vez de exibi-lo.
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// A antiga conexão $link foi removida. Agora, usaremos a variável $pdo em todo o projeto.
?>