<?php $this->layout('KebabDetailsNewLayout'); ?>

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
            <a href="/carrito" class="cart-icon"><img src="/img/carrito.png" alt=""></a>
            <div class="user-menu">
                <a href="#" class="user-icon"><img src="/img/user.png" alt=""></a>
                <div class="user-dropdown">
                    <a href="#">Información Personal</a>
                    <a href="/add-kebab">Administrador</a>
                </div>
            </div>
        </div>
    </header>
<?php $this->stop() ?>

<?php $this->start('body') ?>
<div class="product-container">
    <h1>Kebab Personalizado</h1>
    <div class="product-content">
        <img src="../../img/panpita.png" class="product-image">
    </div>
    <div class="ingredients-container">
        <section class="ingredients">
            <h2>Ingredientes Disponibles</h2>
            <div id="available-ingredients"></div>
        </section>
        <section class="ingredients-added">
            <h2>Ingredientes Añadidos</h2>
            <div id="added-ingredients"></div>
        </section>
    </div>  
    <div class="footer">
        <p class="price">5,00€</p>
        <button id="addToCartBtn" class="add-to-cart">Añadir al Carrito</button>
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

    function getAllIngredients() {
        fetch('http://www.kebab.com/apiPhp/api_ingredienteKebab.php')
        .then(response => response.json())
        .then(data => {
            console.log('Todos los ingredientes:', data);
            displayIngredients(data);
            calculateKebabPrice();
        })
        .catch((error) => {
            console.error('Error al obtener los ingredientes:', error);
        });
    }

    function displayIngredients(allIngredients) {
        const availableIngredientsContainer = document.getElementById('available-ingredients');
        
        if (!availableIngredientsContainer) {
            console.error('No se encontró el contenedor de ingredientes disponibles');
            return;
        }

        availableIngredientsContainer.innerHTML = '';

        allIngredients.forEach(ingredient => {
            const ingredientElement = createIngredientElement(ingredient);
            availableIngredientsContainer.appendChild(ingredientElement);
        });

        setupDragAndDrop();
    }


    function createIngredientElement(ingredient) {
        const ingredientElement = document.createElement('div');
        ingredientElement.classList.add('ingredient-item');
        ingredientElement.draggable = true;
        
        const id = ingredient.id || ingredient.idIngredient;
        
        if (id !== undefined) {
            ingredientElement.dataset.id = id.toString();
        } else {
            console.warn('Advertencia: El ingrediente no tiene un ID definido', ingredient);
        }
        
        ingredientElement.dataset.name = ingredient.name || '';
        ingredientElement.dataset.price = ingredient.price || '';

        const img = document.createElement('img');
        img.src = `data:image/jpeg;base64,${ingredient.photo}`;
        img.alt = ingredient.name;

        const span = document.createElement('span');
        span.textContent = ingredient.name;

        ingredientElement.appendChild(img);
        ingredientElement.appendChild(span);

        return ingredientElement;
    }

    function setupDragAndDrop() {
        const availableIngredientsContainer = document.getElementById('available-ingredients');
        const addedIngredientsContainer = document.getElementById('added-ingredients');

        [availableIngredientsContainer, addedIngredientsContainer].forEach(container => {
            container.addEventListener('dragover', e => {
                e.preventDefault();
                const afterElement = getDragAfterElement(container, e.clientY);
                const draggable = document.querySelector('.dragging');
                if (afterElement == null) {
                    container.appendChild(draggable);
                } else {
                    container.insertBefore(draggable, afterElement);
                }
            });
        });

        const ingredientItems = document.querySelectorAll('.ingredient-item');
        ingredientItems.forEach(item => {
            item.addEventListener('dragstart', () => {
                item.classList.add('dragging');
            });

            item.addEventListener('dragend', () => {
                item.classList.remove('dragging');
                calculateKebabPrice();
            });
        });
    }

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.ingredient-item:not(.dragging)')];

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function calculateKebabPrice() {
    const basePrice = 5.00; // Precio base fijo para un kebab personalizado
    const addedIngredients = document.querySelectorAll('#added-ingredients .ingredient-item');
    let totalPrice = basePrice;

    addedIngredients.forEach(ingredient => {
        const ingredientPrice = parseFloat(ingredient.dataset.price) || 0;
        totalPrice += ingredientPrice;
    });

    document.querySelector('.price').textContent = `${totalPrice.toFixed(2)} €`;
}

    // Iniciar la carga de ingredientes
    getAllIngredients();

    // Añadir al final del script existente, dentro de document.addEventListener('DOMContentLoaded', function() { ... });
    const addToCartBtn = document.getElementById('addToCartBtn');
    addToCartBtn.addEventListener('click', function() {
        const kebabName = "Kebab Personalizado";
        const kebabPrice = parseFloat(document.querySelector('.price').textContent);
        
        const addedIngredients = Array.from(document.querySelectorAll('#added-ingredients .ingredient-item')).map(item => ({
            id: parseInt(item.dataset.id),
            name: item.dataset.name,
            price: parseFloat(item.dataset.price) || 0
        }));

        const customKebab = {
            id: null,
            name: kebabName,
            basePrice: 5.00, // Precio base fijo
            totalPrice: kebabPrice,
            ingredients: addedIngredients
        };

        addToCart(customKebab);
        alert('Kebab personalizado añadido al carrito');
        updateCartCounter();
    });

    // Función para añadir al carrito
    function addToCart(product) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        // Asegurarse de que todos los valores numéricos sean números
        product.id = parseInt(product.id);
        product.basePrice = parseFloat(product.basePrice);
        product.totalPrice = product.totalPrice;
        
        product.ingredients = product.ingredients.map(ing => ({
            id: parseInt(ing.id),
            name: ing.name,
            price: parseFloat(ing.price)
        }));

        // Buscar si el producto ya existe en el carrito
        const existingProductIndex = cart.findIndex(item => 
            item.id === product.id && 
            JSON.stringify(item.ingredients) === JSON.stringify(product.ingredients)
        );

        if (existingProductIndex !== -1) {
            // Si el producto ya existe, incrementar la cantidad
            cart[existingProductIndex].quantity = (cart[existingProductIndex].quantity || 1) + 1;
        } else {
            // Si es un nuevo producto, añadirlo con cantidad 1
            product.quantity = 1;
            cart.push(product);
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCounter();
    }

    function updateCartCounter() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartCount = cart.reduce((total, item) => total + (item.quantity || 1), 0);
        // Actualiza el elemento HTML que muestra el contador del carrito
        const cartCounter = document.getElementById('cart-counter');
        if (cartCounter) {
            cartCounter.textContent = cartCount;
        }
    }

    updateCartCounter();

    getAllIngredients();

});
</script>
<?php $this->stop() ?>

