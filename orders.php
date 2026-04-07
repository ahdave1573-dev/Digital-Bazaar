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
body{background:#f8fafc}
.orders-container{
 max-width:1000px;
 margin:40px auto;
 padding:0 20px;
}
.order-card{
 background:#fff;
 border-radius:12px;
 border:1px solid #e2e8f0;
 box-shadow:0 4px 10px rgba(0,0,0,.05);
 margin-bottom:25px;
 overflow:hidden;
}
.order-header{
 padding:15px 20px;
 background:#f1f5f9;
 display:flex;
 justify-content:space-between;
 font-weight:600;
}
.order-body{padding:20px}
.order-status{
 padding:4px 10px;
 border-radius:20px;
 font-size:13px;
}
.Pending{background:#fde68a;color:#92400e}
.Completed{background:#bbf7d0;color:#166534}
.Cancelled{background:#fecaca;color:#991b1b}
.item{
 display:flex;
 justify-content:space-between;
 margin-bottom:10px;
 border-bottom:1px solid #eee;
 padding-bottom:8px;
}
.total{
 margin-top:10px;
 text-align:right;
 font-weight:700;
 font-size:18px;
}
.empty{
 text-align:center;
 padding:50px;
 background:#fff;
 border-radius:12px;
}
</style>

<div class="orders-container">

<h2 style="text-align:center;margin-bottom:30px">My Order History</h2>

<?php
// ===== FETCH ORDERS =====
$order_q = mysqli_query(
    $conn,
    "SELECT * FROM orders 
     WHERE user_id='$user_id' 
     ORDER BY id DESC"
);

if (mysqli_num_rows($order_q) == 0):
?>
    <div class="empty">
        <h3>No Orders Found</h3>
        <a href="products.php">Shop Now</a>
    </div>
<?php
else:
while ($order = mysqli_fetch_assoc($order_q)):
?>

<div class="order-card">

    <div class="order-header">
        <span>
            Order ID: <?= $order['order_id']; ?><br>
            <small>Placed on <?= date("d M Y", strtotime($order['created_at'])); ?></small>
        </span>

        <span class="order-status <?= $order['order_status']; ?>">
            <?= $order['order_status']; ?>
        </span>
    </div>

    <div class="order-body">

        <?php
        // ===== FETCH ORDER ITEMS =====
        $item_q = mysqli_query(
            $conn,
            "SELECT * FROM order_items 
             WHERE order_id='{$order['order_id']}'"
        );

        while ($item = mysqli_fetch_assoc($item_q)):
        ?>
            <div class="item">
                <div>
                    <b><?= $item['product_name']; ?></b><br>
                    <small>
                        Category: <?= $item['category']; ?><br>
                        Qty: <?= $item['quantity']; ?>
                    </small>
                </div>

                <div>
                    ₹<?= number_format($item['price'] * $item['quantity']); ?>
                </div>
            </div>
        <?php endwhile; ?>

        <div class="total">
            Total Amount: ₹<?= number_format($order['total_amount']); ?>
        </div>

    </div>
</div>

<?php endwhile; endif; ?>

</div>

<?php include('includes/footer.php'); ?>
