<?php
// Inclui o autoload do Composer para carregar a biblioteca phpdotenv
require_once 'vendor/autoload.php';

// Carrega as variáveis de ambiente do arquivo .env que você criou
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// --- Agora, as configurações usam as variáveis do .env ---

// DSN (Data Source Name) - define a conexão com o banco
// Ele pega os valores de DB_SERVER, DB_NAME e DB_CHARSET do seu arquivo .env
$dsn = "mysql:host=" . $_ENV['DB_SERVER'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=" . $_ENV['DB_CHARSET'];

// Opções para a conexão PDO (isso continua igual)
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Tenta criar a conexão PDO usando as credenciais do seu arquivo .env
    $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $options);
} catch (\PDOException $e) {
    // Se a conexão falhar, exibe uma mensagem de erro e interrompe o script
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}