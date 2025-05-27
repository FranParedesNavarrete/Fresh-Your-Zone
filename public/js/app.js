let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let localFavs = JSON.parse(localStorage.getItem('favs')) || [];
let favs = Array.from(new Set([...localFavs, ...(window.favoritesFromServer || [])]));
localStorage.setItem('favs', JSON.stringify(favs));


 // Funcion para que la ventana se refresque cada 10s
 function refreshWindow() {
    setTimeout(() => {
        location.reload();
    }, 100);
}
//refreshWindow();

document.addEventListener('DOMContentLoaded', function() {
    changeDarkmodeClass();

    showTotalPrice();

    toggleAddressDeliveryPoint();

    checkPhoneNumber();

    checkAddress();

    showTooltips();

    updateOrderStatus();

    toggleShippingInputs();

});

function showTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function changeDarkmodeClass() {
    let darkmodeBtn = document.querySelector('.darkmodeBtn');
    let darkmodeIcon = document.getElementById('darkmodeIcon');

    let logoFZY = document.querySelectorAll('.logo-fzy');

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

function darkmodeTogle() {
    let currentMode = localStorage.getItem('darkmode');
    let newValue = (currentMode === 'true') ? 'false' : 'true';
    localStorage.setItem('darkmode', newValue);

    changeDarkmodeClass();
}

// Función para alternar favorito
function toggleFav(icon, productId, isAuth) {
    if (!isAuth) {
        window.location.href = '/login';
        return;
    }

    productId = Number(productId);

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

    if (isActive) {
        icon.classList.add('text-danger');
    }
}

// Función para agregar un producto a la lista de favoritos
function addFavorite(productId, icon) {    
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
    let row = document.querySelector(`tr[row-id="${productId}"]`);

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
        favs = favs.filter(id => id !== productId);
        localStorage.setItem('favs', JSON.stringify(favs));
        updateFavIcon(icon, false);
        console.log('Favorito eliminado');
    })
    .catch(error => {
        let row = document.querySelector(`tr[row-id="${productId}"]`);
        let tableBody = row.parentElement;
    
        if (row) {
            row.remove();
        }
    
        let remainingRows = tableBody.querySelectorAll('tr[row-id]');
    
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
