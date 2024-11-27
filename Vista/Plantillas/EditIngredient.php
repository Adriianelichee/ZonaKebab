<?php $this->layout('EditIngredientLayout'); ?>

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

<?php $this->start('searchIngredient')?>
    <div id="ingredientSearchOverlay" class="overlay">
        <div class="overlay-content">
            <h2>Buscar Ingrediente para Editar</h2>
            <div class="search-ingredient-container">
                <input type="text" id="searchIngredientInput" placeholder="Introduce el nombre del ingrediente">
                <button id="searchIngredientButton"><img src="/img/search.png" alt="Buscar"></button>
            </div>
            <div id="ingredientList" class="ingredient-list-all">
                <!-- Aquí se mostrarán los ingredientes -->
            </div>
            <a href="/edit-ingredient" class="close-button">Cerrar</a>
        </div>
    </div>
<?php $this->stop()?>

<?php $this->start('main-content')?>
        <section class="main-content">
            <h1>Editar Ingrediente</h1>

            <form class="ingredient-form" id="editIngredientForm" action="" method="post" enctype="multipart/form-data">
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

                <button type="submit" class="edit-button">Guardar Cambios</button>
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

    

    const searchButton = document.getElementById('searchIngredientButton');
    const searchInput = document.getElementById('searchIngredientInput');
    const ingredientList = document.getElementById('ingredientList');
    const updateButton = document.querySelector('.update-button');

    // Ocultar el botón de actualizar inicialmente
    if (updateButton) {
        updateButton.style.display = 'none';
    }

    if (searchButton && searchInput) {
        searchButton.addEventListener('click', function() {
            const searchTerm = searchInput.value.trim();
            loadIngredients(searchTerm);
        });
    } else {
        console.error('Search button or input not found');
    }

    // Cargar ingredientes inicialmente
    loadIngredients();
    // Manejo de la navegación secundaria
    const links = document.querySelectorAll('.secondary-nav .secondary-link');
    let activeLink = null;

    // Buscar el enlace "Editar Ingrediente" y establecerlo como activo
    const editIngredientLink = document.querySelector('.secondary-nav .secondary-link:nth-child(5)');
    if (editIngredientLink) {
        editIngredientLink.classList.add('active');
        activeLink = editIngredientLink;
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

    function loadIngredients(searchTerm = '') {
    fetch(`http://www.kebab.com/apiPhp/api_ingredienteKebab.php?search=${searchTerm}`)
        .then(response => response.json())
        .then(data => {
            displayIngredients(data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

function displayIngredients(ingredients) {
    const ingredientList = document.getElementById('ingredientList');
    if (ingredientList) {
        ingredientList.innerHTML = '';
        ingredients.forEach(ingredient => {
            const ingredientItem = document.createElement('div');
            ingredientItem.classList.add('ingredient-item');
            ingredientItem.textContent = ingredient.name;
            ingredientItem.addEventListener('click', () => selectIngredient(ingredient));
            ingredientList.appendChild(ingredientItem);
        });
    } else {
        console.error('Element with id "ingredientList" not found');
    }
}

function selectIngredient(ingredient) {
    console.log('Ingrediente seleccionado:', ingredient);

    document.getElementById('ingredient-name').value = ingredient.name;
    document.getElementById('ingredient-price').value = ingredient.price;

    // Actualizar la imagen del ingrediente
    const photoPreview = document.getElementById('photo-preview-image');
    const photoPlaceholder = document.getElementById('photo-placeholder');
    if (ingredient.photo && ingredient.photo !== 'undefined' && ingredient.photo !== '') {
        photoPreview.src = `data:image/jpeg;base64,${ingredient.photo}`;
        photoPreview.style.display = 'block';
        photoPlaceholder.style.display = 'none';
    } else {
        photoPreview.style.display = 'none';
        photoPlaceholder.style.display = 'block';
    }

    // Actualizar los alérgenos añadidos
    const addedAllergensContainer = document.getElementById('added-allergens');
    addedAllergensContainer.innerHTML = '';
    if (ingredient.allergens && Array.isArray(ingredient.allergens)) {
        ingredient.allergens.forEach(allergen => {
            const allergenElement = createAllergenElement(allergen);
            addedAllergensContainer.appendChild(allergenElement);
        });
    }

    // Actualizar la lista de alérgenos disponibles
    getAllAllergens().then(allAllergens => {
        const availableAllergens = allAllergens.filter(allergen => 
            !ingredient.allergens || !ingredient.allergens.some(ingredientAllergen => ingredientAllergen.id === allergen.id)
        );
        displayAllergens(availableAllergens);
    }).catch(error => {
        console.error('Error al obtener todos los alérgenos:', error);
    });

    // Mostrar y cambiar el texto del botón de envío
    const updateButton = document.querySelector('.update-button');
    if (updateButton) {
        updateButton.style.display = 'block';
        updateButton.textContent = 'Actualizar';
    }

    // Cambiar el título del formulario
    const formTitle = document.querySelector('.main-content h1');
    if (formTitle) {
        formTitle.textContent = 'Editar Ingrediente';
    }

    document.getElementById('editIngredientForm').dataset.ingredientId = ingredient.idIngredient;

    // Cerrar la ventana de búsqueda
    const searchOverlay = document.getElementById('ingredientSearchOverlay');
    if (searchOverlay) {
        searchOverlay.style.display = 'none';
    }
}

document.getElementById('editIngredientForm').addEventListener('submit', updateIngredientFromForm);

function updateIngredientFromForm(e) {
    e.preventDefault();
    console.log('updateIngredientFromForm() llamada');

    const ingredientId = this.dataset.ingredientId;
    if (!ingredientId) {
        console.error('No se ha seleccionado ningún ingrediente para actualizar');
        return;
    }

    const name = document.getElementById('ingredient-name').value;
    const price = parseFloat(document.getElementById('ingredient-price').value);
    const photoInput = document.getElementById('ingredient-photo');
    const photoPreview = document.getElementById('photo-preview-image');

    const addedAllergens = Array.from(document.querySelectorAll('#added-allergens .allergen-item'))
        .map(item => parseInt(item.dataset.id));

    const ingredientData = {
        id: ingredientId,
        name: name,
        price: price,
        allergens: JSON.stringify(addedAllergens)
    };

    // Función para procesar la imagen y enviar los datos
    const processImageAndSend = (imageData) => {
        ingredientData.photo = imageData;
        sendIngredientUpdateData(ingredientData);
    };

    if (photoInput.files[0]) {
        // Si se ha seleccionado una nueva imagen
        const reader = new FileReader();
        reader.onload = function(e) {
            const base64Image = e.target.result.split(',')[1]; // Obtiene solo la parte de datos base64
            processImageAndSend(base64Image);
        };
        reader.readAsDataURL(photoInput.files[0]);
    } else if (photoPreview.src) {
        // Si no hay nueva imagen, pero hay una imagen existente en la vista previa
        fetch(photoPreview.src)
            .then(res => res.blob())
            .then(blob => {
                const reader = new FileReader();
                reader.onloadend = function() {
                    const base64Image = reader.result.split(',')[1];
                    processImageAndSend(base64Image);
                };
                reader.readAsDataURL(blob);
            });
    } else {
        // Si no hay imagen nueva ni existente
        processImageAndSend(null);
    }
}



function sendIngredientUpdateData(ingredientData) {
    const ingredientId = ingredientData.id;
    const url = `http://www.kebab.com/apiPhp/api_ingredienteKebab.php?id=${ingredientId}`;

    fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(ingredientData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Ingrediente actualizado con éxito');
            loadIngredients(); // Recargar la lista de ingredientes
        } else {
            alert('Error al actualizar el ingrediente: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch((error) => {
        console.error('Error en la solicitud:', error);
        alert('Error al actualizar el ingrediente: ' + error.message);
    });
}


    // Cargar y manejar los alérgenos
    function getAllAllergens() {
        console.log('Cargando alérgenos...');
        return fetch('http://www.kebab.com/apiPhp/api_allergens.php')
            .then(response => response.json())
            .catch((error) => {
                console.error('Error:', error);
                throw error;
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
});
</script>
<?php $this->stop() ?>