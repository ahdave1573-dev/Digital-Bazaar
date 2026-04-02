<?php
// ================= SESSION =================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once(__DIR__ . '/config/db.php');
//include_once(__DIR__ . '/config/auth.php');

// ================= LOGIN CHECK =================
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id   = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

// ================= METRICS =================

// Total Orders
$q1 = mysqli_query($conn, "SELECT id FROM orders WHERE user_id='$user_id'");
$total_orders = mysqli_num_rows($q1);

// Pending Orders
$q2 = mysqli_query($conn,"
    SELECT id FROM orders
    WHERE user_id='$user_id'
    AND order_status NOT IN ('Delivered','Completed','Cancelled')
");
$pending_orders = mysqli_num_rows($q2);

// Cart Items
$cart_items = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

include_once(__DIR__ . '/includes/header.php');
?>

<style>
.dashboard-wrapper{display:flex;min-height:80vh;background:#f8fafc}
.main-content{flex:1;padding:30px}

.welcome-banner{
    background:linear-gradient(135deg,#2563eb,#1e40af);
    color:#fff;padding:30px;border-radius:15px;margin-bottom:30px
}

.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:25px;margin-bottom:35px
}
.stat-card{
    background:#fff;padding:22px;border-radius:12px;
    display:flex;gap:18px;border:1px solid #e5e7eb
}
.stat-icon{
    width:55px;height:55px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-size:22px
}
.blue{background:#eff6ff;color:#2563eb}
.orange{background:#fff7ed;color:#ea580c}
.green{background:#f0fdf4;color:#16a34a}

.table-box{
    background:#fff;padding:25px;border-radius:15px;
    border:1px solid #e5e7eb
}
table{width:100%;border-collapse:collapse}
th,td{padding:14px;border-bottom:1px solid #eee;text-align:left}
.badge{padding:5px 12px;border-radius:20px;font-size:12px}
.pending{background:#fff7ed;color:#c2410c}
.delivered{background:#f0fdf4;color:#15803d}
.cancelled{background:#fee2e2;color:#b91c1c}
a{color:#2563eb;text-decoration:none;font-weight:500}
</style>

<div class="dashboard-wrapper">
<div class="main-content">

<!-- WELCOME -->
<div class="welcome-banner">
    <h1>Welcome back, <?php echo htmlspecialchars($user_name); ?> 👋</h1>
    <p>Manage your orders and activity</p>
</div>

<!-- STATS -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">🛍</div>
        <div>
            <h3><?php echo $total_orders; ?></h3>
            <p>Total Orders</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">⏳</div>
        <div>
            <h3><?php echo $pending_orders; ?></h3>
            <p>Pending Orders</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">🛒</div>
        <div>
            <h3><?php echo $cart_items; ?></h3>
            <p>Cart Items</p>
        </div>
    </div>
</div>

<!-- ================= RECENT ORDERS ================= -->
<div class="table-box">
<h3>Recent Orders</h3><br>

<table>
<tr>
    <th>Order ID</th>
    <th>Date</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php
$recent = mysqli_query($conn,"
    SELECT id, order_status, created_at
    FROM orders
    WHERE user_id='$user_id'
    ORDER BY id DESC
    LIMIT 5
");

if (mysqli_num_rows($recent) > 0) {
    while ($o = mysqli_fetch_assoc($recent)) {

        $status = strtolower($o['order_status']);
        $cls = 'pending';
        if ($status == 'completed' || $status == 'delivered') $cls = 'delivered';
        if ($status == 'cancelled') $cls = 'cancelled';
?>
<tr>
    <td>ORD-<?php echo $o['id']; ?></td>
    <td><?php echo date('d M Y', strtotime($o['created_at'])); ?></td>
    <td><span class="badge <?php echo $cls; ?>">
        <?php echo ucfirst($status); ?>
    </span></td>
    <td>
        <a href="my_orders.php?order_id=<?php echo $o['id']; ?>">
            View
        </a>
    </td>
</tr>
<?php } } else { ?>
<tr>
    <td colspan="4">No orders found</td>
</tr>
<?php } ?>

</table>
</div>

</div>
</div>

<?php include_once(__DIR__ . '/includes/footer.php'); ?>
