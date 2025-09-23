document.addEventListener('DOMContentLoaded', () => {
    
    // ========================================================================
    // LÓGICA DO MENU DE NAVEGAÇÃO E HEADER
    // ========================================================================
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            navToggle.classList.toggle('active');
        });
    }

    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            if (navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                navToggle.classList.remove('active');
            }
        });
    });

    const header = document.querySelector('.header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.style.backgroundColor = 'rgba(20, 20, 20, 0.9)';
            header.style.padding = '0.5rem 0';
        } else {
            header.style.backgroundColor = 'var(--secondary-color)';
            header.style.padding = '1rem 0';
        }
    });

    // ========================================================================
    // LÓGICA DO CARRINHO COM AJAX
    // ========================================================================
    const cartPageContainer = document.getElementById('cart-page-container');
    const cartSummaryContainer = document.getElementById('cart-summary-container');
    if (cartPageContainer && cartSummaryContainer) {
        cartPageContainer.addEventListener('click', (event) => {
            const targetButton = event.target.closest('.cart-action-btn');
            if (!targetButton) return;

            event.preventDefault();
            const productId = targetButton.dataset.productId;
            const action = targetButton.dataset.action;
            const formData = new URLSearchParams();
            formData.append('action', action);
            if (productId) {
                formData.append('product_id', productId);
            }

            fetch('atualizar_carrinho.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.cart_html) {
                        cartSummaryContainer.innerHTML = data.cart_html;
                    }
                })
                .catch(error => console.error('Erro ao atualizar o carrinho:', error));
        });
    }

    // ========================================================================
    // LÓGICA PARA INICIALIZAR CARROSSÉIS
    // ========================================================================
    function initializeCarousel(carouselSelector, prevBtnSelector, nextBtnSelector) {
        const carousel = document.querySelector(carouselSelector);
        const prevBtn = document.querySelector(prevBtnSelector);
        const nextBtn = document.querySelector(nextBtnSelector);
        if (!carousel || !prevBtn || !nextBtn) return;
        
        const firstCard = carousel.querySelector('.service-card, .product-card');
        if (!firstCard) return;

        const cardStyle = window.getComputedStyle(firstCard);
        const cardMargin = parseFloat(cardStyle.marginRight) || 0;
        const scrollAmount = firstCard.offsetWidth + cardMargin + 32;

        nextBtn.addEventListener('click', () => carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' }));
        prevBtn.addEventListener('click', () => carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' }));
    }

    initializeCarousel('.services-carousel', '#services-prev', '#services-next');
    initializeCarousel('.products-carousel', '#products-prev', '#products-next');

    // ========================================================================
    // LÓGICA PARA O MODAL DE IMAGEM
    // ========================================================================
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        const modalImage = document.getElementById('modalImage');
        const closeModalBtn = document.getElementById('closeModal');
        const carouselImages = document.querySelectorAll('.carousel-image');

        carouselImages.forEach(image => {
            image.addEventListener('click', () => {
                imageModal.classList.add('active');
                modalImage.src = image.dataset.src;
            });
        });

        const closeImageModal = () => imageModal.classList.remove('active');
        closeModalBtn.addEventListener('click', closeImageModal);
        imageModal.addEventListener('click', (event) => {
            if (event.target === imageModal) closeImageModal();
        });
        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && imageModal.classList.contains('active')) closeImageModal();
        });
    }

    // ========================================================================
    // LÓGICA PARA O MODAL DE PAGAMENTO PIX
    // ========================================================================
    const pixModal = document.getElementById('pixModal');
    if (pixModal) {
        const openPixModalBtn = document.getElementById('openPixModal');
        const closePixModalBtn = document.getElementById('closePixModal');
        
        if (openPixModalBtn && closePixModalBtn) {
            openPixModalBtn.addEventListener('click', () => pixModal.classList.add('active'));

            const closePixModal = () => pixModal.classList.remove('active');
            closePixModalBtn.addEventListener('click', closePixModal);
            pixModal.addEventListener('click', (event) => {
                if (event.target === pixModal) closePixModal();
            });
        }

        const copyPixKeyBtn = document.getElementById('copyPixKey');
        if (copyPixKeyBtn) {
            const pixKeyText = document.getElementById('pixKey').innerText;
            copyPixKeyBtn.addEventListener('click', () => {
                navigator.clipboard.writeText(pixKeyText).then(() => {
                    const originalText = copyPixKeyBtn.innerText;
                    copyPixKeyBtn.innerText = 'Copiado!';
                    setTimeout(() => { copyPixKeyBtn.innerText = originalText; }, 2000);
                }).catch(err => console.error('Erro ao copiar a chave PIX: ', err));
            });
        }
    }
});