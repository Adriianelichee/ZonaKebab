<?php $this->layout('SeeOrdersLayout'); ?>


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

<?php $this->start('body') ?>
<section class="orders">
    <h1>Ver pedidos</h1>
    <?php foreach ($orders as $order) : ?>
        <div class="order-card">
            <p><strong>ID del Pedido:</strong> <?= htmlspecialchars($order->getIdOrder()) ?></p>
            <p><strong>Fecha y hora:</strong> <?= htmlspecialchars($order->getDatetime()) ?></p>
            <p><strong>Estado:</strong> <?= htmlspecialchars($order->getState()) ?></p>
            
            <!-- Botones para cambiar el estado -->
            <div class="order-state-buttons">
                <button class="state-button" data-order-id="<?= $order->getIdOrder() ?>" data-state="pendiente">Pendiente</button>
                <button class="state-button" data-order-id="<?= $order->getIdOrder() ?>" data-state="en_preparacion">En Preparación</button>
                <button class="state-button" data-order-id="<?= $order->getIdOrder() ?>" data-state="listo">Listo</button>
                <button class="state-button" data-order-id="<?= $order->getIdOrder() ?>" data-state="entregado">Entregado</button>
            </div>

            <p><strong>Precio total:</strong> <?= htmlspecialchars($order->getTotalPrice()) ?> €</p>
            <p><strong>ID de Usuario:</strong> <?= htmlspecialchars($order->getUserID()) ?></p>

            <h3>Líneas de pedido:</h3>
            <?php foreach ($order->getOrderLines() as $orderLine) : ?>
                <div class="order-line">
                    <p><strong>Cantidad:</strong> <?= htmlspecialchars($orderLine->getQuantity()) ?></p>
                    <h4>Kebabs:</h4>
                    <?php foreach ($orderLine->getKebabs() as $kebab) : ?>
                        <div class="kebab-details">
                            <p><strong>Nombre:</strong> <?= htmlspecialchars($kebab->getName()) ?></p>
                            <p><strong>Precio base:</strong> <?= htmlspecialchars($kebab->getBasePrice()) ?> €</p>
                            <?php if ($kebab->getIngredients()) : ?>
                                <h5>Ingredientes:</h5>
                                <ul class="ingredients-list">
                                    <?php foreach ($kebab->getIngredients() as $ingredient) : ?>
                                        <li><?= htmlspecialchars($ingredient->getName()) ?> (<?= htmlspecialchars($ingredient->getPrice()) ?> €)</li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <p>No hay ingredientes especificados para este kebab.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
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

<?php $this->start('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Manejo de la navegación secundaria
    const links = document.querySelectorAll('.secondary-nav .secondary-link');
    let activeLink = null;

    // Buscar el enlace "Añadir Ingrediente" y establecerlo como activo
    const addIngredientLink = document.querySelector('.secondary-nav .secondary-link:nth-child(7)');
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

    const stateButtons = document.querySelectorAll('.state-button');
    stateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const newState = this.getAttribute('data-state');
            updateOrderState(orderId, newState);
        });
    });

    function updateOrderState(orderId, newState) {
    fetch('/apiPhp/api_orders.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'updateState',
            orderId: orderId,
            newState: newState
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Estado del pedido actualizado con éxito');
            location.reload();
        } else {
            alert('Error al actualizar el estado del pedido: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el estado del pedido: ' + error.message);
    });
}
});
</script>
<?php $this->stop() ?>