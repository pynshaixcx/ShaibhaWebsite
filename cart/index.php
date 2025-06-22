<?php
$page_title = "Shopping Cart";
$page_description = "Review your selected items before checkout.";
require_once '../includes/functions.php';
require_once '../includes/cart-functions.php';

$session_id = getSessionId();
$customer_id = getCurrentCustomerId();

// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update':
                if (isset($_POST['cart_id']) && isset($_POST['quantity'])) {
                    updateCartQuantity($_POST['cart_id'], $_POST['quantity']);
                    setFlashMessage('success', 'Cart updated successfully');
                }
                break;
            case 'remove':
                if (isset($_POST['cart_id'])) {
                    removeFromCart($_POST['cart_id']);
                    setFlashMessage('success', 'Item removed from cart');
                }
                break;
            case 'clear':
                clearCart($session_id, $customer_id);
                setFlashMessage('success', 'Cart cleared');
                break;
        }
        redirect('index.php');
    }
}

$cart_items = getCartItems($session_id, $customer_id);
$cart_totals = calculateCartTotal($session_id, $customer_id);

include_once '../includes/header.php';
?>

<main class="main-content">
    <!-- Cart Header -->
    <section class="cart-header">
        <div class="container">
            <h1 class="page-title">Shopping Cart</h1>
            <p class="page-subtitle">
                <?php echo count($cart_items); ?> item<?php echo count($cart_items) !== 1 ? 's' : ''; ?> in your cart
            </p>
        </div>
    </section>

    <!-- Cart Content -->
    <section class="cart-content">
        <div class="container">
            <?php if (!empty($cart_items)): ?>
                <div class="cart-layout">
                    <!-- Cart Items -->
                    <div class="cart-items">
                        <?php foreach ($cart_items as $item): ?>
                            <?php
                            $item_price = $item['sale_price'] ?: $item['price'];
                            $item_total = $item_price * $item['quantity'];
                            ?>
                            <div class="cart-item" data-cart-id="<?php echo $item['id']; ?>">
                                <div class="item-image">
                                    <img src="https://images.pexels.com/photos/1536619/pexels-photo-1536619.jpeg" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>">
                                </div>
                                
                                <div class="item-details">
                                    <h3 class="item-name">
                                        <a href="../shop/product.php?slug=<?php echo $item['slug']; ?>">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </a>
                                    </h3>
                                    <p class="item-brand"><?php echo htmlspecialchars($item['brand']); ?></p>
                                    <?php if ($item['size']): ?>
                                        <p class="item-size">Size: <?php echo htmlspecialchars($item['size']); ?></p>
                                    <?php endif; ?>
                                    <?php if ($item['color']): ?>
                                        <p class="item-color">Color: <?php echo htmlspecialchars($item['color']); ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="item-price">
                                    <span class="price"><?php echo formatPrice($item_price); ?></span>
                                    <?php if ($item['sale_price']): ?>
                                        <span class="original-price"><?php echo formatPrice($item['price']); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="item-quantity">
                                    <form method="POST" class="quantity-form">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                        <div class="quantity-controls">
                                            <button type="button" onclick="decreaseQuantity(this)">-</button>
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                                   min="1" max="10" onchange="this.form.submit()">
                                            <button type="button" onclick="increaseQuantity(this)">+</button>
                                        </div>
                                    </form>
                                </div>
                                
                                <div class="item-total">
                                    <span class="total"><?php echo formatPrice($item_total); ?></span>
                                </div>
                                
                                <div class="item-actions">
                                    <form method="POST" class="remove-form">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="remove-btn" onclick="return confirm('Remove this item from cart?')">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3,6 5,6 21,6"></polyline>
                                                <path d="M19,6v14a2,2,0,0,1-2,2H7a2,2,0,0,1-2-2V6m3,0V4a2,2,0,0,1,2-2h4a2,2,0,0,1,2,2V6"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <!-- Cart Actions -->
                        <div class="cart-actions">
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="clear">
                                <button type="submit" class="btn btn-outline" onclick="return confirm('Clear entire cart?')">
                                    Clear Cart
                                </button>
                            </form>
                            <a href="../shop/" class="btn btn-secondary">Continue Shopping</a>
                        </div>
                    </div>
                    
                    <!-- Cart Summary -->
                    <div class="cart-summary">
                        <div class="summary-card">
                            <h3>Order Summary</h3>
                            
                            <div class="summary-row">
                                <span>Subtotal (<?php echo count($cart_items); ?> items)</span>
                                <span><?php echo formatPrice($cart_totals['subtotal']); ?></span>
                            </div>
                            
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span>
                                    <?php if ($cart_totals['shipping_cost'] > 0): ?>
                                        <?php echo formatPrice($cart_totals['shipping_cost']); ?>
                                    <?php else: ?>
                                        <span class="free-shipping">FREE</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            
                            <?php if ($cart_totals['subtotal'] < FREE_SHIPPING_THRESHOLD && $cart_totals['shipping_cost'] > 0): ?>
                                <div class="shipping-notice">
                                    <p>Add <?php echo formatPrice(FREE_SHIPPING_THRESHOLD - $cart_totals['subtotal']); ?> more for free shipping!</p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="summary-total">
                                <span>Total</span>
                                <span><?php echo formatPrice($cart_totals['total']); ?></span>
                            </div>
                            
                            <div class="checkout-actions">
                                <a href="checkout.php" class="btn btn-primary checkout-btn">
                                    Proceed to Checkout
                                </a>
                                <p class="payment-info">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 12l2 2 4-4"></path>
                                        <circle cx="12" cy="12" r="10"></circle>
                                    </svg>
                                    Cash on Delivery Available
                                </p>
                            </div>
                        </div>
                        
                        <!-- Shipping Info -->
                        <div class="shipping-info-card">
                            <h4>Shipping Information</h4>
                            <ul>
                                <li>Free shipping on orders over ₹1,999</li>
                                <li>Standard delivery: 3-5 business days</li>
                                <li>Cash on Delivery available</li>
                                <li>Secure packaging guaranteed</li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Empty Cart -->
                <div class="empty-cart">
                    <div class="empty-cart-content">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <h2>Your cart is empty</h2>
                        <p>Looks like you haven't added any items to your cart yet.</p>
                        <a href="../shop/" class="btn btn-primary">Start Shopping</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<style>
/* Cart Page Styles */
.cart-header {
    padding: var(--spacing-2xl) 0;
    background: linear-gradient(135deg, var(--color-gray-100) 0%, var(--color-white) 100%);
    text-align: center;
}

.cart-content {
    padding: var(--spacing-3xl) 0;
}

.cart-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: var(--spacing-3xl);
}

.cart-items {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
}

.cart-item {
    display: grid;
    grid-template-columns: 100px 1fr auto auto auto auto;
    gap: var(--spacing-lg);
    align-items: center;
    padding: var(--spacing-lg) 0;
    border-bottom: 1px solid var(--color-gray-200);
}

.cart-item:last-child {
    border-bottom: none;
}

.item-image img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: var(--border-radius-md);
}

.item-details h3 {
    margin-bottom: var(--spacing-xs);
}

.item-name a {
    color: var(--color-black);
    text-decoration: none;
    font-weight: 600;
}

.item-name a:hover {
    text-decoration: underline;
}

.item-brand,
.item-size,
.item-color {
    color: var(--color-gray-600);
    font-size: 0.9rem;
    margin-bottom: var(--spacing-xs);
}

.item-price .price {
    font-weight: 700;
    color: var(--color-black);
}

.item-price .original-price {
    color: var(--color-gray-500);
    text-decoration: line-through;
    font-size: 0.9rem;
    display: block;
}

.quantity-controls {
    display: flex;
    align-items: center;
    border: 1px solid var(--color-gray-300);
    border-radius: var(--border-radius-md);
    overflow: hidden;
}

.quantity-controls button {
    background: var(--color-gray-100);
    border: none;
    padding: var(--spacing-xs) var(--spacing-sm);
    cursor: pointer;
    transition: background-color var(--transition-fast);
}

.quantity-controls button:hover {
    background: var(--color-gray-200);
}

.quantity-controls input {
    border: none;
    padding: var(--spacing-xs);
    width: 50px;
    text-align: center;
    background: var(--color-white);
}

.item-total .total {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--color-black);
}

.remove-btn {
    background: transparent;
    border: none;
    color: var(--color-gray-500);
    cursor: pointer;
    padding: var(--spacing-xs);
    border-radius: var(--border-radius-sm);
    transition: all var(--transition-fast);
}

.remove-btn:hover {
    background: #fee2e2;
    color: #dc2626;
}

.cart-actions {
    margin-top: var(--spacing-xl);
    padding-top: var(--spacing-xl);
    border-top: 1px solid var(--color-gray-200);
    display: flex;
    gap: var(--spacing-md);
}

.cart-summary {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.summary-card,
.shipping-info-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    position: sticky;
    top: 100px;
}

.summary-card h3 {
    margin-bottom: var(--spacing-lg);
    color: var(--color-black);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: var(--spacing-md);
    color: var(--color-gray-700);
}

.free-shipping {
    color: #10b981;
    font-weight: 600;
}

.shipping-notice {
    background: #f0f9ff;
    border: 1px solid #0ea5e9;
    border-radius: var(--border-radius-md);
    padding: var(--spacing-sm);
    margin: var(--spacing-md) 0;
}

.shipping-notice p {
    color: #0369a1;
    font-size: 0.9rem;
    margin: 0;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    font-weight: 700;
    font-size: 1.2rem;
    color: var(--color-black);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--color-gray-200);
    margin-top: var(--spacing-md);
}

.checkout-actions {
    margin-top: var(--spacing-xl);
}

.checkout-btn {
    width: 100%;
    justify-content: center;
    margin-bottom: var(--spacing-md);
}

.payment-info {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    color: var(--color-gray-600);
    font-size: 0.9rem;
    justify-content: center;
}

.shipping-info-card h4 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.shipping-info-card ul {
    list-style: none;
}

.shipping-info-card li {
    margin-bottom: var(--spacing-xs);
    color: var(--color-gray-600);
    font-size: 0.9rem;
    position: relative;
    padding-left: var(--spacing-md);
}

.shipping-info-card li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #10b981;
    font-weight: bold;
}

.empty-cart {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
}

.empty-cart-content {
    text-align: center;
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    border: 1px solid var(--glass-border);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-3xl);
    max-width: 400px;
}

.empty-cart-content svg {
    color: var(--color-gray-400);
    margin-bottom: var(--spacing-lg);
}

.empty-cart-content h2 {
    margin-bottom: var(--spacing-md);
    color: var(--color-black);
}

.empty-cart-content p {
    color: var(--color-gray-600);
    margin-bottom: var(--spacing-xl);
}

/* Responsive */
@media (max-width: 992px) {
    .cart-layout {
        grid-template-columns: 1fr;
        gap: var(--spacing-xl);
    }
    
    .cart-item {
        grid-template-columns: 80px 1fr;
        gap: var(--spacing-md);
    }
    
    .item-price,
    .item-quantity,
    .item-total,
    .item-actions {
        grid-column: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: var(--spacing-sm);
    }
}

@media (max-width: 768px) {
    .cart-actions {
        flex-direction: column;
    }
    
    .summary-card,
    .shipping-info-card {
        position: static;
    }
}
</style>

<script>
function increaseQuantity(button) {
    const input = button.parentElement.querySelector('input[name="quantity"]');
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
        input.form.submit();
    }
}

function decreaseQuantity(button) {
    const input = button.parentElement.querySelector('input[name="quantity"]');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
        input.form.submit();
    }
}
</script>

<?php include_once '../includes/footer.php'; ?>