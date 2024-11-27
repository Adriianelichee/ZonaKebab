<?php $this->layout('layout'); ?>


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
            <a href="#" class="cart-icon"><img src="/img/carrito.png" alt=""></a>
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

<?php $this->start('nav2')?>
    <div class="secondary-nav">
        <a href="#" class="secondary-link" data-section="kebabs-casa">Kebab's de la casa</a>
        <a href="#" class="secondary-link" data-section="create-kebab">Kebab al gusto</a>
    </div>
<?php $this->stop()?>

<?php $this->start('body') ?>
<section id="kebabs-casa" class="content-section">
        <div class="product-carousel">
            <?php
            // Limitar a un máximo de 4 kebabs
            $displayKebabs = array_slice($kebabs, 0, 4);
            foreach ($displayKebabs as $kebab):
            ?>
                <div class="product-card">
                    <img src="data:image/jpeg;base64,<?= htmlspecialchars($kebab->getPhoto()) ?>" alt="<?= htmlspecialchars($kebab->getName()) ?>" class="product-image">
                    <div class="product-info">
                        <h3><?= htmlspecialchars($kebab->getName()) ?></h3>
                        <p class="price"><?= number_format($kebab->getBasePrice(), 2) ?> €</p>
                        <p class="delivery-info">Entrega en menos de 5 minutos</p>
                        <a href="/kebab-detail/<?= $kebab->getIdKebab() ?>" class="view-more-btn">Ver más</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="create-kebab" class="content-section">
        <div class="text-content">
            <h1>Crea tu kebab perfecto</h1>
            <p>Elige tus ingredientes y disfruta de un sabor único, hecho a tu gusto.</p>
            <a href="/kebab-create" class="create-button">Crear</a>
        </div>
    </section>
<?php $this->stop() ?>

<?php $this->start('reseñas')?>
<div class="testimonial-section">
        <h2>Opiniones de Nuestros Clientes</h2>
        <div class="testimonial-container">
            <div class="testimonial">
                <p>El proceso de alquilar un coche fue increíblemente fácil y rápido. Encontré el coche que necesitaba a un precio justo y con todas las comodidades que buscaba. El servicio al cliente fue excelente, y sin duda volveré a alquilar con ellos.</p>
                <span>María Fernández</span>
            </div>
            <div class="testimonial">
                <p>Decidí vender mi coche a través de SellRent y quedé muy satisfecho. Me dieron un presupuesto online en minutos y todo el proceso fue transparente y eficiente. No tuve que preocuparme por nada, ¡muy recomendable!</p>
                <span>José Martínez</span>
            </div>
            <div class="testimonial">
                <p>Alquilar un coche aquí fue la mejor decisión para mi viaje. La interfaz es intuitiva y las opciones de filtro me ayudaron a encontrar justo lo que buscaba. El proceso de recogida fue rápido y sin problemas.</p>
                <span>Laura González</span>
            </div>
            <div class="testimonial">
                <p>Vendí mi coche a través de este servicio y no podría estar más contento. Recibí un presupuesto justo y la gestión fue muy profesional. Todo fue online y muy sencillo, evitando papeleo y llamadas interminables.</p>
                <span>Carlos López</span>
            </div>
            <div class="testimonial">
                <p>Excelente servicio de alquiler. La página es clara, los precios son competitivos y el coche que alquilé estaba impecable. Repetiría sin dudarlo. El proceso fue rápido, algo que valoro mucho en mi día a día.</p>
                <span>Ana Torres</span>
            </div>
            <div class="testimonial">
                <p>Vender mi coche fue una experiencia muy positiva. No tuve que desplazarme ni perder tiempo, todo lo gestioné online. El presupuesto que me ofrecieron fue justo y el proceso fue sencillo y rápido. Definitivamente volvería a usar este servicio.</p>
                <span>Ricardo Pérez</span>
            </div>
        </div>
    </div>
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
    const links = document.querySelectorAll('.secondary-nav .secondary-link');
    const sections = document.querySelectorAll('.content-section');
    let activeLink = null;

    function showSection(sectionId) {
        sections.forEach(section => {
            if (section.id === sectionId) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    }

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            if (activeLink) {
                activeLink.classList.remove('active');
            }
            this.classList.add('active');
            activeLink = this;
            
            const sectionId = this.getAttribute('data-section');
            showSection(sectionId);
        });
    });

    // Mostrar la primera sección por defecto
    showSection('kebabs-casa');
    links[0].classList.add('active');
    activeLink = links[0];

    const hamburger = document.querySelector('.hamburger-menu');
    const nav = document.querySelector('.nav');

    hamburger.addEventListener('click', function() {
        nav.classList.toggle('active');
    });

    const userIcon = document.querySelector('.user-icon');
    const userDropdown = document.querySelector('.user-dropdown');

    userIcon.addEventListener('click', function(e) {  
        e.preventDefault();
        userDropdown.style.display = userDropdown.style.display === 'none' ? 'block' : 'none';
    });

    document.addEventListener('click', function(e) {
        if (!userIcon.contains(e.target) && !userDropdown.contains(e.target)) {  
            userDropdown.style.display = 'none';
        }
    });
});
</script>
<?php $this->stop() ?>

