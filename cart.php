<?php
session_start();
include('db.php');

/* ================= CART LOGIC ================= */

// 1. Initialize Cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 2. Add Item / Increase Qty
if (isset($_GET['add'])) {
    $id = $_GET['add'];
    $check = mysqli_query($conn, "SELECT id FROM products WHERE id='$id'");
    if (mysqli_num_rows($check) > 0) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]++;
        } else {
            $_SESSION['cart'][$id] = 1;
        }
    }
    header("Location: cart.php");
    exit();
}

// 3. Decrease Qty
if (isset($_GET['dec'])) {
    $id = $_GET['dec'];
    if (isset($_SESSION['cart'][$id])) {
        if ($_SESSION['cart'][$id] > 1) {
            $_SESSION['cart'][$id]--;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }
    header("Location: cart.php");
    exit();
}

// 4. Remove Item
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

// 5. Clear Cart
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
    header("Location: cart.php");
    exit();
}

include('includes/header.php');
?>

<style>
/* Global Variables */
:root {
    --primary: #4f46e5;
    --secondary: #1f2937;
    --bg-color: #f8fafc;
    --card-bg: #ffffff;
    --text-main: #333;
    --text-light: #64748b;
    --border: #e2e8f0;
}

body {
    background-color: var(--bg-color);
    font-family: 'Poppins', sans-serif;
}

.cart-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
    display: flex;
    gap: 30px;
    align-items: flex-start;
}

/* --- Left Side: Cart Items --- */
.cart-items {
    flex: 2;
    background: var(--card-bg);
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    border: 1px solid var(--border);
    overflow: hidden;
}

.cart-header {
    padding: 20px;
    background: #f8fafc;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.cart-header h3 { margin: 0; font-size: 1.2rem; color: var(--secondary); }
.clear-btn { color: #ef4444; text-decoration: none; font-size: 0.9rem; font-weight: 500; }
.clear-btn:hover { text-decoration: underline; }

.cart-table { width: 100%; border-collapse: collapse; }
.cart-table td { padding: 20px; border-bottom: 1px solid var(--border); vertical-align: middle; }
.cart-table tr:last-child td { border-bottom: none; }

/* Product Info in Table */
.prod-info { display: flex; align-items: center; gap: 15px; }
.prod-img {
    width: 80px; height: 80px; border-radius: 8px;
    object-fit: cover; border: 1px solid var(--border);
}
.prod-details h4 { margin: 0 0 5px; font-size: 1rem; color: var(--secondary); }
.prod-cat { font-size: 0.85rem; color: var(--text-light); }
.prod-price { font-weight: 600; color: var(--primary); font-size: 1rem; margin-top: 5px; display: block; }

/* Quantity Box */
.qty-wrapper {
    display: flex; align-items: center; border: 1px solid var(--border);
    border-radius: 8px; width: fit-content; overflow: hidden;
}
.qty-btn {
    padding: 5px 12px; background: #f1f5f9; text-decoration: none;
    color: var(--secondary); font-weight: bold; transition: 0.2s;
}
.qty-btn:hover { background: #e2e8f0; }
.qty-num { padding: 5px 15px; font-weight: 600; font-size: 0.9rem; border-left: 1px solid var(--border); border-right: 1px solid var(--border); }

/* Remove Button */
.remove-link {
    color: #ef4444; text-decoration: none; font-size: 1.2rem;
    padding: 8px; border-radius: 50%; transition: 0.2s;
}
.remove-link:hover { background: #fee2e2; }

/* --- Right Side: Summary --- */
.cart-summary {
    flex: 1;
    background: var(--card-bg);
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
    border: 1px solid var(--border);
    position: sticky; top: 100px;
}

.summary-title { font-size: 1.3rem; font-weight: 700; margin-bottom: 20px; color: var(--secondary); }

.summary-row {
    display: flex; justify-content: space-between; margin-bottom: 15px;
    font-size: 0.95rem; color: var(--text-main);
}
.summary-total {
    display: flex; justify-content: space-between; margin-top: 20px;
    padding-top: 15px; border-top: 2px dashed var(--border);
    font-size: 1.2rem; font-weight: 700; color: var(--primary);
}

/* Payment Options */
.payment-methods { margin-top: 25px; }
.payment-methods h4 { font-size: 1rem; margin-bottom: 10px; }
.radio-group {
    display: block; margin-bottom: 10px; cursor: pointer;
    font-size: 0.95rem; color: var(--text-light);
}
.radio-group input { margin-right: 8px; }

/* Checkout Button */
.btn-checkout {
    display: block; width: 100%; padding: 14px;
    background: var(--primary); color: white;
    border: none; border-radius: 10px;
    font-size: 1rem; font-weight: 600;
    margin-top: 20px; cursor: pointer; transition: 0.3s;
}
.btn-checkout:hover { background: #4338ca; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3); }

/* Empty State */
.empty-cart { text-align: center; padding: 60px 20px; }
.empty-icon { font-size: 4rem; color: #cbd5e1; margin-bottom: 20px; }
.btn-shop {
    display: inline-block; padding: 10px 25px; background: var(--secondary);
    color: white; text-decoration: none; border-radius: 8px; margin-top: 15px;
}

/* Responsive */
@media (max-width: 900px) {
    .cart-container { flex-direction: column; }
    .cart-items, .cart-summary { width: 100%; }
    .cart-summary { position: static; }
}
</style>

<div class="cart-container">

    <div class="cart-items">
        <div class="cart-header">
            <h3>Shopping Cart</h3>
            <?php if(!empty($_SESSION['cart'])): ?>
                <a href="cart.php?clear=1" class="clear-btn"><i class="fas fa-trash-alt"></i> Clear Cart</a>
            <?php endif; ?>
        </div>

        <?php if(empty($_SESSION['cart'])): ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-basket empty-icon"></i>
                <h3>Your Cart is Empty</h3>
                <p style="color:#64748b;">Looks like you haven't added anything yet.</p>
                <a href="products.php" class="btn-shop">Continue Shopping</a>
            </div>
        <?php else: ?>

        <table class="cart-table">
            <?php
            $total = 0;
            foreach($_SESSION['cart'] as $id => $qty):
                $id_safe = mysqli_real_escape_string($conn, $id);
                $p = mysqli_fetch_assoc(mysqli_query($conn,"
                    SELECT p.*, o.discount_price 
                    FROM products p 
                    LEFT JOIN offers o ON p.id = o.product_id AND o.active = 1
                    WHERE p.id='$id_safe'
                "));
                if(!$p) continue;

                $current_price = !empty($p['discount_price']) ? $p['discount_price'] : $p['selling_price'];
                $line_total = $current_price * $qty;
                $total += $line_total;
            ?>
            <tr>
                <td width="55%">
                    <div class="prod-info">
                        <img src="assets/images/<?= $p['image']; ?>" class="prod-img" alt="Product">
                        <div class="prod-details">
                            <h4><?= substr($p['name'], 0, 30); ?>...</h4>
                            <span class="prod-cat">Category: <?= $p['category']; ?></span>
                            <?php if(!empty($p['discount_price'])): ?>
                                <span class="prod-price" style="display: flex; gap: 8px; align-items: baseline;">
                                    <span style="text-decoration: line-through; color: #94a3b8; font-size: 0.85rem;">₹<?= number_format($p['selling_price']); ?></span>
                                    <span>₹<?= number_format($p['discount_price']); ?></span>
                                </span>
                            <?php else: ?>
                                <span class="prod-price">₹<?= number_format($p['selling_price']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>

                <td width="25%">
                    <div class="qty-wrapper">
                        <a href="cart.php?dec=<?= $id; ?>" class="qty-btn">-</a>
                        <div class="qty-num"><?= $qty; ?></div>
                        <a href="cart.php?add=<?= $id; ?>" class="qty-btn">+</a>
                    </div>
                </td>

                <td width="20%" align="right">
                    <a href="cart.php?remove=<?= $id; ?>" class="remove-link" title="Remove Item">
                        <i class="fas fa-times"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>

    <?php if(!empty($_SESSION['cart'])): ?>
    <div class="cart-summary">
        <div class="summary-title">Order Summary</div>

        <div class="summary-row">
            <span>Subtotal</span>
            <span>₹<?= number_format($total); ?></span>
        </div>
        <div class="summary-row">
            <span>Shipping</span>
            <span style="color:#16a34a; font-weight:600;">Free</span>
        </div>
        
        <div class="summary-total">
            <span>Total Amount</span>
            <span>₹<?= number_format($total); ?></span>
        </div>

        <form action="checkout.php" method="post">
            <div class="payment-methods">
                <h4>Payment Method</h4>
                <label class="radio-group">
                    <input type="radio" name="payment_method" value="COD" checked> Cash on Delivery
                </label>
                <label class="radio-group">
                    <input type="radio" name="payment_method" value="UPI"> UPI / Online
                </label>
                <label class="radio-group">
                    <input type="radio" name="payment_method" value="CARD"> Debit / Credit Card
                </label>
            </div>

            <input type="hidden" name="total_amount" value="<?= $total; ?>">
            
            <button type="submit" class="btn-checkout">
                Proceed to Checkout <i class="fas fa-arrow-right"></i>
            </button>
        </form>
    </div>
    <?php endif; ?>

</div>

<?php include('includes/footer.php'); ?>