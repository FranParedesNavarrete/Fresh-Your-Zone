// Función para recoger los productos seleccionados del carrito y mostrar el precio total
function showTotalPrice() {
    let checkboxes = document.querySelectorAll('.product-checked');
    let totalPriceZone = document.getElementById('totalPrice');
    
    checkboxes.forEach(product => {
        product.addEventListener('click', function () {
            let totalPrice = 0;

            let checkboxesSelelected = document.querySelectorAll('.product-checked:checked'); // Guarda todos los productos seleccionados
            
            // Si no hay productos seleccionados deshabilita el btn de comprar
            if (checkboxesSelelected.length > 0) {
                document.getElementById('buyBtn').disabled = false;
                document.getElementById('buyBtn').classList.remove('btn-secondary');
                document.getElementById('buyBtn').classList.add('btn-primary');
            } else {
                document.getElementById('buyBtn').disabled = true;
                document.getElementById('buyBtn').classList.remove('btn-primary');
                document.getElementById('buyBtn').classList.add('btn-secondary');
            }
    
            document.querySelectorAll('.product-checked:checked').forEach(checked => {
                let price = parseFloat(checked.dataset.price);
                totalPrice += price;
            });
    
            totalPriceZone.innerHTML = `Total: ${totalPrice.toFixed(2)}`;
        });
    });
}

// Función para agregar un producto al carrito 
function moveToShoppingCart(productId) {
    fetch(`/products/move-to-cart`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body : JSON.stringify({id: productId})
    })
    .then(response => {
        return response.json();
    })
    .then(data => {
        const shoppingCartIcon = document.getElementById('shoppingCartIcon');

        if (shoppingCartIcon) {
            shoppingCartIcon.classList.add('cart-animation');
    
            // Quitar la animación después de 5ms
            setTimeout(() => {
                shoppingCartIcon.classList.remove('cart-animation');
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function deleteFromCart(productId) {
    let totalPriceZone = document.getElementById('totalPrice');

    fetch(`/products/remove-from-cart`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body : JSON.stringify({id: productId})
    })
    .then(response => {
        return response.json();
    })
    .then(data => {
        let row = document.querySelector(`tr[row-id="${productId}"]`);
        let tableBody = row.parentElement;
    
        if (row) {
            row.remove();
            let totalPrice = 0;
    
            document.querySelectorAll('.product-checked:checked').forEach(checked => {
                let price = parseFloat(checked.dataset.price);
                totalPrice += price;
            });
    
            totalPriceZone.innerHTML = `Total: ${totalPrice.toFixed(2)}`;
        }
    
        let remainingRows = tableBody.querySelectorAll('tr[row-id]');
    
        if (remainingRows.length === 0) {
            // Añadir fila indicando que no tiene productos en el carrito
            const emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `<td colspan="5" class="text-center">No tienes productos en el carrito.</td>`;
            tableBody.appendChild(emptyRow);
            document.getElementById('totalPrice').innerHTML = "Total: 0";
        }
    })
    .catch(error => {
        console.log(error);
    });
}
 
function buyProducts(id = null, orderId = null) {
    let checkboxesSelelected = document.querySelectorAll('.product-checked:checked');
    if (!id) {
        let productsSelected = Array.from(checkboxesSelelected).map(product => product.value);
        id = productsSelected;
    } 

    fetch('/products/prepare-to-buy', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body : JSON.stringify({
            id: id,
            order_id: orderId
        })
    })
    .then(response => {
        window.location.href = '/orders/resume';
    })
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Función para alternar entre dirección y punto de entrega 
function toggleAddressDeliveryPoint() {
    let addressRadio = document.getElementById('use_address');
    let deliveryPointRadio = document.getElementById('use_deliveryPoint');
    let addressZone = document.getElementById('addressZone');
    let poinZone = document.getElementById('delivery_pointZone');

    function toggleSections() {
        if (addressRadio.checked) {
            addressZone.classList.remove('d-none');
            poinZone.classList.add('d-none');
        } else {
            addressZone.classList.add('d-none');
            poinZone.classList.remove('d-none');
        }
    }

    addressRadio.addEventListener('change', toggleSections);
    deliveryPointRadio.addEventListener('change', toggleSections);
}

// Función para comprobar el número de teléfono, si no existe lo pone en rojo y si se introduce un número correcto lo guarda en la base de datos y lo pone en verde
function checkPhoneNumber() {
    let phoneText = document.getElementById('phoneNumber');
    let phoneInput = document.getElementById('phone_number');

    if (!phoneText) {
        phoneInput.style.borderColor = 'red';
        phoneInput.style.borderWidth = '2px';
        phoneInput.style.borderStyle = 'solid';

    }

    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            if (phoneInput.value.length == 9) {
                fetch('/profile/save-phone', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body : JSON.stringify({phone_number: phoneInput.value})
                })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    if (data.success == false) {
                        alert('El número de teléfono ya existe');
                    } else {
                        phoneInput.style.borderColor = 'green';
                        phoneInput.style.borderWidth = '2px';
                        phoneInput.style.borderStyle = 'solid';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            } else {
                phoneInput.style.borderColor = 'red';
                phoneInput.style.borderWidth = '2px';
                phoneInput.style.borderStyle = 'solid';
            }
        });
    }

}

function checkAddress() {
    let adressSelected = document.getElementById('use_address');
    let addressInput = document.getElementById('address');

    if (adressSelected.checked) {
        if (addressInput.value == '') {
            addressInput.style.borderColor = 'red';
            addressInput.style.borderWidth = '2px';
            addressInput.style.borderStyle = 'solid';
        }

        if (addressInput) {
            addressInput.addEventListener('blur', function() {
                if (addressInput.value.length > 0) {
                    addressInput.style.borderColor = 'green';
                    addressInput.style.borderWidth = '2px';
                    addressInput.style.borderStyle = 'solid';
        
                    fetch('/profile/save-address', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body : JSON.stringify({address: addressInput.value})
                    })
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        console.log(data)
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                } else {
                    addressInput.style.borderColor = 'red';
                    addressInput.style.borderWidth = '2px';
                    addressInput.style.borderStyle = 'solid';
                }
            });
        }
    }
}

function updateOrderStatus(order) {
    let orderStatus = document.getElementById(order.id);

    fetch('/admin/orders/update-status', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            status: orderStatus.value,
            order_id: order.id
        })
    })
    .then(response => {
        return response.json();
    })
    .then(data => {
        if (data.success) {
            order.style.borderColor = 'green';
            order.style.borderWidth = '2px';
            order.style.borderStyle = 'solid';
        } else {
            order.style.borderColor = 'red';
            order.style.borderWidth = '2px';
            order.style.borderStyle = 'solid';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });

}