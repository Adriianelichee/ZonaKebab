<?php $this->layout('ContactoLayout'); ?>


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

<?php $this->start('body') ?>
<section class="contact">
        <h1>Contacto</h1>
        <div class="contact-container">
            <div class="map">
                <!-- Usa un iframe de Google Maps -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3163.5006979655534!2d-1.783097684695368!3d37.76786297975916!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd6e47927b0e1f0d%3A0x30b62a09e524c280!2sC.%20Enrique%20Ponce%2C%204%2C%2023006%20Ja%C3%A9n%2C%20Espa%C3%B1a!5e0!3m2!1ses!2sus!4v1697568979865!5m2!1ses!2sus"
                    width="100%"
                    height="300"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            <div class="contact-info">
                <p><strong>Ubicación:</strong> <a href="#">C. Enrique Ponce, 4, 23006 Jaén</a></p>
                <p><strong>Teléfono de Contacto:</strong> 953859182</p>
                <p><strong>Correo electrónico de contacto:</strong> <a href="mailto:help@zonakebab.com">help@zonakebab.com</a></p>
                <p>
                    En nuestra página de Contacto, estamos aquí para responder tus preguntas y recibir tus comentarios.
                    Puedes comunicarte con nosotros a través del teléfono, por correo electrónico o visitando nuestras
                    redes sociales. Nuestro equipo está disponible para ayudarte con cualquier consulta sobre pedidos
                    o servicios. ¡Tu opinión es importante para nosotros!
                </p>
                <div class="social-icons">
                    <a href="#" class="icon whatsapp"></a>
                    <a href="#" class="icon tiktok"></a>
                    <a href="#" class="icon instagram"></a>
                    <a href="#" class="icon facebook"></a>
                </div>
            </div>
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
</script>
<?php $this->stop() ?>

