
<?php $this->layout('CarritoCompraLayout'); ?>

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
                    <a href="/add-kebab">Administrador</a>
                </div>
            </div>
        </div>
    </header>
<?php $this->stop() ?>

<?php $this->start('body') ?>
<div class="checkout-container">
<nav class="breadcrumb">
        <span class="step-indicator active">REGISTRO</span> &gt; 
        <span class="step-indicator">ENVÍO</span> &gt; 
        <span class="step-indicator">PAGO</span>
    </nav>

    <div class="checkout-content">
        <!-- Sección de formulario -->
        <section class="form-section">
            <div class="step" id="registro-step">
                <h2>1 Registro</h2>
                <p>¿Tienes una cuenta creada? <a href="#" id="toggleLogin">Inicia Sesión</a></p>
                <div id="registro-container">
                    <form id="registro-form">
                        <input type="text" placeholder="Nombre" name="name" id="username" required>
                        <input type="password" placeholder="Contraseña" name="password" id="password" required>
                        <input type="email" placeholder="Correo Electrónico" name="email" id="email" required>
                        <input type="tel" placeholder="Teléfono Móvil" name="tel" id="tel" required>
                        <button type="submit">REGISTRARSE Y CONTINUAR AL ENVÍO</button>
                    </form>
                </div>
                <div id="login-container" style="display: none;">
                <form id="login-form">
                    <input type="email" name="email" placeholder="Introduce tu correo electronico" required>
                    <input type="password" name="password" placeholder="Introduce tu contraseña" required>
                    <button type="submit">Iniciar sesión</button>
                </form>
                </div>
            </div>

            <div class="step" id="envio-step" style="display: none;">
                <h2>2 Información de Envío</h2>
                <select id="direcciones-select">
                    <option value="">Seleccione una dirección existente</option>
                </select>
                <form id="envio-form">
                    <input type="text" id="nombre-destinatario" placeholder="Nombre del destinatario" required readonly>
                    <input type="tel" id="telefono-destinatario" placeholder="Teléfono del destinatario" required readonly>
                    <input type="time" id="hora-entrega" placeholder="Hora de la entrega" required>
                    <input type="text" id="calle" placeholder="Calle" required>
                    <input type="text" id="numero" placeholder="Número" required>
                    <button type="button" id="añadir-direccion">Añadir Dirección</button>
                    <button type="submit" id="continuePay">CONTINUAR CON EL PAGO</button>
                </form>
            </div>

            <div class="step" id="pago-step" style="display: none;">
                <h2>3 Pago</h2>
                <p>Crédito disponible en Monedero: <strong id="credito-disponible">0€</strong></p>
                <form id="pago-form">
                    <input type="number" id="cantidad-añadir" placeholder="Introduce el dinero que desea añadir" min="0" step="0.01">
                    <button type="submit" id="boton-añadir">AÑADIR</button>
                </form>
                <button id="realizar-compra">REALIZAR COMPRA</button>
            </div>
        </section>

            <!-- Sección del carrito -->
            <section class="cart-section">
                <h2>Cesta</h2>
                <div id="cart-items">
                    <!-- Los items del carrito se insertarán aquí dinámicamente -->
                </div>
                <div class="cart-summary">
                    <span id="cart-counter">Articulos añadidos: 0</span>
                    <p>Subtotal: <span id="cart-subtotal">0€</span></p>
                    <p>IVA: <span id="cart-iva">10%</span></p>
                    <p>Total: <strong id="cart-total">0€</strong></p>
                </div>
                <p class="secure-payment">Pago Seguro 🔒</p>
            </section>

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
    let currentStep = 0;
    const steps = ['registro', 'envio', 'pago'];
    let userId = null;
    let userAddresses = [];

    function nextStep() {
    if (currentStep < steps.length - 1) {
        currentStep++;
        showStep(currentStep);
    }
}

function showStep(stepIndex) {
        steps.forEach((step, index) => {
            const stepElement = document.getElementById(`${step}-step`);
            const indicator = document.querySelectorAll('.step-indicator')[index];
            if (index === stepIndex) {
                stepElement.style.display = 'block';
                indicator.classList.add('active');
            } else {
                stepElement.style.display = 'none';
                indicator.classList.remove('active');
            }
        });
    }
    
document.addEventListener('DOMContentLoaded', function() {

    // Registro form submission
    document.getElementById('registro-form').addEventListener('submit', function(e) {
        e.preventDefault();
        createUserFromForm(e);
    });

    // Login form submission
    document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();
        loginUser(e);
    });

    // Envío form submission
    document.getElementById('envio-form').addEventListener('submit', function(e) {
        e.preventDefault();
        saveDeliveryInfo();
        nextStep();
    });

    // Añadir dirección
    document.getElementById('añadir-direccion').addEventListener('click', function() {
        addAddress();
    });

    // Inicializar mostrando el primer paso
    showStep(currentStep);

    const toggleLogin = document.getElementById('toggleLogin');
    const registroContainer = document.getElementById('registro-container');
    const loginContainer = document.getElementById('login-container');

    toggleLogin.addEventListener('click', function(e) {
        e.preventDefault();
        if (registroContainer.style.display !== 'none') {
            registroContainer.style.display = 'none';
            loginContainer.style.display = 'block';
            toggleLogin.textContent = 'Registrarse';
        } else {
            registroContainer.style.display = 'block';
            loginContainer.style.display = 'none';
            toggleLogin.textContent = 'Iniciar Sesión';
        }
    });



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

updateCartDisplay();


document.getElementById('direcciones-select').addEventListener('change', function(e) {
        const addressId = e.target.value;
        if (addressId) {
            const selectedAddress = userAddresses.find(a => a.id == addressId);
            if (selectedAddress) {
                document.getElementById('calle').value = selectedAddress.roadName;
                document.getElementById('numero').value = selectedAddress.roadNumber;
            }
        }
});

document.getElementById('boton-añadir').addEventListener('click', function(e) {
    e.preventDefault();
    updateWallet();
});


    setInterval(function() {
        const pagoStep = document.getElementById('pago-step');
        if (pagoStep.style.display !== 'none') {
            updateWalletDisplay();
        }
    }, 1000);
    
    // Realizar compra button
    document.getElementById('realizar-compra').addEventListener('click', function() {
        createOrder();
    });

    showStep(currentStep);

});

function createUserFromForm(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const userData = {
        username: formData.get('name'),
        email: formData.get('email'),
        password: formData.get('password'),
        phone: formData.get('tel'),
        rol: 'user'
    };

    fetch('http://www.kebab.com/apiPhp/api_users.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(userData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Server response:', data);
        if (data.message && data.message.includes("Usuario creado con éxito")) {
            alert('Usuario creado con éxito');
            if (data.user && data.user.id) {
                userId = data.user.id;
                document.getElementById('nombre-destinatario').value = userData.username;
                document.getElementById('telefono-destinatario').value = userData.phone;
                nextStep();
            } else {
                console.warn('User ID not found in response.');
                alert('Usuario creado con éxito. Por favor, inicie sesión.');
            }
        } else {
            throw new Error(data.message || 'Error desconocido al crear el usuario');
        }
    })
    .catch((error) => {
        console.error('Error details:', error);
        alert('Ocurrió un error al crear el usuario: ' + error.message);
    });
}




function loginUser(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const email = formData.get('email');
    const password = formData.get('password');

    console.log('Enviando datos:', { email, password, action: 'login' });

    fetch('http://www.kebab.com/apiPhp/api_users.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'login',
            email: email,
            password: password
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP error! status: ${response.status}, message: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Respuesta del servidor:', data);
        if (data.success) {
            userId = data.user.id;
            document.getElementById('nombre-destinatario').value = data.user.username || '';
            document.getElementById('telefono-destinatario').value = data.user.phone || '';
            loadUserAddresses(userId);
            nextStep();
        } else {
            alert('Error de inicio de sesión: ' + data.message);
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('Ocurrió un error al iniciar sesión: ' + (error.message || 'Error desconocido'));
    });
}



function addToCart(product) {
    console.log('Añadiendo al carrito:', product);
    
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let existingProductIndex = cart.findIndex(item => 
        item.id === product.id && 
        JSON.stringify(item.ingredients) === JSON.stringify(product.ingredients)
    );
    
    if (existingProductIndex !== -1) {
        cart[existingProductIndex].quantity = (cart[existingProductIndex].quantity || 1) + 1;
        console.log('Producto existente actualizado:', cart[existingProductIndex]);
    } else {
        product.quantity = 1;
        cart.push(product);
        console.log('Nuevo producto añadido:', product);
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    console.log('Carrito actualizado:', cart);
    
    updateCartDisplay();
}


function removeFromCart(productID) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let productIndex = cart.findIndex(item => item.id === productID);
    
    if (productIndex !== -1) {
        if (cart[productIndex].quantity > 1) {
            cart[productIndex].quantity -= 1;
        } else {
            cart.splice(productIndex, 1);
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartDisplay();
    }
}


function updateCartDisplay() {
    const cartItems = document.getElementById('cart-items');
    const cartSubtotal = document.getElementById('cart-subtotal');
    const cartTotal = document.getElementById('cart-total');
    const cartCounter = document.getElementById('cart-counter');
    const cart = JSON.parse(localStorage.getItem('cart')) || [];

    cartItems.innerHTML = '';
    let subtotal = 0;
    let itemCount = 0;

    cart.forEach(item => {
        console.log('Procesando item:', item);
        const itemTotal = item.totalPrice * item.quantity;
        subtotal += itemTotal;
        itemCount += item.quantity;

        let itemDetails = `
            <div class="cart-item">
                <div class="cart-item-details">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-price">${itemTotal.toFixed(2)}€</div>
                </div>
                <div class="cart-item-quantity">
                    <button onclick="removeFromCart(${item.id})">-</button>
                    ${item.quantity}
                    <button onclick="addToCart(${JSON.stringify(item).replace(/"/g, '&quot;')})">+</button>
                </div>
        `;

        if (item.ingredients && item.ingredients.length > 0) {
            itemDetails += `
                <div class="cart-item-ingredients">
                    <ul>
            `;
            item.ingredients.forEach(ingredient => {
                itemDetails += `<li>${ingredient.name}</li>`;
            });
            itemDetails += `
                    </ul>
                </div>
            `;
        }

        itemDetails += `</div>`;

        cartItems.innerHTML += itemDetails;
    });

    cartSubtotal.textContent = subtotal.toFixed(2) + '€';
    const total = subtotal * 1.10; // Suponiendo un 21% de IVA
    cartTotal.textContent = total.toFixed(2) + '€';
    cartCounter.textContent = 'Artículos añadidos: ' + itemCount;
}

function cartWithDB(userID) {
    if (!userID) {
        console.error('User ID is undefined');
        alert('Error: ID de usuario no disponible. Por favor, inicie sesión nuevamente.');
        return;
    }

    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        console.log('El carrito está vacío');
        alert('El carrito está vacío. Añada productos antes de realizar un pedido.');
        return;
    }

    const orderData = {
        datetime: new Date().toISOString(),
        state: 'pendiente',
        totalPrice: cart.reduce((total, item) => total + (item.totalPrice * item.quantity), 0),
        userID: userID,
        orderLines: cart.map(item => ({
            price: item.totalPrice * item.quantity,
            quantity: item.quantity,
            json: JSON.stringify(item),
            kebabId: item.id
        }))
    };

    console.log('Enviando datos del pedido:', orderData);

    fetch('http://www.kebab.com/apiPhp/api_orders.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(orderData)
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error('Error en la respuesta del servidor: ' + text);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.message && data.message.includes("Pedido creado con éxito")) {
            console.log('Pedido creado en la base de datos');
            localStorage.removeItem('cart');
            updateCartDisplay();
            alert('Pedido creado con éxito');
        } else {
            throw new Error('Error al crear el pedido: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch((error) => {
        console.error('Ocurrió un error al crear el pedido:', error);
        alert('Error al crear el pedido: ' + error.message);
    });
}

function loadUserAddresses(userId) {
    fetch(`http://www.kebab.com/apiPhp/api_addresses.php?user_Id=${userId}`)
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Server response:', text);
                    throw new Error(`HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta de la API de direcciones:', data);
            
            if (data.error) {
                throw new Error(data.error);
            }

            if (!Array.isArray(data)) {
                throw new Error('La respuesta no tiene el formato esperado');
            }

            const select = document.getElementById('direcciones-select');
            if (!select) {
                console.error('No se encontró el elemento select de direcciones');
                return;
            }

            select.innerHTML = '<option value="">Seleccione una dirección existente</option>';
            
            if (data.length === 0) {
                console.log('No se encontraron direcciones para este usuario');
                select.innerHTML += '<option value="" disabled>No hay direcciones disponibles</option>';
            } else {
                data.forEach(address => {
                    if (address && address.roadName && address.roadNumber) {
                        const option = document.createElement('option');
                        option.value = address.id;
                        option.textContent = `${address.roadName} ${address.roadNumber}`;
                        option.dataset.roadName = address.roadName;
                        option.dataset.roadNumber = address.roadNumber;
                        select.appendChild(option);
                    }
                });
            }

            // Añadir evento de cambio al select
            select.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const calleInput = document.getElementById('calle');
                const numeroInput = document.getElementById('numero');

                if (selectedOption.value) {
                    calleInput.value = selectedOption.dataset.roadName;
                    numeroInput.value = selectedOption.dataset.roadNumber;
                } else {
                    calleInput.value = '';
                    numeroInput.value = '';
                }
            });
        })
        .catch(error => {
            console.error('Error loading addresses:', error);
            alert('Error al cargar las direcciones: ' + error.message);
        });
}


function addAddress() {
    const calleInput = document.getElementById('calle');
    const numeroInput = document.getElementById('numero');

    if (!calleInput || !numeroInput) {
        console.error('No se encontraron los campos de dirección');
        alert('Error: No se pueden encontrar los campos de dirección. Por favor, recargue la página.');
        return;
    }

    const calle = calleInput.value.trim();
    const numero = numeroInput.value.trim();

    if (!calle || !numero) {
        alert('Por favor, complete todos los campos de la dirección.');
        return;
    }

    if (!userId) {
        console.error('User ID is not set');
        alert('Error: Por favor, inicie sesión antes de añadir una dirección.');
        return;
    }

    const addressData = {
        roadName: calle,
        roadNumber: numero,
        userId: userId
    };

    fetch('http://www.kebab.com/apiPhp/api_addresses.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(addressData)
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Server response:', text);
                throw new Error(`HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Address added successfully:', data);
        alert('Dirección añadida con éxito');
        loadUserAddresses(userId);
        // Limpiar los campos después de añadir la dirección
        calleInput.value = '';
        numeroInput.value = '';
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('Ocurrió un error al añadir la dirección. Por favor, revise la consola para más detalles.');
    });
}

    function saveDeliveryInfo() {
        const horaEntrega = document.getElementById('hora-entrega').value;
        localStorage.setItem('horaEntrega', horaEntrega);
    }

    function getCurrentFormattedDateTime() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    
    // Obtener la hora de entrega del localStorage
    const horaEntrega = localStorage.getItem('horaEntrega');
    let hours, minutes;
    
    if (horaEntrega) {
        [hours, minutes] = horaEntrega.split(':');
    } else {
        // Si no hay hora de entrega, usar la hora actual
        hours = String(now.getHours()).padStart(2, '0');
        minutes = String(now.getMinutes()).padStart(2, '0');
    }
    
    const seconds = '00'; 

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}
function createOrder() {
    const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
    const horaEntrega = localStorage.getItem('horaEntrega');

    // Calcular el precio total
    const totalPrice = cartItems.reduce((total, item) => total + (item.totalPrice * item.quantity), 0);

    // Crear las líneas de pedido
    const orderLines = cartItems.map(item => ({
        quantity: item.quantity,
        price: item.totalPrice * item.quantity,
        kebabs: [{
            id: item.id,
            name: item.name,
            price: item.price,
            ingredients: item.ingredients
        }]
    }));

    const orderData = {
        datetime: getCurrentFormattedDateTime(),
        state: 'pendiente',
        totalPrice: totalPrice,
        userID: userId,
        orderLines: orderLines // Añadir las líneas de pedido aquí
    };


    fetch('http://www.kebab.com/apiPhp/api_orders.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.orderId) {
            alert('¡Pedido realizado con éxito!');
            localStorage.removeItem('cart');
            localStorage.removeItem('horaEntrega');
            updateCartDisplay();
        } else {
            throw new Error(data.message || 'Error desconocido al realizar el pedido');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al realizar el pedido: ' + error.message);
    });
}





function updateWalletAfterPurchase(amount) {
    fetch('http://www.kebab.com/apiPhp/api_users.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'updateWallet',
            userId: userId,
            amount: -amount  // Restamos el monto del pedido
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message && data.message.includes("Monedero actualizado con éxito")) {
            updateWalletDisplay();
        } else {
            throw new Error(data.message || 'Error desconocido al actualizar el monedero');
        }
    })
    .catch(error => {
        console.error('Error al actualizar el monedero:', error);
        alert('Error al actualizar el monedero: ' + error.message);
    });
}

function getCurrentWalletBalance() {
    return fetch(`http://www.kebab.com/apiPhp/api_users.php?id=${userId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta completa de la API:', data); // Para depuración
            if (data && (typeof data.monedero !== 'undefined' || typeof data.wallet !== 'undefined')) {
                return parseFloat(data.monedero || data.wallet);
            } else {
                console.error('Estructura de datos inesperada:', data);
                throw new Error('No se pudo obtener el saldo del monedero');
            }
        })
        .catch(error => {
            console.error('Error al obtener el saldo:', error);
            throw error;
        });
}

    function updateWallet() {
        const amount = parseFloat(document.getElementById('cantidad-añadir').value);
        if (isNaN(amount) || amount <= 0) {
            alert('Por favor, ingrese una cantidad válida.');
            return;
        }
    
        if (!userId) {
            alert('Error: ID de usuario no disponible');
            return;
        }
    
        fetch('http://www.kebab.com/apiPhp/api_users.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'updateWallet',
                userId: userId,
                amount: amount
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Monedero actualizado con éxito');
                document.getElementById('credito-disponible').textContent = data.newWallet + '€';
                document.getElementById('cantidad-añadir').value = '';
                updateWalletDisplay();
            } else {
                throw new Error(data.message || 'Error desconocido al actualizar el monedero');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            alert('Ocurrió un error al actualizar el monedero: ' + (error.message || 'Error desconocido'));
        });
    }

    function updateWalletDisplay() {
    getCurrentWalletBalance()
        .then(balance => {
            const saldoElement = document.getElementById('credito-disponible');
            if (saldoElement) {
                saldoElement.textContent = balance.toFixed(2) + '€';
            } else {
                console.warn('Elemento "credito-disponible" no encontrado en el DOM');
            }
        })
        .catch(error => {
            console.error('Error al actualizar el display del monedero:', error);
            const saldoElement = document.getElementById('credito-disponible');
            if (saldoElement) {
                saldoElement.textContent = 'Error al cargar';
            } else {
                console.warn('Elemento "credito-disponible" no encontrado en el DOM');
            }
        });
}
</script>
<?php $this->stop() ?>

