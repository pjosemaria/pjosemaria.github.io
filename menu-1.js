// Elementos del DOM
const cartButton = document.getElementById('cart-button');
const cart = document.getElementById('cart');
const cartItems = document.getElementById('cart-items');
const cartCount = document.getElementById('cart-count');
const cartTotal = document.getElementById('cart-total');
const addToCartButtons = document.querySelectorAll('.add-to-cart');

// Estado del carrito
let cartData = [];

// Mostrar/ocultar carrito
cartButton.addEventListener('click', () => {
    cart.style.display = cart.style.display === 'none' ? 'block' : 'none';
});

// Función para actualizar el carrito en pantalla
function updateCartDisplay() {
    cartItems.innerHTML = '';
    let total = 0;
    cartData.forEach(item => {
        const li = document.createElement('li');
        li.textContent = `${item.name} - $${item.price}`;
        cartItems.appendChild(li);
        total += item.price;
    });
    cartTotal.textContent = total.toFixed(2);
    cartCount.textContent = cartData.length;
}

// Agregar producto al carrito
addToCartButtons.forEach(button => {
    button.addEventListener('click', () => {
        const item = button.closest('.menu-item');
        const name = item.dataset.name;
        const price = parseFloat(item.dataset.price);

        cartData.push({ name, price });
        updateCartDisplay();
    });
});

// Botón de pago
document.getElementById('checkout-button').addEventListener('click', () => {
    if (cartData.length === 0) {
        alert("Tu carrito está vacío.");
    } else {
        alert("Gracias por tu compra!");
        cartData = [];
        updateCartDisplay();
    }
});