<?php $this->layout('CartaKebabLayout'); ?>


<?php $this->start('header') ?>

    <header class="header">
        <div class="logo">
            <span class="logo-zona">Zona</span><span class="logo-kebab">Kebab</span>
        </div>
        <div class="hamburger-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <nav class="nav">
            <a href="/">Inicio</a>
            <a href="#">Carta</a>
            <a href="/kebab-create">Al gusto</a>
            <a href="#">Información Alergenos</a>
            <a href="#">Contacto</a>
        </nav>
        <div class="search-container">
            <input type="text" placeholder="Buscar productos">
            <button><img src="/img/search.png" alt="Buscar"></button>
        </div>
        <div class="icons">
            <a href="/carrito" class="cart-icon">
                <img src="/img/carrito.png" alt="">
                <span id="cart-counter" class="cart-counter">0</span>
            </a>            
            <div class="user-menu">
                <a href="#" class="user-icon"><img src="/img/user.png" alt=""></a>
            <div class="user-dropdown">
                <a href="#">Información Personal</a>
                <a href="/dashboard/add-kebab">Administrador</a>
            </div>
    </div>
</div>

    </header>
<?php $this->stop() ?>


<?php $this->start('body') ?>

<div class="filter-section">
    <button id="openFiltersBtn" class="btn btn-secondary">Filtros</button>
    <div id="filterOptions" class="filter-options" style="display: none;">
        <h3>Filtrar por precio:</h3>
        <div class="filter-group">
            <label>Precio mínimo: <span id="minPriceValue">0</span>€</label>
            <input type="range" id="minPrice" name="minPrice" min="0" max="20" step="0.5" value="0">
        </div>
        <div class="filter-group">
            <label>Precio máximo: <span id="maxPriceValue">20</span>€</label>
            <input type="range" id="maxPrice" name="maxPrice" min="0" max="20" step="0.5" value="20">
        </div>
        <button id="applyFiltersBtn" class="btn btn-primary">Aplicar Filtros</button>
    </div>
</div>

<section id="kebabs-casa" class="content-section">
    <h2 class="section-title">Nuestros Kebabs</h2>
    <div class="product-grid">
        <?php foreach ($kebabs as $kebab): ?>
            <div class="product-card">
                <div class="product-image-container">
                    <img src="data:image/jpeg;base64,<?= htmlspecialchars($kebab->getPhoto()) ?>" alt="<?= htmlspecialchars($kebab->getName()) ?>" class="product-image">
                </div>
                <div class="product-info">
                    <h3 class="product-title"><?= htmlspecialchars($kebab->getName()) ?></h3>
                    <p class="product-price"><?= number_format($kebab->getBasePrice(), 2) ?> €</p>
                    <p class="product-description">Delicioso kebab preparado con ingredientes frescos</p>
                    <div class="product-actions">
                        <a href="/kebab-detail/<?= $kebab->getIdKebab() ?>" class="btn btn-primary">Ver detalles</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php $this->stop() ?>

<?php $this->start('footer') ?>
<footer class="footer">
    <div class="footer-content">
        <div class="footer-logo">
            <span class="footer-logo-zona">Zona</span><span class="footer-logo-kebab">Kebab</span>
            <p>
            Sell&Rent está listo para ayudar a los clientes a encontrar fácilmente el automóvil de sus sueños y brindarles diversas soluciones de pago que sean flexibles y adecuadas a sus necesidades. Sell&Rent se compromete a brindar la mejor y más satisfactoria experiencia de alquiler y venta de automóviles para cada cliente.
            </p>
        </div>
        <div class="footer-links">
            <h3>Empresa</h3>
            <a href="#">Sobre Nosotros</a>
            <a href="#">Información de Alérgenos</a>
            <a href="#">Términos de Servicio</a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 ZonaKebab. Todos los derechos reservados.</p>
    </div>
</footer>
<?php $this->stop() ?>

<?php $this->start('scripts')?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger-menu');
    const nav = document.querySelector('.nav');
    const userIcon = document.querySelector('.user-icon');
    const userDropdown = document.querySelector('.user-dropdown');
    const openFiltersBtn = document.getElementById('openFiltersBtn');
    const filterOptions = document.getElementById('filterOptions');
    const applyFiltersBtn = document.getElementById('applyFiltersBtn');
    const minPriceInput = document.getElementById('minPrice');
    const maxPriceInput = document.getElementById('maxPrice');
    const minPriceValue = document.getElementById('minPriceValue');
    const maxPriceValue = document.getElementById('maxPriceValue');

    // Manejo del menú hamburguesa
    hamburger.addEventListener('click', function() {
        nav.classList.toggle('active');
    });

    // Manejo del menú de usuario
    userIcon.addEventListener('click', function(e) {  
        e.preventDefault();
        userDropdown.style.display = userDropdown.style.display === 'none' ? 'block' : 'none';
    });

    document.addEventListener('click', function(e) {
        if (!userIcon.contains(e.target) && !userDropdown.contains(e.target)) {  
            userDropdown.style.display = 'none';
        }
    });

    // Manejo de los filtros
    openFiltersBtn.addEventListener('click', function() {
        console.log('Botón de filtros clickeado');
        filterOptions.style.display = filterOptions.style.display === 'none' ? 'block' : 'none';
    });

    // Actualizar los valores mostrados cuando se mueven los sliders
    minPriceInput.addEventListener('input', function() {
        minPriceValue.textContent = this.value;
    });

    maxPriceInput.addEventListener('input', function() {
        maxPriceValue.textContent = this.value;
    });

    applyFiltersBtn.addEventListener('click', function() {
        const minPrice = parseFloat(minPriceInput.value);
        const maxPrice = parseFloat(maxPriceInput.value);

        console.log('Filtros aplicados:', { minPrice, maxPrice });
        
        // Filtrar los kebabs
        const kebabCards = document.querySelectorAll('.product-card');
        kebabCards.forEach(card => {
            const priceElement = card.querySelector('.product-price');
            if (priceElement) {
                const price = parseFloat(priceElement.textContent);
                if (price >= minPrice && price <= maxPrice) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            }
        });

        // Cerrar el panel de filtros después de aplicarlos
        filterOptions.style.display = 'none';
    });

    // Actualizar el contador del carrito al cargar la página
    updateCartCounter();
});

// Función para actualizar el contador del carrito
function updateCartCounter() {
    const cartCounter = document.getElementById('cart-counter');
    if (cartCounter) {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        cartCounter.textContent = cart.length;
    }
}

// Función para añadir al carrito
function addToCart(product) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.push(product);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCounter();
}
</script>
<?php $this->stop() ?>

