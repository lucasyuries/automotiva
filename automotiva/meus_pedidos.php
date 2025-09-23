<?php
session_start();
require_once 'config.php';

// 1. SEGURANÇA: Redireciona o usuário para o login se ele não estiver logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// 2. BUSCA PRINCIPAL: Pega todos os pedidos do usuário logado
try {
    $sql_pedidos = "SELECT id, data_pedido, valor_total, status FROM pedidos WHERE id_usuario = :id_usuario ORDER BY data_pedido DESC";
    $stmt_pedidos = $pdo->prepare($sql_pedidos);
    $stmt_pedidos->execute(['id_usuario' => $id_usuario]);
    $pedidos = $stmt_pedidos->fetchAll();
} catch (PDOException $e) {
    die("ERRO: Não foi possível buscar os pedidos. " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos - Automotiva</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header class="header">
        <div class="container">
            <a href="index.php" class="logo">Automotiva</a>
            <nav class="nav">
                <ul class="nav-menu">
                    <li><a href="index.php#produtos">Produtos</a></li>
                    <?php if (isset($_SESSION['id_usuario'])): ?>
                        <li><a href="meus_pedidos.php">Meus Pedidos</a></li>
                        <li><a href="logout.php?action=logout">Sair</a></li>
                        <li class="user-greeting">Olá, <?php echo htmlspecialchars(explode(' ', $_SESSION['nome_usuario'])[0]); ?></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                    <li><a href="carrinho.php" class="cart-icon"><img src="https://img.icons8.com/ios-glyphs/30/ffffff/shopping-cart.png" alt="Carrinho de Compras"/></a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="orders-section">
            <div class="container">
                <h2>Meus Pedidos</h2>

                <?php if (empty($pedidos)): ?>
                    <p class="empty-message">Você ainda não fez nenhum pedido.</p>
                <?php else: ?>
                    <?php foreach ($pedidos as $pedido): ?>
                        <article class="order-card">
                            <div class="order-header">
                                <div>
                                    <h3>Pedido #<?= htmlspecialchars($pedido['id']) ?></h3>
                                    <p>Realizado em: <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>
                                </div>
                                <div class="order-summary">
                                    <span class="order-status status-<?= strtolower(htmlspecialchars($pedido['status'])) ?>">
                                        <?= htmlspecialchars($pedido['status']) ?>
                                    </span>
                                    <span class="order-total">Total: R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></span>
                                </div>
                            </div>

                            <div class="order-items-list">
                                <?php
                                try {
                                    $sql_itens = "SELECT pi.quantidade, pi.preco_unitario, pr.nome, pr.imagem_url 
                                                  FROM pedido_itens pi 
                                                  JOIN produtos pr ON pi.id_produto = pr.id 
                                                  WHERE pi.id_pedido = :id_pedido";
                                    $stmt_itens = $pdo->prepare($sql_itens);
                                    $stmt_itens->execute(['id_pedido' => $pedido['id']]);
                                    $itens = $stmt_itens->fetchAll();

                                    foreach ($itens as $item):
                                ?>
                                    <div class="order-item">
                                        <img src="<?= htmlspecialchars($item['imagem_url']) ?>" alt="<?= htmlspecialchars($item['nome']) ?>">
                                        <div class="order-item-details">
                                            <span class="item-name"><?= htmlspecialchars($item['nome']) ?></span>
                                            <span class="item-qty-price">
                                                <?= $item['quantidade'] ?> un. x R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php
                                    endforeach;
                                } catch (PDOException $e) {
                                    echo "<p>Erro ao buscar itens do pedido.</p>";
                                }
                                ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Automotiva. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>