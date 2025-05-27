// Archivo JS para las funciones relacionadas a pedidos de la aplicación, este archivo contiene las funciones orientadas a gestionar los pedidos

// Función para recoger los productos seleccionados del carrito y mostrar el precio total
function showTotalPrice() {
    let checkboxes = document.querySelectorAll('.product-checked'); // Guarda todas las filas seleccionables
    let totalPriceZone = document.getElementById('totalPrice'); // Guarda el contenedor donde va a ir el precio total
    
    // Se itera cada fila seleccionable para añadir un evento 'click' para calcular el precio total segun los productos seleccionados
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
    
            // Calcula el precio total
            document.querySelectorAll('.product-checked:checked').forEach(checked => {
                let price = parseFloat(checked.dataset.price);
                totalPrice += price;
            });
    
            totalPriceZone.innerHTML = `Total: ${totalPrice.toFixed(2)}`; // Muestra por pantalla el precio total
        });
    });
}

// Función para agregar un producto al carrito 
function moveToShoppingCart(productId) {
    // Se hace una petición AJAX de tipo POST para enviar al controlador el id del producto que se desea guardar en el carrito
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
        // Guarda el icono del carrito del header para añadir una animación de respiración si se agrega el producto al carrito
        let shoppingCartIcon = document.getElementById('shoppingCartIcon');

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

// Función para eliminar productos del carrito, recibe el id a elimnar
function deleteFromCart(productId) {
    let totalPriceZone = document.getElementById('totalPrice'); // Guarda el contenedor donde va a ir el precio total

    // Hace una petición AJAX de tipo POST para enviar al controlador el id a eliminar del carrito 
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
        // Se guarda la fila del producto a elimnar del carrito
        let row = document.querySelector(`tr[row-id="${productId}"]`);
        let tableBody = row.parentElement;
    
        // Se elimina la fila y se calcula el nuevo precio total
        if (row) {
            row.remove();
            let totalPrice = 0;
    
            document.querySelectorAll('.product-checked:checked').forEach(checked => {
                let price = parseFloat(checked.dataset.price);
                totalPrice += price;
            });
    
            totalPriceZone.innerHTML = `Total: ${totalPrice.toFixed(2)}`;
        }
    
        let remainingRows = tableBody.querySelectorAll('tr[row-id]'); // Guarda el número de filas restantes 
    
        // Si el número de filas es 0 se añade una nueva fila indicando que ya no hay productos en el carrito de forma dinamica para mejorar la experiencia de usuario
        if (remainingRows.length === 0) {
            // Añadir fila indicando que no tiene productos en el carrito
            let emptyRow = document.createElement('tr');
            emptyRow.innerHTML = `<td colspan="5" class="text-center">No tienes productos en el carrito.</td>`;
            tableBody.appendChild(emptyRow);
            document.getElementById('totalPrice').innerHTML = "Total: 0";
        }
    })
    .catch(error => {
        console.log(error);
    });
}
 
// Función que guarda el producto o productos a comprar, si es solo un producto recibe el id del prodcuto a comprar, si son productos de la vista 'Carrito' recibe el id de pedido seleccionado
function buyProducts(id = null, orderId = null) {
    let checkboxesSelelected = document.querySelectorAll('.product-checked:checked'); // Guarda todos los productos seleccionados

    // Si no se recibe id, significa que son los productos del carrito, se recorre todos los productos seleccionados y se guarda el valor
    if (!id) {
        let productsSelected = Array.from(checkboxesSelelected).map(product => product.value);
        id = productsSelected;
    } 

    // Se hace una petición AJAX de tipo POST para enviar al controlador el id de producto y el id de pedido
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
        // Redirecciona a la vista de 'Resumen de pedido' para completar el pago
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
    // Guarda el input tipo radio junto con las opciones
    let addressRadio = document.getElementById('use_address');
    let deliveryPointRadio = document.getElementById('use_deliveryPoint');
    let addressZone = document.getElementById('addressZone');
    let poinZone = document.getElementById('delivery_pointZone');

    // Función para gestionar el cambio de elección, dependiendo de la elección se añade o quita la clase 'd-none' de Bootstrap para ocultar la opción no elegida
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

// Función para comprobar el número de teléfono, si no existe lo pone el input en rojo y si se introduce un número correcto lo guarda en la base de datos y pone el input en verde
function checkPhoneNumber() {
    let phoneText = document.getElementById('phoneNumber');
    let phoneInput = document.getElementById('phone_number');

    // Si no hay un número de teléfono guardado en la base de datos se pone rojo el input
    if (!phoneText) {
        phoneInput.style.borderColor = 'red';
        phoneInput.style.borderWidth = '2px';
        phoneInput.style.borderStyle = 'solid';

    }

    // Se añade un evento de tipo 'input' al input de número de teléfono para guardarlo en la base de datos 
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
                    // Si el número introducide ya esta en la base de datos muestra un alert indicando el error, si se guarda correctamente se pone de color verde el input                    
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

// Función para comprobar el input de dirección en caso de elegir esta opción
function checkAddress() {
    let adressSelected = document.getElementById('use_address');
    let addressInput = document.getElementById('address');

    // Si se selecciona la opción de dirección y no hay una dirección guardada en la base de datos pone el input de color rojo
    if (adressSelected.checked) {
        if (addressInput.value == '') {
            addressInput.style.borderColor = 'red';
            addressInput.style.borderWidth = '2px';
            addressInput.style.borderStyle = 'solid';
        }

        // Se añade un evento de tipo 'blur' para comprobar la dirección introducida despúes de quitar el enfoque del input, si esta bien se pone de color verde
        if (addressInput) {
            addressInput.addEventListener('blur', function() {
                if (addressInput.value.length > 0) {
                    addressInput.style.borderColor = 'green';
                    addressInput.style.borderWidth = '2px';
                    addressInput.style.borderStyle = 'solid';
        
                    // Se hace una petición AJAX de tipo POST para hacer la comprobación y guardado en la base de datos de forma dinamica
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

// Función para actualizar el estado de un pedido
function updateOrderStatus(order) {
    let orderStatus = document.getElementById(order.id);

    // Se hace una petición AJAX de tipo POST para enviar al controlador el nuevo estado del pedido y el id de este
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
        // Si se completa la actualización de estado pone de color ver el select, si no se ha podido completar se pone rojo
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