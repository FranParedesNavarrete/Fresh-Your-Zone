// Archivo JS principal de la aplicación, este archivo contiene las funciones generales

let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Guardar el CSRF Token para las peticiones
let localFavs = JSON.parse(localStorage.getItem('favs')) || []; // Guardar los favoritos del localStorage en caso de que hayan
let favs = Array.from(new Set([...localFavs, ...(window.favoritesFromServer || [])])); // Guardar los favoritos que se reciben de la base de datos si los hay
localStorage.setItem('favs', JSON.stringify(favs)); // Guardar los favoritos en el localStorage


 // Funcion para que la ventana se refresque cada 10s
 function refreshWindow() {
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Evento principal que llama a las funciones al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    //refreshWindow();

    changeDarkmodeClass();

    showTotalPrice();

    toggleAddressDeliveryPoint();

    checkPhoneNumber();

    checkAddress();

    showTooltips();

    updateOrderStatus();

    toggleShippingInputs();

});

// Función para permitir mostrar los tooltips de la aplicación
function showTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Función para permitir el modo oscuro y claro, cambia la clase darkmode del documento por lightmode y viceversa. Tambien se encarga de cambiar el logo de la aplicación segun el modo escogido

function changeDarkmodeClass() {
    let darkmodeBtn = document.querySelector('.darkmodeBtn');
    let darkmodeIcon = document.getElementById('darkmodeIcon');

    let logoFZY = document.querySelectorAll('.logo-fzy');

    // Comprueba el modo guardado en el localStorage, si es true significa que el modo oscuro está activo, si es false el modo claro esta activo
    if(localStorage.getItem('darkmode') === 'true') {
        document.body.classList.remove('lightmode');
        document.body.classList.add('darkmode');

        darkmodeIcon.classList.remove('bi-moon-fill');
        darkmodeIcon.classList.add('bi-sun-fill', 'text-dark');
        darkmodeBtn.style.backgroundColor = '#d4cdc5';

        logoFZY.forEach(logo => {
            logo.src = '/assets/images/logo/fzy-logo-light.png';
        });

    }else {
        document.body.classList.remove('darkmode');
        document.body.classList.add('lightmode');

        darkmodeIcon.classList.remove('bi-sun-fill', 'text-dark');
        darkmodeIcon.classList.add('bi-moon-fill');
        darkmodeBtn.style.backgroundColor = '#262525';

        logoFZY.forEach(logo => {
            logo.src = '/assets/images/logo/fzy-logo-dark.png';
        });
    }
}

// Función para intercambiar entre modo oscuro y claro, se activa al pulsar el botón con id 'darkmodeIcon'
function darkmodeTogle() {
    let currentMode = localStorage.getItem('darkmode'); // Guarda el valor del localStorage
    let newValue = (currentMode === 'true') ? 'false' : 'true'; // Si el valor es true lo cambia a false, si no hay valor o es false guarda el valor en true
    localStorage.setItem('darkmode', newValue);

    changeDarkmodeClass(); // Llama a la función para cambiar las clases del documento
}

// Función para alternar favorito
function toggleFav(icon, productId, isAuth) {
    // Si no se está autenticado se redirige al formulario de inicio de sesión
    if (!isAuth) {
        window.location.href = '/login';
        return;
    }

    // Si esta autenticado el usuario guarda el id del producto
    productId = Number(productId);

    // Si el producto ya está guardado en el localStorage lo elimina, si no está lo guarda en el localStorage
    if (favs.includes(productId)) {
        deleteFavorite(productId, icon);
    } else {
        addFavorite(productId, icon);
    }
}

// Cambia el icono Bootstrap de 'heart' o 'heart-fill' según el estado del favorito
function updateFavIcon(icon, isActive) {
    icon.classList.remove('bi-heart', 'bi-heart-fill', 'text-danger');
    icon.classList.add(isActive ? 'bi-heart-fill' : 'bi-heart');

    // Si está activo añade la clase 'text-danger' de Bootstrap para que el icono se ponga rojo
    if (isActive) {
        icon.classList.add('text-danger');
    }
}

// Función para agregar un producto a la lista de favoritos
function addFavorite(productId, icon) {   
    // Se hace una petición AJAX de tipo POST para enviar al controlador el id del producto 
    fetch(`/favorites/add/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al agregar el favorito');
        }
        return response.json();
    })
    .then(data => {
        // Guarda el id del producto en el localStorage, acutaliza el icono y añade una pequeña animación de tipo respiración al icono de favoritos del header
        favs.push(productId);
        localStorage.setItem('favs', JSON.stringify(favs));
        updateFavIcon(icon, true);

        document.getElementById('favoriteBtn').classList.add('cart-animation');
    
        // Quitar la animación después de 5ms
        setTimeout(() => {
            document.getElementById('favoriteBtn').classList.remove('cart-animation');
        }, 2000);

        console.log('Favorito agregado');
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Función para eliminar un producto de la lista de favoritos
function deleteFavorite(productId, icon) {
    let row = document.querySelector(`tr[row-id="${productId}"]`); // Guarda la fila donde esta el producto a eliminar de favoritos

    // Se hace una petición AJAX de tipo DELETE para enviar al controlador el id del producto a eliminar de favoritos
    fetch(`/favorites/delete/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al eliminar el favorito');
        }
        return response.json();
    })
    .then(data => {
        // Quita el id del producto eliminado de favoritos del localStorage y actualiza el icono
        favs = favs.filter(id => id !== productId);
        localStorage.setItem('favs', JSON.stringify(favs));
        updateFavIcon(icon, false);
        console.log('Favorito eliminado');
    })
    .catch(error => {
        // Elimina la fila de la tabla del producto que se elimina de favoritos y se actualiza la tabla dinamicamente para mejorar la experiencia de usuario
        let row = document.querySelector(`tr[row-id="${productId}"]`); // Guarda la fila donde esta el producto a eliminar de favoritos
        let tableBody = row.parentElement; // Guarda el tbody de la fila guardada
    
        // Si hay fila, la elimina de la tabla
        if (row) {
            row.remove();
        }
    
        // Guarda las filas restantes
        let remainingRows = tableBody.querySelectorAll('tr[row-id]');
    
        // Si el número de filas es 0 añade una nueva fila indicando que no hay favoritos guardados
        if (remainingRows.length === 0) {
            // Añadir fila indicando que no favoritos guardados
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `<td colspan="4" class="text-center">No tienes favoritos guardados.</td>`;
            tableBody.appendChild(emptyRow);
        }
    });
}

// Función para cambiar el rol a vendedor
function changeRoleToSeller() {
    // Se hace una petición AJAX de tipo POST para que desde el controlador se cambie el rol del usuario autenticado
    fetch('/profile/change-role', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al cambiar el rol');
        }
        return response.json();
    })
    .then(data => {
        // Si se cambia el rol con exito se redirige al usuario a la página del perfil de usuario
        if (data.success) {
            window.location.href = '/profile';
        } else {
            console.error('Error al cambiar el rol:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
