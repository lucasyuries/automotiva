<?php
session_start();
require_once 'config.php';

// 1. Segurança: Verificar se o método é POST e se o usuário está logado
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

// 2. Segurança: Verificar se o carrinho não está vazio
if (empty($_SESSION['cart'])) {
    header('Location: carrinho.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$endereco = $_POST['endereco'];
$data_nascimento = $_POST['data_nascimento'];
$metodo_pagamento = $_POST['metodo_pagamento'];

// Inicia uma transação para garantir a integridade dos dados
$pdo->beginTransaction();

try {
    // 3. Atualiza os dados do usuário (endereço e data de nascimento) se foram alterados
    $stmt = $pdo->prepare("UPDATE usuarios SET endereco = ?, data_nascimento = ? WHERE id = ?");
    $stmt->execute([$endereco, $data_nascimento, $id_usuario]);

    // 4. Calcular o valor total do pedido
    $valor_total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $valor_total += $item['price'] * $item['quantity'];
    }

    // 5. Inserir o registro na tabela `pedidos`
    $stmt = $pdo->prepare("INSERT INTO pedidos (id_usuario, valor_total, metodo_pagamento, status) VALUES (?, ?, ?, 'Processando')");
    $stmt->execute([$id_usuario, $valor_total, $metodo_pagamento]);
    $id_pedido = $pdo->lastInsertId(); // Pega o ID do pedido recém-criado

    // 6. Inserir cada item do carrinho na tabela `pedido_itens`
    $stmt_item = $pdo->prepare("INSERT INTO pedido_itens (id_pedido, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $id_produto => $item) {
        $stmt_item->execute([$id_pedido, $id_produto, $item['quantity'], $item['price']]);
    }

    // 7. Se tudo deu certo, comita a transação
    $pdo->commit();

    // 8. Limpa o carrinho da sessão
    unset($_SESSION['cart']);

    // 9. Salva o ID do pedido na sessão para a página de sucesso
    $_SESSION['ultimo_pedido_id'] = $id_pedido;

    // 10. Redireciona para uma página de sucesso
    header('Location: pedido_sucesso.php');
    exit();

} catch (Exception $e) {
    // 11. Se algo deu errado, desfaz a transação (rollback)
    $pdo->rollBack();
    
    // Em um ambiente de produção, você poderia logar o erro: error_log($e->getMessage());
    // E redirecionar para uma página de erro amigável
    die("Ocorreu um erro ao processar seu pedido. Por favor, tente novamente. Erro: " . $e->getMessage());
}
