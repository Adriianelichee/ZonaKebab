<?php $this->layout('AddIngredientLayout'); ?>

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
        <section class="main-content">
            <h1>Añadir Ingrediente</h1>

            <form class="ingredient-form" id="addIngredientForm" action="" method="post" enctype="multipart/form-data">
                <label for="ingredient-name">Nombre del Ingrediente</label>
                <input type="text" id="ingredient-name" name="ingredient-name" placeholder="Tomate">

                <label for="ingredient-photo">Foto del Ingrediente</label>
                <div class="photo-upload">
                    <input type="file" id="ingredient-photo" name="ingredient-photo" accept="image/*">
                    <div class="photo-preview">
                        <img id="photo-preview-image" src="#" alt="Vista previa de la imagen" style="display: none; max-width: 200px; max-height: 200px;">
                        <span id="photo-placeholder">Agregar una Foto</span>
                    </div>
                </div>

                <label for="ingredient-price">Precio del Ingrediente</label>
                <input type="text" id="ingredient-price" name="ingredient-price" placeholder="1.50€">

                <button type="submit" class="add-button">Añadir Ingrediente</button>
            </form>
        </section>
<?php $this->stop()?>

<?php $this->start('allergens')?>
    <section class="allergens">
        <h2>Alérgenos Disponibles</h2>
        <div id="available-allergens"></div>
    </section>

    <section class="allergens-added">
        <h2>Alérgenos Añadidos</h2>
        <div id="added-allergens"></div>
    </section>
<?php $this->stop()?>

<?php $this->start('footer') ?>
<footer class="footer">
    <div class="footer-content">
        <div class="footer-logo">
            <span class="footer-logo-zona">Zona</span><span class="footer-logo-kebab">Kebab</span>
            <p>
            ZonaKebab se compromete a ofrecer ingredientes de la más alta calidad para crear los mejores kebabs y satisfacer a nuestros clientes.
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
    console.log('DOM cargado. Iniciando carga de alérgenos...');

    loadAllergens();
    // Manejo de la navegación secundaria
    const links = document.querySelectorAll('.secondary-nav .secondary-link');
    let activeLink = null;

    // Buscar el enlace "Añadir Ingrediente" y establecerlo como activo
    const addIngredientLink = document.querySelector('.secondary-nav .secondary-link:nth-child(4)');
    if (addIngredientLink) {
        addIngredientLink.classList.add('active');
        activeLink = addIngredientLink;
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

    // Manejo de la carga de imágenes
    const photoInput = document.getElementById('ingredient-photo');
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

    // Cargar y manejar los alérgenos
    function loadAllergens() {
        console.log('Cargando alérgenos...');
        fetch('http://www.kebab.com/apiPhp/api_allergens.php')
            .then(response => response.json())
            .then(data => {
                console.log('Alérgenos recibidos:', data);
                displayAllergens(data);
            })
            .catch((error) => {
                console.error('Error al cargar los alérgenos:', error);
            });
    }

    function displayAllergens(allergens) {
        const availableAllergensContainer = document.getElementById('available-allergens');
        availableAllergensContainer.innerHTML = '';

        allergens.forEach(allergen => {
            const allergenElement = createAllergenElement(allergen);
            availableAllergensContainer.appendChild(allergenElement);
        });

        setupDragAndDrop();
    }

    function createAllergenElement(allergen) {
        const allergenElement = document.createElement('div');
        allergenElement.classList.add('allergen-item');
        allergenElement.dataset.id = allergen.idAllergens;
        allergenElement.draggable = true;

        allergenElement.innerHTML = `
            <img src="../../img/${allergen.photo}" alt="${allergen.name}">
            <span>${allergen.name}</span>`;

        return allergenElement;
    }

    function setupDragAndDrop() {
        const allergenItems = document.querySelectorAll('.allergen-item');
        const availableAllergensContainer = document.getElementById('available-allergens');
        const addedAllergensContainer = document.getElementById('added-allergens');

        allergenItems.forEach(item => {
            item.addEventListener('dragstart', dragStart);
            item.addEventListener('dragend', dragEnd);
        });

        [availableAllergensContainer, addedAllergensContainer].forEach(container => {
            container.addEventListener('dragover', dragOver);
            container.addEventListener('dragenter', dragEnter);
            container.addEventListener('dragleave', dragLeave);
            container.addEventListener('drop', drop);
        });
    }
    function dragStart(e) {
        e.dataTransfer.setData('text/plain', e.target.dataset.id);
        setTimeout(() => {
            e.target.classList.add('dragging');
        }, 0);
    }

    function dragEnd(e) {
        e.target.classList.remove('dragging');
    }

    function dragOver(e) {
        e.preventDefault();
    }

    function dragEnter(e) {
        e.preventDefault();
        e.target.classList.add('drag-over');
    }

    function dragLeave(e) {
        e.target.classList.remove('drag-over');
    }

    function drop(e) {
        e.preventDefault();
        const id = e.dataTransfer.getData('text');
        const draggableElement = document.querySelector(`.allergen-item[data-id="${id}"]`);
        const dropzone = e.target.closest('#available-allergens, #added-allergens');

        if (dropzone && draggableElement) {
            if (dropzone.id === 'available-allergens') {
                document.getElementById('available-allergens').appendChild(draggableElement);
            } else if (dropzone.id === 'added-allergens') {
                document.getElementById('added-allergens').appendChild(draggableElement);
            }
        }

        e.target.classList.remove('drag-over');
    }

    const addIngredientForm = document.getElementById('addIngredientForm');
    if (addIngredientForm) {
        addIngredientForm.addEventListener('submit', createIngredientFromForm);
    }

    const ingredientPhotoInput = document.getElementById('ingredient-photo');
    if (ingredientPhotoInput) {
        ingredientPhotoInput.addEventListener('change', function(e) {
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

function createIngredientFromForm(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);

    // Obtener los valores del formulario
    const name = formData.get('ingredient-name');
    const price = formData.get('ingredient-price');
    const photo = document.getElementById('ingredient-photo').files[0];

    // Validar los datos
    if (!name || !price) {
        alert('Por favor, rellena todos los campos obligatorios');
        return;
    }

    // Añadir la foto al FormData si se ha seleccionado una
    if (photo) {
        formData.set('ingredient-photo', photo);
    } else {
        alert('Por favor, selecciona una foto para el ingrediente');
        return;
    }
    const addedAllergensContainer = document.getElementById('added-allergens');
    const selectedAllergens = Array.from(addedAllergensContainer.children).map(el => el.dataset.id);
    // Obtener los alérgenos seleccionados
    
    if (selectedAllergens.length === 0) {
        alert('Por favor, selecciona al menos un alérgeno');
        return;
    }

    // Agregar los alérgenos al FormData
    formData.append('allergens', JSON.stringify(selectedAllergens));

    console.log('Alérgenos seleccionados:', selectedAllergens);
    console.log('FormData antes de enviar:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }

    // Llamar a la función para añadir el ingrediente
    addIngredient(formData);
}

function addIngredient(formData) {
    console.log('Iniciando addIngredient...');

    // Crear un objeto con los datos del ingrediente
    let ingredientData = {
        name: formData.get('ingredient-name'),
        price: parseFloat(formData.get('ingredient-price')),
        allergens: JSON.parse(formData.get('allergens')),
    };

    // Convertir la imagen a Base64
    let reader = new FileReader();
    reader.readAsDataURL(formData.get('ingredient-photo'));
    reader.onload = function () {
        ingredientData.photo = reader.result.split(',')[1]; // Quitamos el prefijo "data:image/jpeg;base64,"

        console.log('Datos a enviar:', ingredientData);

        // Enviar la solicitud al servidor
        fetch('http://www.kebab.com/apiPhp/api_ingredienteKebab.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(ingredientData)
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
                alert('Ingrediente añadido con éxito');
                document.getElementById('addIngredientForm').reset();
            } else {
                alert('Error al añadir el ingrediente: ' + (data.message || data.error || 'Error desconocido'));
            }
        })
        .catch((error) => {
            console.error('Error en la solicitud:', error);
            alert('Error al añadir el ingrediente: ' + error.message);
        });
    };
}

</script>
<?php $this->stop() ?>