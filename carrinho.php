<?php
session_start();
require_once 'config.php';

// Lógica para ATUALIZAR a quantidade do produto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$product_id])) {
        if ($action == 'increase') {
            $_SESSION['cart'][$product_id]['quantity']++;
        } elseif ($action == 'decrease') {
            $_SESSION['cart'][$product_id]['quantity']--;
            if ($_SESSION['cart'][$product_id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }
    header("Location: carrinho.php");
    exit;
}

// Lógica para REMOVER produto do carrinho
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header("Location: carrinho.php");
    exit;
}

// Lógica para adicionar produto ao carrinho
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Adiciona o produto ao carrinho (ou incrementa a quantidade)
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // Busca informações do produto no banco
        $sql = "SELECT id, nome, preco, imagem_url FROM produtos WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $product_id);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 1) {
                    $product = mysqli_fetch_assoc($result);
                    $_SESSION['cart'][$product_id] = [
                        "id" => $product['id'],
                        "name" => $product['nome'],
                        "price" => $product['preco'],
                        "image" => $product['imagem_url'],
                        "quantity" => 1
                    ];
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    // Redireciona para a mesma página para evitar reenvio do formulário
    header("Location: carrinho.php");
    exit;
}

// Lógica para limpar o carrinho
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
    header("Location: carrinho.php");
    exit;
}

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
            padding-top: 120px; /* Espaço para o header fixo */
        }
        .cart-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            align-items: start;
        }
        .products-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        .product-card {
            text-align: center;
        }
        .product-card img {
            width: 100%;
            height: 200px; /* Altura fixa para todas as imagens */
            object-fit: cover; /* Garante que a imagem cubra a área sem distorcer */
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
            background: #ddd;
            border: none;
            width: 30px;
            height: 30px;
            cursor: pointer;
            border-radius: 50%;
        }
        .quantity-controls span {
            padding: 0 1rem;
        }
        .remove-btn {
            background: none;
            border: none;
            color: #c00000;
            cursor: pointer;
            font-size: 1.2rem;
            margin-left: 1rem;
        }
        .cart-total {
            margin-top: 1.5rem;
            font-size: 1.2rem;
            font-weight: bold;
            text-align: right;
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
            background-color: #c00000;
            margin-top: 1rem;
            width: 100%;
        }
        .checkout-btn:hover {
            background-color: #a00000;
        }
        .cart-actions {
            margin-top: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* --- Responsividade para o Carrinho --- */
        @media (max-width: 992px) {
            .cart-container {
                grid-template-columns: 1fr; /* Coluna única em telas menores */
            }
            .cart-summary {
                position: static; /* Remove o comportamento fixo */
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
                    <li><a href="index.php#sobre">Sobre Nós</a></li>
                    <li><a href="index.php#depoimentos">Depoimentos</a></li>
                    <li><a href="index.php#contato">Contato</a></li>
                    <?php if (isset($_SESSION['id_usuario'])): ?>
                        <li><a href="logout.php">Sair</a></li>
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
                <h2>Nossos Produtos</h2>
                <div class="cart-container">
                    <div class="products-list">
                        <?php
                        // Busca todos os produtos no banco de dados
                        $sql = "SELECT id, nome, descricao, preco, imagem_url FROM produtos ORDER BY nome ASC";
                        if($result = mysqli_query($link, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_array($result)){
                                    echo '<article class="product-card">';
                                    echo '    <img src="' . htmlspecialchars($row['imagem_url']) . '" alt="' . htmlspecialchars($row['nome']) . '">';
                                    echo '    <h3>' . htmlspecialchars($row['nome']) . '</h3>';
                                    echo '    <p>' . htmlspecialchars($row['descricao']) . '</p>';
                                    echo '    <span class="price">R$ ' . number_format($row['preco'], 2, ',', '.') . '</span>';
                                    echo '    <form method="post" action="carrinho.php">';
                                    echo '        <input type="hidden" name="product_id" value="' . $row['id'] . '">';
                                    echo '        <button type="submit" name="add_to_cart" class="cta-button">Adicionar ao Carrinho</button>';
                                    echo '    </form>';
                                    echo '</article>';
                                }
                                mysqli_free_result($result);
                            } else{
                                echo "<p>Nenhum produto encontrado.</p>";
                            }
                        } else{
                            echo "ERRO: Não foi possível executar $sql. " . mysqli_error($link);
                        }
                        ?>
                    </div>

                    <aside class="cart-summary">
                        <h3>Resumo do Carrinho</h3>
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
                                        <span>R$ <?= number_format($item['price'], 2, ',', '.') ?></span>
                                    </div>
                                    <div class="quantity-controls">
                                        <form method="post" action="carrinho.php" style="display: inline;">
                                            <input type="hidden" name="product_id" value="<?= $id ?>">
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" name="update_quantity">-</button>
                                        </form>
                                        <span><?= $item['quantity'] ?></span>
                                        <form method="post" action="carrinho.php" style="display: inline;">
                                            <input type="hidden" name="product_id" value="<?= $id ?>">
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" name="update_quantity">+</button>
                                        </form>
                                    </div>
                                    <form method="post" action="carrinho.php">
                                        <input type="hidden" name="product_id" value="<?= $id ?>">
                                        <button type="submit" name="remove_item" class="remove-btn" title="Remover item">&times;</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                            <div class="cart-total">
                                Total: R$ <?= number_format($total, 2, ',', '.') ?>
                            </div>
                            <div class="cart-actions">
                                <a href="checkout.php" class="cta-button checkout-btn">Finalizar Compra</a>
                                <form method="post" action="carrinho.php">
                                    <button type="submit" name="clear_cart" class="cta-button empty-cart-btn">Limpar Carrinho</button>
                                </form>
                            </div>
                        <?php endif; ?>
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
