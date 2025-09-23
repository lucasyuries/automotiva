<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras - Automotiva</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .cart-section {
            padding-top: 120px;
            padding-bottom: 4rem;
        }
        .cart-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            align-items: start;
        }
        .products-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .product-card {
            text-align: center;
        }
        .product-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        .cart-summary {
            background-color: var(--card-background);
            padding: 2rem;
            border-radius: 8px;
            position: sticky;
            top: 120px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .cart-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 1rem;
        }
        .cart-item-info {
            flex-grow: 1;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
        }
        .quantity-controls button {
            background: #444;
            color: #fff;
            border: none;
            width: 30px;
            height: 30px;
            cursor: pointer;
            border-radius: 50%;
            font-size: 1.2rem;
            line-height: 1;
        }
        .quantity-controls span {
            padding: 0 1rem;
            font-weight: 500;
        }
        .remove-btn {
            background: none;
            border: none;
            color: #E50914;
            cursor: pointer;
            font-size: 1.5rem;
            margin-left: 1rem;
        }
        .cart-total {
            margin-top: 1.5rem;
            font-size: 1.2rem;
            font-weight: bold;
            text-align: right;
            border-top: 1px solid #333;
            padding-top: 1rem;
        }
        .empty-cart-btn {
            background-color: #555;
            margin-top: 1rem;
            width: 100%;
        }
        .empty-cart-btn:hover {
            background-color: #777;
        }
        .checkout-btn {
            background-color: var(--primary-color);
            margin-top: 1rem;
            width: 100%;
        }
        .checkout-btn:hover {
            background-color: #ff0a16;
        }
        .cart-actions {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        @media (max-width: 992px) {
            .cart-container {
                grid-template-columns: 1fr;
            }
            .cart-summary {
                position: static;
                margin-top: 2rem;
            }
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="container">
            <a href="index.php" class="logo">Automotiva</a>
            <nav class="nav">
                <button class="nav-toggle" aria-label="Abrir menu">
                    <span class="hamburger"></span>
                </button>
                <ul class="nav-menu">
                    <li><a href="index.php#servicos">Serviços</a></li>
                    <li><a href="index.php#produtos">Produtos</a></li>
                    <?php if (isset($_SESSION['id_usuario'])): ?>
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
        <section class="cart-section">
            <div class="container">
                <div class="cart-container" id="cart-page-container">
                    <div>
                        <h2>Nossos Produtos</h2>
                        <div class="products-list">
                            <?php
                            try {
                                $sql = "SELECT id, nome, descricao, preco, imagem_url FROM produtos ORDER BY nome ASC";
                                $stmt = $pdo->query($sql);
                                
                                if($stmt->rowCount() > 0){
                                    while($row = $stmt->fetch()){
                                        echo '<article class="product-card">';
                                        echo '    <img src="' . htmlspecialchars($row['imagem_url']) . '" alt="' . htmlspecialchars($row['nome']) . '">';
                                        echo '    <h3>' . htmlspecialchars($row['nome']) . '</h3>';
                                        echo '    <p>' . htmlspecialchars($row['descricao']) . '</p>';
                                        echo '    <span class="price">R$ ' . number_format($row['preco'], 2, ',', '.') . '</span>';
                                        echo '    <button class="cta-button cart-action-btn" data-product-id="' . $row['id'] . '" data-action="add">Adicionar ao Carrinho</button>';
                                        echo '</article>';
                                    }
                                } else {
                                    echo "<p>Nenhum produto encontrado.</p>";
                                }
                            } catch (PDOException $e) {
                                echo "ERRO: Não foi possível buscar os produtos. " . $e->getMessage();
                            }
                            ?>
                        </div>
                    </div>

                    <aside class="cart-summary">
                        <h3>Resumo do Carrinho</h3>
                        <div id="cart-summary-container">
                            <?php if (empty($_SESSION['cart'])): ?>
                                <p>Seu carrinho está vazio.</p>
                            <?php else: ?>
                                <?php
                                $total = 0;
                                foreach ($_SESSION['cart'] as $id => $item):
                                    $total += $item['price'] * $item['quantity'];
                                ?>
                                    <div class="cart-item">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                        <div class="cart-item-info">
                                            <span><?= htmlspecialchars($item['name']) ?></span><br>
                                            <small>R$ <?= number_format($item['price'], 2, ',', '.') ?></small>
                                        </div>
                                        <div class="quantity-controls">
                                            <button class="cart-action-btn" data-product-id="<?= $id ?>" data-action="decrease">-</button>
                                            <span><?= $item['quantity'] ?></span>
                                            <button class="cart-action-btn" data-product-id="<?= $id ?>" data-action="increase">+</button>
                                        </div>
                                        <button class="cart-action-btn remove-btn" data-product-id="<?= $id ?>" data-action="remove" title="Remover item">&times;</button>
                                    </div>
                                <?php endforeach; ?>
                                <div class="cart-total">
                                    Total: R$ <?= number_format($total, 2, ',', '.') ?>
                                </div>
                                <div class="cart-actions">
                                    <a href="checkout.php" class="cta-button checkout-btn">Finalizar Compra</a>
                                    <button class="cta-button empty-cart-btn cart-action-btn" data-action="clear">Limpar Carrinho</button>
                                </div>
                            <?php endif; ?>
                        </div>
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

    <script src="script.js"></script>
</body>
</html>