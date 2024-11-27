<?php $this->layout('EditKebabLayout'); ?>

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
            <a href="#">Inicio</a>
            <a href="#">Carta</a>
            <a href="#">Al gusto</a>
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
                    <a href="#">Administrador</a>
                </div>
            </div>
        </div>
    </header>
<?php $this->stop() ?>

<?php $this->start('nav2')?>
    <div class="secondary-nav">
        <a href="#" class="secondary-link">Añadir Kebab de la Casa</a>
        <a href="#" class="secondary-link">Editar Kebab de la Casa</a>
        <a href="#" class="secondary-link">Eliminar Kebab de la Casa</a>
        <a href="#" class="secondary-link">Añadir Ingrediente</a>
        <a href="#" class="secondary-link">Editar Ingrediente</a>
        <a href="#" class="secondary-link">Eliminar Ingrediente</a>
        <a href="#" class="secondary-link">Ver Pedidos</a>        
    </div>
<?php $this->stop()?>

<?php $this->start('sidebar')?>
    <aside class="sidebar">
        <h2>Cuenta</h2>
        <p>AdrianPan Moderador, adrianpancorbo@sellrent.com</p>
        <div class="menu-item">
            <i class="icon">📄</i>
            <span>Información Personal</span>
            <p>Proporcione datos personales y cómo podemos comunicarnos con usted.</p>
        </div>
        <div class="menu-item">
            <i class="icon">💳</i>
            <span>Pagos y Compras</span>
            <p>Revisa los pagos realizados como las compras realizadas por usted.</p>
        </div>
        <div class="menu-item selected">
            <i class="icon">📋</i>
            <span>Dashboard Moderación</span>
            <p>Apartado para el usuario con permisos especiales.</p>
        </div>
    </aside>
<?php $this->stop()?>

<?php $this->start('searchKebab')?>
    <div id="kebabSearchOverlay" class="overlay">
        <div class="overlay-content">
            <h2>Buscar Kebab para Editar</h2>
            <div class="search-kebab-container">
                <input type="text" id="searchKebabInput" placeholder="Introduce el nombre del kebab">
                <button id="searchKebabButton"><img src="/img/search.png" alt="Buscar"></button>
            </div>
            <div id="kebabList" class="kebab-list-all">
                <!-- Aquí se mostrarán los kebabs -->
            </div>
            <a href="/add-kebab" class="close-button">Cerrar</a>
        </div>
    </div>
<?php $this->stop()?>



<?php $this->start('main-content')?>
        <section class="main-content">
            <h1>Editar Kebab de la Casa</h1>

            <form class="kebab-form" id="addKebabForm" action="" method="post" enctype="multipart/form-data">
                <label for="kebab-title">Edita el título del Kebab</label>
                <input type="text" id="kebab-title" name="kebab-title" placeholder="Kebab Mixto">

                <label for="kebab-photo">Agrega la foto del kebab</label>
                <div class="photo-upload">
                    <input type="file" id="kebab-photo" name="kebab-photo" accept="image/*">
                    <div class="photo-preview">
                        <img id="photo-preview-image" src="#" alt="Vista previa de la imagen" style="display: none; max-width: 200px; max-height: 200px;">
                        <span id="photo-placeholder">Agregar una Foto</span>
                    </div>
                </div>



                <label for="kebab-base">Elige una base del Kebab a crear</label>
                <select id="kebab-base" name="kebab-base">
                    <option>Kebab Mixto sin queso</option>
                    <option>Kebab Vegetariano</option>
                </select>

                <label for="kebab-price">Introduce el Precio base del Kebab</label>
                <input type="text" id="kebab-price" name="kebab-price" placeholder="10€">

                <button type="submit" class="add-button">Añadir</button>
            </form>
        </section>
<?php $this->stop()?>

<?php $this->start('ingredients')?>
    <section class="ingredients">
        <h2>Ingredientes Disponibles</h2>
        <div id="available-ingredients"></div>
    </section>

    <section class="ingredients-added">
        <h2>Ingredientes Añadidos</h2>
        <div id="added-ingredients"></div>
    </section>
<?php $this->stop()?>



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

<?php $this->start('scripts') ?>
<script src="../../apiJS/api_ingredienteKebab.js"></script>
<script src="../../apiJS/api_Kebab.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejo de la navegación secundaria
    const links = document.querySelectorAll('.secondary-nav .secondary-link');
    let activeLink = null;

    // Buscar el enlace "Eliminar Kebab de la Casa" y establecerlo como activo
    const editKebabLink = document.querySelector('.secondary-nav .secondary-link:nth-child(2)');
    if (editKebabLink) {
        editKebabLink.classList.add('active');
        activeLink = editKebabLink;
    }

    links.forEach(link => {
        link.addEventListener('mouseenter', function() {
            if (this !== activeLink) {
                this.classList.add('hover-active');
            }
        });
        link.addEventListener('mouseleave', function() {
            if (this !== activeLink) {
                this.classList.remove('hover-active');
            }
        });
        link.addEventListener('click', function(e) {
            e.preventDefault();
            if (activeLink) {
                activeLink.classList.remove('active');
            }
            this.classList.add('active');
            this.classList.remove('hover-active');
            activeLink = this;
        });
    });
    const nav2 = document.querySelector('.secondary-nav');
    if (nav2) {
        nav2.addEventListener('mouseleave', function() {
            if (!activeLink) {
                links.forEach(link => link.classList.remove('active'));
            }
        });
    }


    const sidebarItem= document.querySelectorAll('.sidebar .menu-item');
    let activeSidebarItem = document.querySelector('.sidebar.menu-item.selected');
    
    sidebarItem.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            if (activeSidebarItem) {
                activeSidebarItem.classList.remove('selected');
            }
            this.classList.add('selected');
            activeSidebarItem = this;
        });
    });

    // Manejo del menú hamburguesa
    const hamburger = document.querySelector('.hamburger-menu');
    const nav = document.querySelector('.nav');

    if (hamburger && nav) {
        hamburger.addEventListener('click', function() {
            nav.classList.toggle('active');
        });
    }

    // Manejo del menú de usuario
    const userIcon = document.querySelector('.user-icon');
    const userDropdown = document.querySelector('.user-dropdown');

    if (userIcon && userDropdown) {
        userIcon.addEventListener('click', function(e) {  
            e.preventDefault();
            userDropdown.style.display = userDropdown.style.display === 'none' ? 'block' : 'none';
        });

        document.addEventListener('click', function(e) {
            if (!userIcon.contains(e.target) && !userDropdown.contains(e.target)) {  
                userDropdown.style.display = 'none';
            }
        });
    }

    // Nuevo: Manejo de la carga de imágenes
    const photoInput = document.getElementById('kebab-photo');
    const photoPreviewImage = document.getElementById('photo-preview-image');
    const photoPlaceholder = document.getElementById('photo-placeholder');

    if (photoInput && photoPreviewImage && photoPlaceholder) {
        photoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    photoPreviewImage.src = e.target.result;
                    photoPreviewImage.style.display = 'block';
                    photoPlaceholder.style.display = 'none';
                }
                
                reader.readAsDataURL(this.files[0]);
            } else {
                photoPreviewImage.style.display = 'none';
                photoPlaceholder.style.display = 'block';
                photoPlaceholder.textContent = 'Agregar una Foto';
            }
        });
    }

    
});
</script>
<?php $this->stop() ?>