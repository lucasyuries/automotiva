<?php
session_start();

// Protege a página: só pode ser acessada se um pedido foi finalizado com sucesso
if (!isset($_SESSION['ultimo_pedido_id'])) {
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
    <link rel="stylesheet" href="styles.css">
    <style>
        .success-section {
            padding-top: 120px;
            text-align: center;
            min-height: 70vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-container {
            background-color: #fff;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 600px;
        }
        .success-container h1 {
            color: #28a745; /* Verde sucesso */
            margin-bottom: 1rem;
        }
        .success-container p {
            font-size: 1.1rem;
            color: #333;
            line-height: 1.6;
        }
        .order-number {
            font-weight: bold;
            color: #c00000;
            font-size: 1.2rem;
        }
        .cta-button {
            margin-top: 2rem;
            display: inline-block;
            padding: 0.8rem 2rem;
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
                    <li><a href="logout.php">Sair</a></li>
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
                    <p>Enviamos um e-mail de confirmação com todos os detalhes da sua compra.</p>
                    <a href="index.php" class="cta-button">Voltar à Página Inicial</a>
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
