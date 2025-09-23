<?php
// Arquivo de configuração do banco de dados

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'automotiva_db');
define('DB_CHARSET', 'utf8mb4');

// Opções do PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// DSN (Data Source Name)
$dsn = "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

try {
    // Tenta conectar ao banco de dados usando PDO
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
} catch (\PDOException $e) {
    // Se a conexão falhar, exibe um erro
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// A variável $link antiga não é mais necessária se padronizarmos
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($link === false){
    die("ERRO: Não foi possível conectar com mysqli. " . mysqli_connect_error());
}
?>