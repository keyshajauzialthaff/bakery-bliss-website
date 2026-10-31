// SHOPPING CART BAKERY BLISS

let cart = JSON.parse(localStorage.getItem("bakeryCart")) || [];

function saveCart() {
    localStorage.setItem("bakeryCart", JSON.stringify(cart));
}

// ADD TO CART
const buttons = document.querySelectorAll(".add-to-cart");

buttons.forEach(button => {
    button.addEventListener("click", function() {

        const name = this.dataset.name;
        const price = Number(this.dataset.price);

        const existingProduct = cart.find(item => item.name === name);

        if (existingProduct) {
            existingProduct.quantity++;
        } else {
            cart.push({
                name: name,
                price: price,
                quantity: 1
            });
        }

        saveCart();

        alert(name + " berhasil ditambahkan ke keranjang!");
    });
});

// DISPLAY CART
function displayCart() {

    const container = document.getElementById("cart-container");

    if (!container) return;

    if (cart.length === 0) {
        container.innerHTML = "Your cart is empty.";
        return;
    }

    let total = 0;
    let html = "";

    cart.forEach((item, index) => {

        const subtotal = item.price * item.quantity;
        total += subtotal;

        html += `
            <div class="cart-item">
                <h3>${item.name}</h3>
                <p>Rp ${item.price.toLocaleString("id-ID")}</p>
                <p>Quantity: ${item.quantity}</p>
                <p>Subtotal: Rp ${subtotal.toLocaleString("id-ID")}</p>
                <div class="quantity-control">
                    <button onclick="changeQuantity(${index}, -1)">−</button>
                    <span>${item.quantity}</span>
                    <button onclick="changeQuantity(${index}, 1)">+</button>
                </div>
                <button onclick="removeItem(${index})">
                Remove
                </button>
            </div>
        `;
    });

    html += `
        <h2>Total: Rp ${total.toLocaleString("id-ID")}</h2>
    `;

    container.innerHTML = html;
}

displayCart();
function removeItem(index) {
    cart.splice(index, 1);
    saveCart();
    displayCart();
}
function changeQuantity(index, change) {

    cart[index].quantity += change;

    if (cart[index].quantity <= 0) {
        cart.splice(index, 1);
    }

    saveCart();
    displayCart();
}
// DISPLAY CHECKOUT SUMMARY

function displayCheckoutSummary() {

    const summary = document.getElementById("checkout-summary");

    if (!summary) return;

    if (cart.length === 0) {
        summary.innerHTML = "<p>Your cart is empty.</p>";
        return;
    }

    let total = 0;
    let html = "";

    cart.forEach(item => {

        const subtotal = item.price * item.quantity;
        total += subtotal;

        html += `
            <div class="summary-item">
                <p><strong>${item.name}</strong></p>
                <p>Quantity: ${item.quantity}</p>
                <p>Subtotal: Rp ${subtotal.toLocaleString("id-ID")}</p>
        </div>
        `;
    });

    html += `
        <h3>Total Payment: Rp ${total.toLocaleString("id-ID")}</h3>
    `;

    summary.innerHTML = html;
}

displayCheckoutSummary();
// PREVENT CHECKOUT WHEN CART IS EMPTY

const checkoutLink = document.getElementById("checkout-link");

if (checkoutLink) {
    checkoutLink.addEventListener("click", function(event) {

        if (cart.length === 0) {
            event.preventDefault();
            alert("Your cart is empty! Please add a product first ♡");
        }

    });
}
// SEND CART DATA TO PHP

const checkoutForm = document.getElementById("checkout-form");

if (checkoutForm) {
    checkoutForm.addEventListener("submit", function() {

        const cartInput = document.getElementById("cart-data");

        cartInput.value = JSON.stringify(cart);

    });
}