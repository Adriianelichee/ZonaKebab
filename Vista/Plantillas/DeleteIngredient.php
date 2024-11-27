<?php $this->layout('DeleteIngredientLayout'); ?>

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
        <a href="/dashboard/add-kebab" class="secondary-link">Añadir Kebab de la Casa</a>
        <a href="/dashboard/edit-kebab" class="secondary-link">Editar Kebab de la Casa</a>
        <a href="/dashboard/delete-kebab" class="secondary-link">Eliminar Kebab de la Casa</a>
        <a href="/dashboard/add-ingredient" class="secondary-link">Añadir Ingrediente</a>
        <a href="/dashboard/edit-ingredient" class="secondary-link">Editar Ingrediente</a>
        <a href="/dashboard/delete-ingredient" class="secondary-link">Eliminar Ingrediente</a>
        <a href="/dashboard/see-orders" class="secondary-link">Ver Pedidos</a>        
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


<?php $this->start('main-content')?>
    <div class="delete-ingredient-container">
        <h2>Eliminar Ingrediente</h2>
        <p>Selecciona el ingrediente que deseas eliminar:</p>
        <div id="ingredientListForDeletion" class="ingredient-list">
            <!-- Los ingredientes se cargarán aquí dinámicamente -->
        </div>
    </div>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejo de la navegación secundaria
    console.log("JavaScript cargado");
    const links = document.querySelectorAll('.secondary-nav .secondary-link');
    let activeLink = null;

    // Buscar el enlace "Eliminar Kebab de la Casa" y establecerlo como activo
    const deleteKebabLink = document.querySelector('.secondary-nav .secondary-link:nth-child(6)');
    if (deleteKebabLink) {
        deleteKebabLink.classList.add('active');
        activeLink = deleteKebabLink;
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


    loadIngredientsForDeletion();    
});

    function loadIngredientsForDeletion() {
        fetch('http://www.kebab.com/apiPhp/api_ingredienteKebab.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
                console.log('Data loaded:', data);
            })
            .then(data => {
                if (!Array.isArray(data)) {
                    throw new Error('Data is not an array');
                }
                const ingredientList = document.getElementById('ingredientListForDeletion');
                ingredientList.innerHTML = ''; // Clear existing content
    
                data.forEach(ingredient => {
                    const ingredientItem = document.createElement('div');
                    ingredientItem.className = 'ingredient-item';
                    ingredientItem.innerHTML = `
                        <img src="data:image/jpeg;base64,${ingredient.photo}" alt="${ingredient.name}" class="ingredient-image">
                        <div class="ingredient-info">
                            <h3>${ingredient.name}</h3>
                            <p>Precio: €${parseFloat(ingredient.price).toFixed(2)}</p>
                        </div>
                        <button onclick="confirmDelete(${ingredient.idIngredient})" class="delete-btn">Eliminar</button>
                    `;
                    ingredientList.appendChild(ingredientItem);
                });
            })
            .catch(error => {
                console.error('Error loading ingredients:', error);
                document.getElementById('ingredientListForDeletion').innerHTML = '<p>Error al cargar los ingredientes. Por favor, intenta de nuevo más tarde.</p>';
            });
    }

    function confirmDelete(ingredientId) {
        if (confirm('¿Estás seguro de que quieres eliminar este ingrediente?')) {
            deleteIngredient(ingredientId);
        }
    }


    function deleteIngredient(id) {
        fetch(`http://www.kebab.com/apiPhp/api_ingredienteKebab.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Ingrediente eliminado con éxito');
                loadIngredientsForDeletion(); // Reload the list
            } else {
                alert('Error al eliminar el ingrediente: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el ingrediente');
        });
    }
</script>
<?php $this->stop() ?>