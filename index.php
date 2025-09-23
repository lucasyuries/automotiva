<?php
session_start();
require_once 'config.php';
?>
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
                <div class="carousel-container">
                    <button class="carousel-btn prev" id="services-prev" aria-label="Anterior">&#8249;</button>
                    <div class="carousel services-carousel">
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
                            <p>Limpeza profunda do interior, eliminando ácaros, bactérias e odores indesejados.</p>
                        </article>
                        <article class="service-card">
                            <h3>Limpeza Técnica de Motor</h3>
                            <p>Limpeza detalhada do motor e seus componentes, sem risco para a parte elétrica.</p>
                        </article>
                        <article class="service-card">
                            <h3>Restauração de Faróis</h3>
                            <p>Devolve a transparência e a eficiência dos faróis amarelados e opacos.</p>
                        </article>
                        <article class="service-card">
                            <h3>Impermeabilização de Estofados</h3>
                            <p>Cria uma camada protetora contra líquidos e sujeiras nos bancos de tecido.</p>
                        </article>
                         <article class="service-card">
                            <h3>Proteção de Pintura (PPF)</h3>
                            <p>Aplicação de película transparente que protege contra riscos, pedras e detritos.</p>
                        </article>
                         <article class="service-card">
                            <h3>Tratamento de Couro</h3>
                            <p>Limpeza e hidratação profunda para bancos de couro, prevenindo rachaduras.</p>
                        </article>
                         <article class="service-card">
                            <h3>Oxi-Sanitização</h3>
                            <p>Eliminação de fungos, vírus e bactérias do sistema de ar-condicionado e interior.</p>
                        </article>
                    </div>
                    <button class="carousel-btn next" id="services-next" aria-label="Próximo">&#8250;</button>
                </div>
            </div>
        </section>

        <section id="produtos" class="products">
            <div class="container">
                <h2>Produtos em Destaque</h2>
                <div class="carousel-container">
                    <button class="carousel-btn prev" id="products-prev" aria-label="Anterior">&#8249;</button>
                    <div class="carousel products-carousel">
                        <?php
                        try {
                            $sql = "SELECT nome, descricao, preco, imagem_url FROM produtos ORDER BY id DESC LIMIT 6";
                            $stmt = $pdo->query($sql);

                            if ($stmt->rowCount() > 0) {
                                while($row = $stmt->fetch()){
                                    $imageUrl = htmlspecialchars($row['imagem_url']);
                                    echo '<article class="product-card">';
                                    echo '    <img src="' . $imageUrl . '" alt="' . htmlspecialchars($row['nome']) . '" class="carousel-image" data-src="' . $imageUrl . '">';
                                    echo '    <h3>' . htmlspecialchars($row['nome']) . '</h3>';
                                    echo '    <p>' . htmlspecialchars($row['descricao']) . '</p>';
                                    echo '    <span class="price">R$ ' . number_format($row['preco'], 2, ',', '.') . '</span>';
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
                    <button class="carousel-btn next" id="products-next" aria-label="Próximo">&#8250;</button>
                </div>
                 <div style="text-align: center; margin-top: 2rem;">
                    <a href="carrinho.php" class="cta-button">Ver Todos os Produtos</a>
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

    <div id="imageModal" class="modal">
        <span class="close-modal" id="closeModal">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <script src="script.js"></script>
</body>
</html>