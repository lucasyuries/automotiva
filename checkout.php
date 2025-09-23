<?php
session_start();
require_once 'config.php';

// 1. Proteger a página: verificar se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    // Salva a intenção de ir para o checkout para redirecionar após o login
    $_SESSION['redirect_to_checkout'] = true;
    header('Location: login.php');
    exit();
}

// 2. Verificar se o carrinho não está vazio
if (empty($_SESSION['cart'])) {
    header('Location: carrinho.php');
    exit();
}

// 3. Buscar os dados do usuário logado para preencher o formulário
$id_usuario = $_SESSION['id_usuario'];
$stmt = $pdo->prepare("SELECT nome, email, endereco, data_nascimento FROM usuarios WHERE id = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Se o formulário não tiver dados do usuário (caso raro), redireciona
if (!$usuario) {
    // Limpa a sessão para forçar novo login
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
    <link rel="stylesheet" href="styles.css">
    <style>
        .checkout-section {
            padding-top: 120px;
            padding-bottom: 4rem;
        }
        .checkout-container {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 3rem;
            align-items: start;
        }
        .checkout-form, .order-summary {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input:read-only {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }
        .order-summary h3 {
            margin-top: 0;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            color: #555;
        }
        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
        .cta-button {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="container">
            <a href="index.php" class="logo">Automotiva</a>
            <nav class="nav">
                <ul class="nav-menu">
                    <li><a href="index.php#produtos">Produtos</a></li>
                    <li><a href="index.php#contato">Contato</a></li>
                    <?php if (isset($_SESSION['id_usuario'])): ?>
                        <li><a href="logout.php">Sair</a></li>
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
            <div class="container">
                <div class="checkout-container">
                    <div class="checkout-form">
                        <h2>Informações para Entrega e Pagamento</h2>
                        <form id="checkout-form" action="finalizar_pedido.php" method="POST">
                            
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
                                <label for="metodo_pagamento">Forma de Pagamento</label>
                                <select id="metodo_pagamento" name="metodo_pagamento" required>
                                    <option value="vista">À Vista (Boleto/Pix)</option>
                                    <option value="cartao">Cartão de Crédito</option>
                                </select>
                            </div>
                            
                            <!-- O botão de submit fica no resumo do pedido -->
                        </form>
                    </div>

                    <aside class="order-summary">
                        <h3>Resumo do Pedido</h3>
                        <?php
                        $total = 0;
                        foreach ($_SESSION['cart'] as $item):
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                        <div class="summary-item">
                            <span><?= htmlspecialchars($item['name']) ?> (x<?= $item['quantity'] ?>)</span>
                            <span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                        </div>
                        <?php endforeach; ?>

                        <div class="summary-total">
                            <span>Total</span>
                            <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
                        </div>

                        <button type="submit" form="checkout-form" class="cta-button" style="margin-top: 2rem;">Confirmar e Pagar</button>
                    </aside>
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
