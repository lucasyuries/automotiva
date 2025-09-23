<?php
session_start();
require_once 'config.php'; // Adicionado para buscar o nome do usuário para o header

// Protege a página: só pode ser acessada se um pedido foi finalizado com sucesso
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['ultimo_pedido_id'])) {
    header('Location: index.php');
    exit();
}

$id_pedido = $_SESSION['ultimo_pedido_id'];

// Limpa a variável de sessão para que a página não seja acessível recarregando
unset($_SESSION['ultimo_pedido_id']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado! - Automotiva</title>
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
                    <li><a href="index.php#servicos">Serviços</a></li>
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
        <section class="success-section">
            <div class="container">
                <div class="success-container">
                    <h1>Pedido Realizado com Sucesso!</h1>
                    <p>Obrigado por comprar na Automotiva! Seu pedido foi recebido e já está sendo processado.</p>
                    <p>O número do seu pedido é: <span class="order-number">#<?= htmlspecialchars($id_pedido) ?></span></p>
                    <p>Você pode acompanhar o status do seu pedido na página "Meus Pedidos".</p>
                    <a href="meus_pedidos.php" class="cta-button">Ver Meus Pedidos</a>
                    <a href="index.php" class="cta-button secondary">Voltar à Página Inicial</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Automotiva. Todos os direitos reservados.</p>
        </div>
    </footer>

</body>
</html>