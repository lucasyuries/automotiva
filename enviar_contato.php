<?php
session_start();
require_once 'config.php'; // Inclui a conexão com o banco ($pdo)

// Verifica se o formulário foi enviado usando o método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Pega os dados do formulário e remove espaços em branco extras
    $nome = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mensagem = trim($_POST['message']);

    // Validação simples para garantir que os campos não estão vazios
    if (!empty($nome) && !empty($email) && !empty($mensagem) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        try {
            // Prepara a instrução SQL para inserir os dados de forma segura
            $sql = "INSERT INTO contatos (nome, email, mensagem) VALUES (:nome, :email, :mensagem)";
            $stmt = $pdo->prepare($sql);

            // Associa os parâmetros da instrução SQL com as variáveis
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':mensagem', $mensagem, PDO::PARAM_STR);

            // Executa a instrução
            if ($stmt->execute()) {
                // Se a inserção for bem-sucedida, cria uma mensagem de sucesso na sessão
                $_SESSION['contact_status'] = 'success';
            } else {
                // Se falhar, cria uma mensagem de erro
                $_SESSION['contact_status'] = 'error';
            }

        } catch (PDOException $e) {
            // Em caso de erro de banco de dados, cria uma mensagem de erro
            $_SESSION['contact_status'] = 'error';
            // Em um ambiente de produção, seria bom logar o erro: error_log($e->getMessage());
        }

    } else {
        // Se a validação dos campos falhar, cria uma mensagem de erro
        $_SESSION['contact_status'] = 'validation_error';
    }

    // Redireciona o usuário de volta para a seção de contato da página inicial
    header("Location: index.php#contato");
    exit();

} else {
    // Se alguém tentar acessar este arquivo diretamente, redireciona para a página inicial
    header("Location: index.php");
    exit();
}
?>