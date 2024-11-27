document.addEventListener('DOMContentLoaded', function() {
    const searchButton = document.getElementById('searchKebabButton');
    const searchInput = document.getElementById('searchKebabInput');
    const kebabList = document.getElementById('kebabList');
    const addButton = document.querySelector('.add-button');

    // Ocultar el botón de añadir inicialmente
    if (addButton) {
        addButton.style.display = 'none';
    }

    if (searchButton && searchInput) {
        searchButton.addEventListener('click', function() {
            const searchTerm = searchInput.value.trim();
            loadKebabs(searchTerm);
        });
    } else {
        console.error('Search button or input not found');
    }

    // Cargar kebabs inicialmente
    loadKebabs();
});



function loadKebabs(searchTerm = '') {
    fetch(`http://www.kebab.com/apiPhp/api_kebab.php?search=${searchTerm}`)
        .then(response => response.json())
        .then(data => {
            // Asegúrate de que cada kebab tenga la información completa de sus ingredientes
            const kebabsWithIngredients = data.map(kebab => {
                if (kebab.ingredients && Array.isArray(kebab.ingredients)) {
                    kebab.ingredients = kebab.ingredients.map(ingredient => {
                        // Si el ingrediente no tiene una foto, asigna una por defecto o déjala vacía
                        if (!ingredient.photo) {
                            ingredient.photo = ''; // O asigna una imagen por defecto
                        }
                        return ingredient;
                    });
                } else {
                    kebab.ingredients = []; // Si no hay ingredientes, inicializa como un array vacío
                }
                return kebab;
            });
            displayKebabs(kebabsWithIngredients);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

function displayKebabs(kebabs) {
    const kebabList = document.getElementById('kebabList');
    if (kebabList) {
        kebabList.innerHTML = '';
        kebabs.forEach(kebab => {
            const kebabItem = document.createElement('div');
            kebabItem.classList.add('kebab-item');
            kebabItem.textContent = kebab.name;
            kebabItem.addEventListener('click', () => selectKebab(kebab));
            kebabList.appendChild(kebabItem);
        });
    } else {
        console.error('Element with id "kebabList" not found');
    }
}

function selectKebab(kebab) {
    console.log('Kebab seleccionado:', kebab);

    document.getElementById('kebab-title').value = kebab.name;
    document.getElementById('kebab-price').value = kebab.basePrice;
    
    // Actualizar la imagen del kebab
    const photoPreview = document.getElementById('photo-preview-image');
    const photoPlaceholder = document.getElementById('photo-placeholder');
    if (kebab.photo && kebab.photo !== 'undefined' && kebab.photo !== '') {
        photoPreview.src = `data:image/jpeg;base64,${kebab.photo}`;
        photoPreview.style.display = 'block';
        photoPlaceholder.style.display = 'none';
    } else {
        photoPreview.style.display = 'none';
        photoPlaceholder.style.display = 'block';
    }

    // Actualizar los ingredientes añadidos
    const addedIngredientsContainer = document.getElementById('added-ingredients');
    addedIngredientsContainer.innerHTML = '';
    if (kebab.ingredients && Array.isArray(kebab.ingredients)) {
        kebab.ingredients.forEach(ingredient => {
            const ingredientElement = createIngredientElement(ingredient);
            ingredientElement.addEventListener('click', () => removeIngredientFromKebab(ingredientElement));
            addedIngredientsContainer.appendChild(ingredientElement);
        });
    }

    // Actualizar la lista de ingredientes disponibles
    getAllIngredients().then(allIngredients => {
        const availableIngredients = allIngredients.filter(ingredient => 
            !kebab.ingredients || !kebab.ingredients.some(kebabIngredient => kebabIngredient.idIngredient === ingredient.idIngredient)
        );
        displayIngredients(availableIngredients);
    }).catch(error => {
        console.error('Error al obtener todos los ingredientes:', error);
    });

    // Mostrar y cambiar el texto del botón de envío
    const addButton = document.querySelector('.add-button');
    if (addButton) {
        addButton.style.display = 'block';
        addButton.textContent = 'Actualizar';
    }

    // Cambiar el título del formulario
    const formTitle = document.querySelector('.main-content h1');
    if (formTitle) {
        formTitle.textContent = 'Editar Kebab de la Casa';
    }

    document.getElementById('addKebabForm').dataset.kebabId = kebab.id;

    // Cerrar la ventana de búsqueda
    const searchOverlay = document.getElementById('kebabSearchOverlay');
    if (searchOverlay) {
        searchOverlay.style.display = 'none';
    }
}


document.getElementById('addKebabForm').addEventListener('submit', updateKebabFromForm);


function getAllKebabs() {
    fetch('http://www.kebab.com/apiPhp/api_kebab.php')
       .then(response => response.json())
       .then(data => {
            // displayKebabs(data);
        })
       .catch((error) => {
            console.error('Error:', error);
        });
}

function getKebabById(id) {
    fetch(`http://www.kebab.com/apiPhp/api_kebab.php?id=${id}`)
       .then(response => response.json())
       .then(data => {
            displayKebab(data);
        })
       .catch((error) => {
            console.error('Error:', error);
        });
}

function addKebab(formData) {
    console.log('Iniciando addKebab...');
    console.log('Datos del FormData:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }

    fetch('http://www.kebab.com/apiPhp/api_kebab.php', {
        method: 'POST',
        body: formData
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
            getAllKebabs();
        } else {
            alert('Error al añadir el kebab: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch((error) => {
        console.error('Error en la solicitud:', error);
        alert('Error al añadir el kebab: ' + error.message);
    });
}



document.getElementById('kebab-photo').addEventListener('change', function(e) {
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

function updateKebab(kebab) {
    fetch(`http://www.kebab.com/apiPhp/api_kebab.php?id=${kebab.id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(kebab)
    })
       .then(response => response.json())
       .then(data => {
            displayKebab(data);
        })
       .catch((error) => {
            console.error('Error:', error);
        });
}

function deleteKebab(id) {
    fetch(`http://www.kebab.com/apiPhp/api_kebab.php?id=${id}`, {
        method: 'DELETE'
    })
       .then(response => response.json())
       .then(data => {
            displayKebabs(data);
        })
       .catch((error) => {
            console.error('Error:', error);
        });
}



function updateKebabFromForm(e) {
    e.preventDefault();
    console.log('updateKebabFromForm() llamada');

    const kebabId = this.dataset.kebabId;
    if (!kebabId) {
        console.error('No se ha seleccionado ningún kebab para actualizar');
        return;
    }

    const name = document.getElementById('kebab-title').value;
    const basePrice = parseFloat(document.getElementById('kebab-price').value);
    const photoInput = document.getElementById('kebab-photo');
    const photo = photoInput.files[0];

    const addedIngredients = Array.from(document.querySelectorAll('#added-ingredients .ingredient-item'))
        .map(item => parseInt(item.dataset.id));

    const formData = new FormData();
    formData.append('id', kebabId);
    formData.append('name', name);
    formData.append('basePrice', basePrice);
    formData.append('ingredients', JSON.stringify(addedIngredients));

    if (photo) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const base64Image = e.target.result.split(',')[1];
            formData.append('photo', base64Image);
            sendKebabUpdateData(formData);
        };
        reader.readAsDataURL(photo);
    } else {
        sendKebabUpdateData(formData);
    }
}

function sendKebabUpdateData(formData) {
    const kebabId = formData.get('id');
    const url = `http://www.kebab.com/apiPhp/api_kebab.php?id=${kebabId}`;

    // Convertir FormData a un objeto simple
    const kebabData = {};
    for (let [key, value] of formData.entries()) {
        kebabData[key] = value;
    }

    // Obtener los ingredientes añadidos
    const addedIngredients = Array.from(document.querySelectorAll('#added-ingredients .ingredient-item'))
        .map(item => parseInt(item.dataset.id));

    // Añadir los ingredientes al objeto kebabData
    kebabData.ingredients = JSON.stringify(addedIngredients);

    fetch(url, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(kebabData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            return response.json();
        } else {
            return response.text().then(text => {
                throw new Error('Respuesta del servidor no válida: ' + text);
            });
        }
    })
    .then(data => {
        if (data.success) {
            alert('Kebab actualizado con éxito');
            loadKebabs(); // Recargar la lista de kebabs
        } else {
            alert('Error al actualizar el kebab: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch((error) => {
        console.error('Error en la solicitud:', error);
        alert('Error al actualizar el kebab: ' + error.message);
    });
}

function getAllIngredients() {
    return fetch('http://www.kebab.com/apiPhp/api_ingredienteKebab.php')
        .then(response => response.json())
        .catch((error) => {
            console.error('Error:', error);
            throw error;
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
    console.log('Imagen de ingrediente:', ingredient.photo);
    img.src = `data:image/jpeg;base64,${ingredient.photo}`;
    img.alt = ingredient.name;

    const span = document.createElement('span');
    span.textContent = ingredient.name;

    ingredientElement.appendChild(img);
    ingredientElement.appendChild(span);

    return ingredientElement;
}
