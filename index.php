<?php session_start(); ?>
<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Automotiva - Estética Automotiva de Alta Performance</title>
    <meta name="description" content="Automotiva: especialista em estética automotiva, oferencendo serviços de vitrificação, polimento, higienização e muito mais.">
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
                <button class="nav-toggle" aria-label="Abrir menu">
                    <span class="hamburger"></span>
                </button>
                <ul class="nav-menu">
                    <li><a href="#servicos">Serviços</a></li>
                    <li><a href="#produtos">Produtos</a></li>
                    <li><a href="#sobre">Sobre Nós</a></li>
                    <li><a href="#depoimentos">Depoimentos</a></li>
                    <li><a href="#contato">Contato</a></li>
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
        <section class="hero">
            <div class="container">
                <h1>Cuidado Premium para seu Veículo</h1>
                <p>A excelência em estética automotiva que seu carro merece.</p>
                <a href="#contato" class="cta-button">Agende uma Avaliação</a>
            </div>
        </section>

        <section id="servicos" class="services">
            <div class="container">
                <h2>Nossos Serviços</h2>
                <div class="services-grid">
                    <article class="service-card">
                        <h3>Vitrificação de Pintura</h3>
                        <p>Proteção cerâmica de longa duração que oferece brilho intenso e repele a sujeira.</p>
                    </article>
                    <article class="service-card">
                        <h3>Polimento Técnico</h3>
                        <p>Remoção de riscos e imperfeições, restaurando o brilho original da pintura.</p>
                    </article>
                    <article class="service-card">
                        <h3>Higienização Interna</h3>
                        <p>Limpeza profunda e detalhada do interior do seu veículo, eliminando ácaros e bactérias.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="produtos" class="products">
            <div class="container">
                <h2>Produtos em Destaque</h2>
                <div class="products-grid">
                    <?php
                    // Busca produtos no banco de dados
                    $sql = "SELECT nome, descricao, preco, imagem_url FROM produtos ORDER BY id DESC LIMIT 3";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_array($result)){
                                echo '<article class="product-card">';
                                echo '    <img src="' . htmlspecialchars($row['imagem_url']) . '" alt="' . htmlspecialchars($row['nome']) . '">';
                                echo '    <h3>' . htmlspecialchars($row['nome']) . '</h3>';
                                echo '    <p>' . htmlspecialchars($row['descricao']) . '</p>';
                                echo '    <span class="price">R$ ' . number_format($row['preco'], 2, ',', '.') . '</span>';
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
            </div>
        </section>

        <section id="sobre" class="about">
            <div class="container">
                <h2>Sobre a Automotiva</h2>
                <p>Fundada por apaixonados por carros, a Automotiva nasceu com a missão de oferecer o mais alto padrão em estética automotiva. Utilizamos apenas produtos de ponta e técnicas refinadas para garantir que cada veículo receba o tratamento exclusivo que merece. Nossa equipe é certificada e está em constante aprimoramento para trazer as últimas inovações do mercado até você.</p>
            </div>
        </section>

        <section id="depoimentos" class="testimonials">
            <div class="container">
                <h2>O que nossos clientes dizem</h2>
                <div class="testimonials-grid">
                    <blockquote class="testimonial-card">
                        <p>"Serviço impecável! Meu carro saiu parecendo novo. A vitrificação fez toda a diferença."</p>
                        <footer>- João Silva</footer>
                    </blockquote>
                    <blockquote class="testimonial-card">
                        <p>"Atendimento nota 10 e profissionais muito detalhistas. Recomendo o polimento técnico."</p>
                        <footer>- Maria Oliveira</footer>
                    </blockquote>
                </div>
            </div>
        </section>

        <section id="contato" class="contact">
            <div class="container">
                <h2>Entre em Contato</h2>
                <p>Pronto para transformar seu carro? Fale conosco!</p>
                <form class="contact-form" action="enviar_contato.php" method="post">
                    <input type="text" name="name" placeholder="Seu Nome" required>
                    <input type="email" name="email" placeholder="Seu E-mail" required>
                    <textarea name="message" placeholder="Sua Mensagem" rows="5" required></textarea>
                    <button type="submit" class="cta-button">Enviar Mensagem</button>
                </form>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Automotiva. Todos os direitos reservados.</p>
            <div class="social-links">
                <a href="#">Instagram</a>
                <a href="#">Facebook</a>
                <a href="#">WhatsApp</a>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
