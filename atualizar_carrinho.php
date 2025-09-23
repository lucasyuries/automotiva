<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? null;
$product_id = $_POST['product_id'] ?? null;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

switch ($action) {
    case 'add':
        if ($product_id) {
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity']++;
            } else {
                try {
                    $stmt = $pdo->prepare("SELECT id, nome, preco, imagem_url FROM produtos WHERE id = :id");
                    $stmt->execute(['id' => $product_id]);
                    $product = $stmt->fetch();
                    if ($product) {
                        $_SESSION['cart'][$product_id] = [
                            "id"       => $product['id'],
                            "name"     => $product['nome'],
                            "price"    => $product['preco'],
                            "image"    => $product['imagem_url'],
                            "quantity" => 1
                        ];
                    }
                } catch (PDOException $e) {
                    // Tratar erro
                }
            }
        }
        break;

    case 'increase':
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']++;
        }
        break;

    case 'decrease':
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']--;
            if ($_SESSION['cart'][$product_id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$product_id]);
            }
        }
        break;

    case 'remove':
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
        break;

    case 'clear':
        $_SESSION['cart'] = [];
        break;
}

$total = 0;
$cart_html = '';

if (empty($_SESSION['cart'])) {
    $cart_html = '<p>Seu carrinho está vazio.</p>';
} else {
    foreach ($_SESSION['cart'] as $id => $item) {
        $total += $item['price'] * $item['quantity'];
        $item_price_formatted = number_format($item['price'], 2, ',', '.');
        
        $cart_html .= <<<HTML
        <div class="cart-item">
            <img src="{$item['image']}" alt="{$item['name']}">
            <div class="cart-item-info">
                <span>{$item['name']}</span><br>
                <small>R$ {$item_price_formatted}</small>
            </div>
            <div class="quantity-controls">
                <button class="cart-action-btn" data-product-id="{$id}" data-action="decrease">-</button>
                <span>{$item['quantity']}</span>
                <button class="cart-action-btn" data-product-id="{$id}" data-action="increase">+</button>
            </div>
            <button class="cart-action-btn remove-btn" data-product-id="{$id}" data-action="remove" title="Remover item">&times;</button>
        </div>
HTML;
    }

    $total_formatted = number_format($total, 2, ',', '.');
    $cart_html .= <<<HTML
    <div class="cart-total">
        Total: R$ {$total_formatted}
    </div>
    <div class="cart-actions">
        <a href="checkout.php" class="cta-button checkout-btn">Finalizar Compra</a>
        <button class="cta-button empty-cart-btn cart-action-btn" data-action="clear">Limpar Carrinho</button>
    </div>
HTML;
}

echo json_encode([
    'cart_html' => $cart_html,
    'item_count' => count($_SESSION['cart'])
]);

exit();
?>