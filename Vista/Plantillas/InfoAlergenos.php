<?php $this->layout('InfoAlergenosLayout'); ?>


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
<section class="about-us">
        <h1>Información Alérgenos</h1>
        <div class="about-section">
            <p>
                En <strong>Sell&Rent</strong>, somos mucho más que una plataforma de compra, venta y alquiler de coches.
                Nos especializamos en crear experiencias de confianza y satisfacción para nuestros clientes, ofreciendo
                soluciones personalizadas que se adaptan a cada necesidad. Desde nuestros inicios, hemos trabajado con
                pasión para posicionarnos como líderes en el sector automotriz, combinando tecnología, innovación y un
                enfoque humano en cada interacción.
                
                Creemos en la transparencia y en la simplicidad de los procesos. Nuestro equipo está comprometido en
                hacer que cada cliente, ya sea que busque vender su coche o alquilar uno para una ocasión especial,
                reciba la mejor atención. Gracias a nuestra plataforma intuitiva y fácil de usar, gestionamos todas las
                transacciones de forma rápida y segura, minimizando el esfuerzo del cliente y maximizando los resultados.

                Nos enorgullece contar con una amplia gama de vehículos, desde los más prácticos hasta los más lujosos,
                garantizando calidad, seguridad y comodidad en cada uno de ellos.

                Nuestra misión es ofrecer un servicio excepcional que supere las expectativas de nuestros clientes, y lo
                logramos a través de un enfoque constante en la excelencia, la innovación y la mejora continua. En
                <strong>Sell&Rent</strong>, no solo vendemos o alquilamos coches, creamos relaciones duraderas basadas
                en la confianza y la satisfacción del cliente. ¡Déjanos ser parte de tu próximo viaje o ayudarnos a
                encontrar el mejor comprador para tu vehículo!
            </p>
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

