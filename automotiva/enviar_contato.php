<?php
session_start();
require_once 'config.php'; // Inclui a conexão com o banco ($pdo)

// Verifica se o formulário foi enviado usando o método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Pega os dados do formulário
    $nome = trim($_POST['name']);
    $email = trim($_POST['email']);
    $telefone = !empty($_POST['telefone']) ? trim($_POST['telefone']) : null; // Pega o telefone, se existir
    $mensagem = trim($_POST['message']);

    // Validação
    if (!empty($nome) && !empty($email) && !empty($mensagem) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        try {
            // Prepara a instrução SQL com o novo campo 'telefone'
            $sql = "INSERT INTO contatos (nome, email, telefone, mensagem) VALUES (:nome, :email, :telefone, :mensagem)";
            $stmt = $pdo->prepare($sql);

            // Associa os parâmetros
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR); // Associa o novo parâmetro
            $stmt->bindParam(':mensagem', $mensagem, PDO::PARAM_STR);

            // Executa
            if ($stmt->execute()) {
                $_SESSION['contact_status'] = 'success';
            } else {
                $_SESSION['contact_status'] = 'error';
            }

        } catch (PDOException $e) {
            $_SESSION['contact_status'] = 'error';
        }

    } else {
        $_SESSION['contact_status'] = 'validation_error';
    }

    // Redireciona o usuário de volta para a seção de contato
    header("Location: index.php#contato");
    exit();

} else {
    // Redireciona para a página inicial
    header("Location: index.php");
    exit();
}
?>