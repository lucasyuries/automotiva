<?php
session_start();
require_once 'config.php';

// Protege a página: verificar se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['redirect_to_checkout'] = true;
    header('Location: login.php');
    exit();
}

// Verificar se o carrinho não está vazio
if (empty($_SESSION['cart'])) {
    header('Location: carrinho.php');
    exit();
}

// Buscar os dados do usuário logado para preencher o formulário
$id_usuario = $_SESSION['id_usuario'];
$stmt = $pdo->prepare("SELECT nome, email, endereco, data_nascimento FROM usuarios WHERE id = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - Automotiva</title>
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
                    <li><a href="carrinho.php" class="cart-icon"><img src="https://img.icons8.com/ios-glyphs/30/ffffff/shopping-cart.png" alt="Carrinho"/></a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="checkout-section">
            <div class="checkout-wrapper">
                
                <div class="checkout-form">
                    <h2>Finalizar Pedido</h2>
                    <form id="checkout-form" action="finalizar_pedido.php" method="POST">
                        <input type="hidden" name="metodo_pagamento" value="pix">
                        
                        <h4>Seus Dados</h4>
                        <div class="form-group">
                            <label for="nome">Nome Completo</label>
                            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="data_nascimento">Data de Nascimento</label>
                            <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($usuario['data_nascimento']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="endereco">Endereço de Entrega</label>
                            <input type="text" id="endereco" name="endereco" value="<?= htmlspecialchars($usuario['endereco']) ?>" placeholder="Rua, Nº, Bairro, Cidade - Estado" required>
                        </div>

                        <h4>Pagamento</h4>
                        <div class="form-group">
                            <label>Forma de Pagamento</label>
                            <input type="text" value="PIX" readonly>
                        </div>
                    </form>
                </div>

                <aside class="order-summary">
                    <h3>Resumo do Pedido</h3>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $item){
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                        echo '<div class="summary-item"><span>' . htmlspecialchars($item['name']) . ' (x' . $item['quantity'] . ')</span><span>R$ ' . number_format($subtotal, 2, ',', '.') . '</span></div>';
                    }
                    ?>
                    <div class="summary-total">
                        <span>Total</span>
                        <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
                    </div>

                    <button type="button" id="openPixModal" class="cta-button">Confirmar e Pagar com PIX</button>
                </aside>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Automotiva. Todos os direitos reservados.</p>
        </div>
    </footer>

    <div id="pixModal" class="modal">
        <div class="pix-modal-content">
            <span class="close-modal" id="closePixModal">&times;</span>
            <h3>Pague com PIX para concluir</h3>
            <p>Escaneie o QR Code com o app do seu banco:</p>
            <img src="pix-qrcode.png" alt="QR Code PIX">
            <p>Ou use o PIX Copia e Cola:</p>
            <div class="pix-key-wrapper">
                <span id="pixKey">00020126580014br.gov.bcb.pix0136123e4567-e89b-12d3-a456-4266141740005204000053039865802BR5913NOME DO LOJISTA6009SAO PAULO62070503***6304E2A4</span>
            </div>
            <button type="button" class="copy-button" id="copyPixKey">Copiar Chave</button>

            <button type="submit" form="checkout-form" class="cta-button">Já Paguei, Finalizar Pedido</button>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>