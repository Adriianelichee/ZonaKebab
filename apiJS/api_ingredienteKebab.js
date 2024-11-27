document.addEventListener('DOMContentLoaded', () => {
    getAllIngredients();
})

function createIngredient(name, price, photo) {
    fetch('api_ingrediente.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name: name,
            price: price,
            photo: photo
        }),
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Ingrediente creado con éxito');
            // Actualizar la lista de ingredientes
        } else {
            alert('Error al crear el ingrediente');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

function getAllIngredients() {
    fetch('http://www.kebab.com/apiPhp/api_ingredienteKebab.php')
    .then(response => response.json())
    .then(data => {
            displayIngredients(data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

function displayIngredients(ingredients) {
    const availableIngredientsContainer = document.getElementById('available-ingredients');
    const addedIngredientsContainer = document.getElementById('added-ingredients');
    
    if (!availableIngredientsContainer || !addedIngredientsContainer) {
        console.error('No se encontró uno de los contenedores de ingredientes');
        return;
    }

    availableIngredientsContainer.innerHTML = '';

    // Obtener los IDs de los ingredientes añadidos
    const addedIngredientIds = Array.from(addedIngredientsContainer.children).map(el => el.dataset.id);

    ingredients.forEach(ingredient => {
        // Comprobar si el ingrediente no está en la lista de añadidos
        if (!addedIngredientIds.includes(ingredient.idIngredient.toString())) {
            const ingredientElement = createIngredientElement(ingredient);
            availableIngredientsContainer.appendChild(ingredientElement);
        }
    });

    setupDragAndDrop();
}

function createIngredientElement(ingredient) {
    const ingredientElement = document.createElement('div');
    ingredientElement.classList.add('ingredient-item');
    ingredientElement.draggable = true;
    
    // Usa el idIngredient si está disponible, de lo contrario usa id
    const id = ingredient.idIngredient || ingredient.id;
    
    if (id !== undefined) {
        ingredientElement.dataset.id = id;
    } else {
        console.warn('Advertencia: El ingrediente no tiene un ID definido', ingredient);
    }
    
    ingredientElement.dataset.name = ingredient.name || '';
    ingredientElement.dataset.price = ingredient.price || '';

    const img = document.createElement('img');
    // Usamos la imagen en base64 directamente
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





function addIngredientToKebab(ingredientElement) {
    if (!ingredientElement) return;

    const availableIngredientsContainer = document.getElementById('available-ingredients');
    const addedIngredientsContainer = document.querySelector('.ingredients-added');

    if (!addedIngredientsContainer) {
        console.error('El contenedor de ingredientes añadidos no se encontró');
        return;
    }

    // Crear una copia del ingrediente para añadirlo a la sección de ingredientes añadidos
    const addedIngredient = ingredientElement.cloneNode(true);
    addedIngredient.removeEventListener('click', () => addIngredientToKebab(addedIngredient));
    addedIngredient.addEventListener('click', () => removeIngredientFromKebab(addedIngredient));

    // Añadir el ingrediente a la sección de ingredientes añadidos
    addedIngredientsContainer.appendChild(addedIngredient);

    // Eliminar el ingrediente de la lista de ingredientes disponibles
    availableIngredientsContainer.removeChild(ingredientElement);

    // Actualizar el precio del kebab
    // const price = parseFloat(ingredientElement.querySelector('span:last-child').textContent);
    // updatePriceKebab(price);
}

function removeIngredientFromKebab(addedIngredient) {
    if (!addedIngredient) return;

    const availableIngredientsContainer = document.getElementById('available-ingredients');
    const addedIngredientsContainer = document.querySelector('.ingredients-added');

    // Crear una copia del ingrediente para devolverlo a la sección de ingredientes disponibles
    const availableIngredient = addedIngredient.cloneNode(true);
    availableIngredient.removeEventListener('click', () => removeIngredientFromKebab(availableIngredient));
    availableIngredient.addEventListener('click', () => addIngredientToKebab(availableIngredient));

    // Añadir el ingrediente de vuelta a la sección de ingredientes disponibles
    availableIngredientsContainer.appendChild(availableIngredient);

    // Eliminar el ingrediente de la sección de ingredientes añadidos
    addedIngredientsContainer.removeChild(addedIngredient);

    // Actualizar el precio del kebab
    // const price = parseFloat(addedIngredient.querySelector('span:last-child').textContent);
    // updatePriceKebab(-price);
}

// function updatePriceKebab(price) {
    // Implementa esta función para actualizar el precio del kebab
//     const totalPriceElement = document.getElementById('kebab-price');
//     let currentPrice = parseFloat(totalPriceElement.textContent);
//     currentPrice += additionalPrice;
//     totalPriceElement.textContent = currentPrice.toFixed(2);
// }

function displayIngredientDetails(ingredients) {
    // Implementa esta función para actualizar tu UI
}

function getIngredient(id) {
    fetch(`api_ingrediente.php?id=${id}`)
    .then(response => response.json())
    .then(data => {
        // Mostrar los detalles del ingrediente
        displayIngredientDetails(data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

function updateIngredient(id, name, price, photo) {
    fetch(`api_ingrediente.php?id=${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name: name,
            price: price,
            photo: photo
        }),
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Ingrediente actualizado con éxito');
            // Actualizar la lista de ingredientes
        } else {
            alert('Error al actualizar el ingrediente');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

function deleteIngredient(id) {
    fetch(`api_ingrediente.php?id=${id}`, {
        method: 'DELETE',
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Ingrediente eliminado con éxito');
            // Actualizar la lista de ingredientes
        } else {
            alert('Error al eliminar el ingrediente');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}
