<?php $this->layout('KebabLayout'); ?>

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
        <a href="#" class="secondary-link active">Añadir Kebab de la Casa</a>
        <a href="/dashboard/edit-kebab" class="secondary-link">Editar Kebab de la Casa</a>
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



<?php $this->start('main-content')?>
        <section class="main-content">
            <h1>Añadir Kebab de la Casa</h1>

            <form class="kebab-form" id="addKebabForm" action="" method="post" enctype="multipart/form-data">
                <label for="kebab-title">Introduce el título del Kebab</label>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejo de la navegación secundaria
    const links = document.querySelectorAll('.secondary-nav .secondary-link');
    let activeLink = null;

    const addKebabLink = document.querySelector('.secondary-nav .secondary-link');
    if (addKebabLink) {
        addKebabLink.classList.add('active');
        activeLink = addKebabLink;
    }

    

    links.forEach(link => {
        link.addEventListener('mouseenter', function() {
            if (!activeLink) {
                links.forEach(link => link.classList.remove('active')); 
                this.classList.add('active');
            }
        });
        link.addEventListener('mouseleave', function() {
            if (!activeLink) {
                this.classList.remove('active');
            }
        });
        link.addEventListener('click', function(e) {
            if (activeLink) {
                activeLink.classList.remove('active');
            }
            this.classList.add('active');
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

    const addKebabForm = document.getElementById('addKebabForm');
    if (addKebabForm) {
        addKebabForm.addEventListener('submit', createKebabFromForm);
    }

    const kebabPhotoInput = document.getElementById('kebab-photo');
    if (kebabPhotoInput) {
        kebabPhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImage = document.getElementById('photo-preview-image');
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                    document.getElementById('photo-placeholder').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    }

});

function createKebabFromForm(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);

    // Obtener los valores del formulario
    const name = formData.get('kebab-title');
    const basePrice = formData.get('kebab-price');
    const base = formData.get('kebab-base');
    const photo = document.getElementById('kebab-photo').files[0];

    // Validar los datos
    if (!name || !basePrice || !base) {
        alert('Por favor, rellena todos los campos obligatorios');
        return;
    }

    // Añadir la foto al FormData si se ha seleccionado una
    if (photo) {
        formData.set('kebab-photo', photo);
    } else {
        alert('Por favor, selecciona una foto para el kebab');
        return;
    }

    // Obtener los ingredientes seleccionados
    const addedIngredientsContainer = document.getElementById('added-ingredients');
    const addedIngredients = Array.from(addedIngredientsContainer.children).map(el => el.dataset.id);
    
    if (addedIngredients.length === 0) {
        alert('Por favor, añade al menos un ingrediente al kebab');
        return;
    }

    // Agregar los ingredientes al FormData
    formData.append('ingredients', JSON.stringify(addedIngredients));

    console.log('Ingredientes seleccionados:', addedIngredients);
    console.log('FormData antes de enviar:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }

    // Llamar a la función para añadir el kebab
    addKebab(formData);
}

function addKebab(formData) {
    console.log('Iniciando addKebab...');

    // Crear un objeto con los datos del kebab
    let kebabData = {
        'kebab-title': formData.get('kebab-title'),
        'kebab-price': parseFloat(formData.get('kebab-price')),
        'ingredients': JSON.parse(formData.get('ingredients')),
        'photo': formData.get('kebab-photo')
};

    // Convertir la imagen a Base64
    let reader = new FileReader();
    reader.readAsDataURL(formData.get('kebab-photo'));
    reader.onload = function () {
        kebabData.photo = reader.result;

        // Enviar la solicitud al servidor
        fetch('http://www.kebab.com/apiPhp/api_kebab.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(kebabData)
        })
        .then(response => {
            console.log('Respuesta recibida del servidor');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos parseados de la respuesta:', data);
            if (data.success) {
                alert('Kebab añadido con éxito');
                document.getElementById('addKebabForm').reset();
            } else {
                alert('Error al añadir el kebab: ' + (data.message || data.error || 'Error desconocido'));
            }
        })
        .catch((error) => {
            console.error('Error en la solicitud:', error);
            alert('Error al añadir el kebab: ' + error.message);
        });
    };
}

</script>
<?php $this->stop() ?>