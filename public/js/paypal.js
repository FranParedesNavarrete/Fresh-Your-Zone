// Archivo JS para las funciones relacionadas a la pasarela de pago, este archivo contiene las funciones para gestionar el SDK de paypal y las validaciones de campos da datos de facturación

// Guarda los elementos a validar y el contenedor donde van los botones de PayPal
let addressInput = document.getElementById('address');
let phoneInput = document.getElementById('phone_number');
let shippingOptions = document.querySelectorAll('input[name="shipping_option"]');
let paypalContainer = document.getElementById('paypal-button-container');
let deliveryPoint = document.getElementById('delivery_point');
let addressValue ='';

// Si se ha elegido la opción de punto de entrega guarda la dirección y hace la llamada a la función que se encarga de validar que los campos esten correctos para proceder con el pago
if (deliveryPoint) {
    // Se añade un evento de tipo 'change' para actualizar la dirección en caso de cambiar de punto de entrega
    deliveryPoint.addEventListener('change', function () {
        addressValue = deliveryPoint.value;
        validateAndRenderPayPal(userId, cartProductIds, totalPrice);
    });
}

// Mostrar/ocultar campos según la opción elegida
shippingOptions.forEach(option => {
    option.addEventListener('change', toggleShippingInputs);
});

// Validación al cambiar tipo de envío
document.querySelectorAll('input[name="shipping_option"]').forEach((radio) => {
    radio.addEventListener('change', function() {
        validateAndRenderPayPal(userId, cartProductIds, totalPrice);
    });
});

// Validación al escribir en la dirección
if (addressInput) {
    // Se añade un evento de tipo 'input' para llamar a la función que se encarga de validar que los campos esten correctos para proceder al pago
    addressInput.addEventListener('input', function () {
        let selectedOption = document.querySelector('input[name="shipping_option"]:checked')?.value;
        if (selectedOption === 'address') {
            validateAndRenderPayPal(userId, cartProductIds, totalPrice);
        }
    });
}

// Validación al escribir en el campo de teléfono (si está visible)
if (phoneInput) {
    // Se añade un evento de tipo 'input' para llamar a la función que se encarga de validar que los campos esten correctos para proceder al pago
    phoneInput.addEventListener('input', function () {
        validateAndRenderPayPal(userId, cartProductIds, totalPrice);
    });
}

// Función que muestra/oculta los inputs de dirección y punto de entrega y llama a la función que se encarga de validar que los campos esten correctos para proceder al pago
function toggleShippingInputs() {
    let selected = document.querySelector('input[name="shipping_option"]:checked').value;
    document.getElementById('addressZone').classList.toggle('d-none', selected !== 'address');
    document.getElementById('delivery_pointZone').classList.toggle('d-none', selected !== 'point');
    validateAndRenderPayPal(userId, cartProductIds, totalPrice);
}

// Función que valida campos requeridos y renderiza la ventana de PayPal para proceder el pago si los datos introducidos son válidos
function validateAndRenderPayPal(user_id, productsId, totalPrice) {
    paypalContainer.innerHTML = ''; // Limpia lso botones de PayPal si ya estaban

    let selectedOption = document.querySelector('input[name="shipping_option"]:checked').value;
    let phoneInput = document.getElementById('phone_number') || document.getElementById('phoneNumber');
    let phoneValue = phoneInput.textContent.trim() || phoneInput.value.trim();
    
    // Si no hay un número de teléfono no muestra lso botones de PayPal
    if (!phoneValue) {
        return;
    }

    // Guarda la dirección de la opción escogida por el usuario
    if (selectedOption === 'address') {
        addressValue = addressInput.value.trim();
        if (!addressValue) {
            return;
        }
    } else if (selectedOption === 'point') {
        addressValue = deliveryPoint.value;
    }

    // Si pasa las validaciones, renderiza la ventana de PayPal para proceder con el pago
    paypal.Buttons({
        // Se indica el metodo a utilizar y se le pasa el precio a cobrar
        createOrder: function (data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: totalPrice,
                    },
                }]
            });
        },
        // Se proceder a hacer la comprobación del pago
        onApprove: function (data, actions) {
            return actions.order.capture().then(function (details) {
                // Se hace una petición AJAX para enviar al controlador los datos del pedido para guardarlos en la base de datos
                fetch("/orders/store", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        user_id: user_id,
                        price: totalPrice,
                        product_id: productsId,
                        delivery_address: addressValue,
                        delivery_phone: phoneValue,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Si se completa el pago se redirecciona a la vista de pedido completado, si no se ha podido completar el pago muestra un alert indicando el error
                    if (data.success) {
                        window.location.href = "/orders/success";
                    } else {
                        alert('Error al procesar el pago: ' + data.message);
                        console.error('Error:', data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un error al procesar el pago.');
                });
            });
        },
        onError: function (err) {
            console.error(err);
            alert("Hubo un error al procesar el pago.");
        }
    }).render('#paypal-button-container'); // Renderiza los botones de PayPal en el contenedor guardado
}
