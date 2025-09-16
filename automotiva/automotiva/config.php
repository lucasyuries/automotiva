<?php
// Arquivo de configuração do banco de dados

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Usuário padrão do XAMPP
define('DB_PASSWORD', ''); // Senha padrão do XAMPP é vazia
define('DB_NAME', 'automotiva_db');

// Tenta conectar ao banco de dados
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verifica a conexão
if($link === false){
    die("ERRO: Não foi possível conectar. " . mysqli_connect_error());
}
?>
