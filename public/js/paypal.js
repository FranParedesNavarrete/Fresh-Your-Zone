
let addressInput = document.getElementById('address');
let phoneInput = document.getElementById('phone_number');
let shippingOptions = document.querySelectorAll('input[name="shipping_option"]');
let paypalContainer = document.getElementById('paypal-button-container');
let deliveryPoint = document.getElementById('delivery_point');
let addressValue ='';

if (deliveryPoint) {
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
    addressInput.addEventListener('input', function () {
        let selectedOption = document.querySelector('input[name="shipping_option"]:checked')?.value;
        if (selectedOption === 'address') {
            validateAndRenderPayPal(userId, cartProductIds, totalPrice);
        }
    });
}

// Validación al escribir en el campo de teléfono (si está visible)
if (phoneInput) {
    phoneInput.addEventListener('input', function () {
        validateAndRenderPayPal(userId, cartProductIds, totalPrice);
    });
}

// Función que muestra/oculta los inputs de dirección y punto de entrega
function toggleShippingInputs() {
    let selected = document.querySelector('input[name="shipping_option"]:checked').value;
    document.getElementById('addressZone').classList.toggle('d-none', selected !== 'address');
    document.getElementById('delivery_pointZone').classList.toggle('d-none', selected !== 'point');
    validateAndRenderPayPal(userId, cartProductIds, totalPrice);
}

// Función que valida campos requeridos y muestra PayPal si es válido
function validateAndRenderPayPal(user_id, productsId, totalPrice) {
    paypalContainer.innerHTML = ''; // Limpia el botón si ya estaba

    let selectedOption = document.querySelector('input[name="shipping_option"]:checked').value;
    let phoneInput = document.getElementById('phone_number') || document.getElementById('phoneNumber');
    let phoneValue = phoneInput.textContent.trim() || phoneInput.value.trim();
    
    if (!phoneValue) {
        return;
    }

    if (selectedOption === 'address') {
        addressValue = addressInput.value.trim();
        if (!addressValue) {
            return;
        }
    } else if (selectedOption === 'point') {
        addressValue = deliveryPoint.value;
    }

    // Si pasa las validaciones, renderiza PayPal
    paypal.Buttons({
        createOrder: function (data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: totalPrice,
                    },
                }]
            });
        },
        onApprove: function (data, actions) {
            return actions.order.capture().then(function (details) {
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
    }).render('#paypal-button-container');
}
