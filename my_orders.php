<?php
session_start();
include('db.php');

// ===== AUTH CHECK =====
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

include('includes/header.php');
?>

<style>
/* Global Settings */
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

.orders-wrapper {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px;
}

/* Page Header */
.page-header {
    text-align: center;
    margin-bottom: 40px;
}
.page-header h2 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--secondary);
    margin-bottom: 5px;
}
.page-header p {
    color: var(--text-light);
}

/* Order Card */
.order-card {
    background: var(--card-bg);
    border-radius: 12px;
    border: 1px solid var(--border);
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    margin-bottom: 25px;
    overflow: hidden;
    transition: transform 0.2s;
}
.order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
}

/* Card Header */
.card-header {
    background: #f8fafc;
    padding: 15px 25px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}
.order-id {
    font-weight: 700;
    color: var(--secondary);
    font-size: 1.05rem;
}
.order-date {
    font-size: 0.85rem;
    color: var(--text-light);
    display: block;
    margin-top: 2px;
}

/* Status Badges */
.badge {
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.Pending { background: #fef3c7; color: #d97706; }
.Processing { background: #dbeafe; color: #2563eb; }
.Completed { background: #dcfce7; color: #166534; }
.Cancelled { background: #fee2e2; color: #991b1b; }

/* Card Body (Items) */
.card-body {
    padding: 20px 25px;
}
.item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f1f5f9;
    padding: 12px 0;
}
.item-row:last-child {
    border-bottom: none;
}
.item-info b {
    color: var(--text-main);
    font-size: 0.95rem;
}
.item-meta {
    font-size: 0.85rem;
    color: var(--text-light);
    margin-top: 2px;
}
.item-price {
    font-weight: 600;
    color: var(--secondary);
}

/* Card Footer (Total) */
.card-footer {
    padding: 15px 25px;
    background: #fff;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: flex-end;
    align-items: center;
}
.total-label {
    font-size: 0.9rem;
    color: var(--text-light);
    margin-right: 10px;
}
.total-amount {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 12px;
    border: 1px dashed var(--border);
}
.empty-icon {
    font-size: 3rem;
    color: #cbd5e1;
    margin-bottom: 15px;
}
.btn-shop {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 25px;
    background: var(--primary);
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
}
.btn-shop:hover { background: #4338ca; }

/* Mobile */
@media(max-width: 600px){
    .card-header { flex-direction: column; align-items: flex-start; }
    .badge { align-self: flex-start; margin-top: 5px; }
}
</style>

<div class="orders-wrapper">

    <div class="page-header">
        <h2>My Order History</h2>
        <p>Track your past purchases and status.</p>
    </div>

    <?php
    // ===== FETCH ORDERS =====
    $order_q = mysqli_query($conn, "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY id DESC");

    if (mysqli_num_rows($order_q) == 0):
    ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-box-open"></i></div>
            <h3>No Orders Placed Yet</h3>
            <p style="color:#64748b;">It looks like you haven't ordered anything yet.</p>
            <a href="products.php" class="btn-shop">Start Shopping</a>
        </div>
    <?php
    else:
        while ($order = mysqli_fetch_assoc($order_q)):
    ?>

        <div class="order-card">
            
            <div class="card-header">
                <div>
                    <div class="order-id">Order #<?= $order['order_id']; ?></div>
                    <span class="order-date">
                        <i class="far fa-calendar-alt"></i> <?= date("d M Y, h:i A", strtotime($order['created_at'])); ?>
                    </span>
                </div>
                <span class="badge <?= $order['order_status']; ?>">
                    <?= $order['order_status']; ?>
                </span>
            </div>

            <div class="card-body">
                <?php
                // Fetch Items for this order
                $item_q = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id='{$order['order_id']}'");
                while ($item = mysqli_fetch_assoc($item_q)):
                ?>
                    <div class="item-row">
                        <div class="item-info">
                            <b><?= $item['product_name']; ?></b>
                            <div class="item-meta">
                                <?= $item['category']; ?> | Qty: <?= $item['quantity']; ?>
                            </div>
                        </div>
                        <div class="item-price">
                            ₹<?= number_format($item['price'] * $item['quantity']); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="card-footer">
                <span class="total-label">Grand Total:</span>
                <span class="total-amount">₹<?= number_format($order['total_amount']); ?></span>
            </div>

        </div>

    <?php endwhile; endif; ?>

</div>

<?php include('includes/footer.php'); ?>