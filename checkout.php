<?php
session_start();
require_once 'config.php';

// 1. Proteger a página: verificar se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
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
    <style>
        /* Estilos específicos para o checkout, alinhados com o design do site */
        .checkout-section {
            padding: 8rem 0 4rem;
            min-height: 80vh;
            display: flex;
            align-items: flex-start; /* Alinha no topo */
            justify-content: center;
        }
        .checkout-wrapper {
            background-color: var(--card-background);
            padding: 3rem;
            border-radius: 8px;
            width: 100%;
            max-width: 900px; /* Aumenta a largura para acomodar as duas colunas */
            border-top: 5px solid var(--primary-color);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            display: flex;
            gap: 3rem;
            flex-wrap: wrap; /* Permite quebrar em telas menores */
        }
        .checkout-form, .order-summary {
            flex: 1; /* Permite que ambos os containers cresçam */
        }
        .checkout-form {
            min-width: 300px; /* Largura mínima para o formulário */
        }
        .order-summary {
            background-color: #111; /* Um pouco mais escuro para destacar */
            padding: 2rem;
            border-radius: 8px;
            min-width: 280px;
        }
        h1, h2, h3, h4 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            color: #ccc;
            padding-bottom: 1rem;
            border-bottom: 1px solid #333;
        }
        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 1.5rem;
            color: #fff;
        }
        .cta-button {
            width: 100%;
            margin-top: 2rem;
            font-size: 1.1rem;
        }
        /* Responsividade */
        @media (max-width: 768px) {
            .checkout-wrapper {
                flex-direction: column;
                padding: 2rem;
            }
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
                    <?php if (isset($_SESSION['id_usuario'])): ?>
                        <li><a href="logout.php?action=logout">Sair</a></li>
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
                            <input type="text" list="payment-methods" id="metodo_pagamento" name="metodo_pagamento" required />
                            <datalist id="payment-methods">
                                <option value="À Vista (Boleto/Pix)">
                                <option value="Cartão de Crédito">
                            </datalist>
                        </div>
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

                    <button type="submit" form="checkout-form" class="cta-button">Confirmar e Pagar</button>
                </aside>

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