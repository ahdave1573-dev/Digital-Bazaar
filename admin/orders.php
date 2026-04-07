<?php
include("../auth.php");
include("../db.php");

/* ===== UPDATE ORDER STATUS ===== */
if (isset($_POST['update_status'])) {
    $order_db_id = (int)$_POST['order_db_id'];
    $status      = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn, "UPDATE orders SET order_status='$status' WHERE id='$order_db_id'");
    
    // Redirect to prevent form resubmission
    header("Location: orders.php");
    exit();
}

/* ===== FETCH ORDERS ===== */
$orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Orders - DigitalBazaar</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
:root{
    --primary: #4f46e5;
    --secondary: #818cf8;
    --bg: #f8fafc;
    --card-bg: #ffffff;
    --text-main: #1f2937;
    --text-muted: #6b7280;
    --border: #e2e8f0;
    
    /* Status Colors */
    --status-pending-bg: #fef3c7; --status-pending-text: #d97706;
    --status-processing-bg: #dbeafe; --status-processing-text: #2563eb;
    --status-completed-bg: #dcfce7; --status-completed-text: #16a34a;
    --status-cancelled-bg: #fee2e2; --status-cancelled-text: #dc2626;
}

* { box-sizing: border-box; transition: all 0.2s ease; }
body { margin: 0; font-family: 'Poppins', sans-serif; background: var(--bg); color: var(--text-main); }

/* ===== NAVBAR & SIDEBAR (Same as Dashboard) ===== */
.navbar {
    position: fixed; top: 0; left: 0; right: 0; height: 70px;
    background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 30px; border-bottom: 1px solid var(--border); z-index: 100;
}
.navbar-brand { font-size: 1.5rem; font-weight: 700; color: var(--primary); }
.logout-btn { background: #fee2e2; color: #ef4444; padding: 8px 18px; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 0.9rem; }
.logout-btn:hover { background: #ef4444; color: white; }

.sidebar {
    position: fixed; top: 70px; left: 0; bottom: 0; width: 260px;
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    padding-top: 30px; box-shadow: 4px 0 10px rgba(0,0,0,0.05);
    border-top-right-radius: 20px;
}
.sidebar a {
    display: flex; align-items: center; padding: 16px 30px;
    color: #94a3b8; text-decoration: none; font-weight: 500;
    border-left: 4px solid transparent;
}
.sidebar a:hover, .sidebar a.active {
    background: rgba(255,255,255,0.05); color: #fff;
    border-left: 4px solid var(--secondary); padding-left: 35px;
}

/* ===== CONTENT AREA ===== */
.content { margin-left: 260px; margin-top: 70px; padding: 40px; }
h2 { margin-top: 0; font-size: 1.8rem; margin-bottom: 30px; }

/* ===== ORDER CARD ===== */
.order-card {
    background: var(--card-bg);
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    margin-bottom: 30px;
    border: 1px solid var(--border);
    overflow: hidden;
}

.card-header {
    background: #f8fafc;
    padding: 20px 25px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.order-info b { font-size: 1.1rem; color: var(--text-main); }
.order-info small { display: block; color: var(--text-muted); margin-top: 4px; font-size: 0.9rem; }

/* Status Badges */
.badge { padding: 6px 14px; border-radius: 50px; font-size: 0.85rem; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; }
.Pending { background: var(--status-pending-bg); color: var(--status-pending-text); }
.Processing { background: var(--status-processing-bg); color: var(--status-processing-text); }
.Completed { background: var(--status-completed-bg); color: var(--status-completed-text); }
.Cancelled { background: var(--status-cancelled-bg); color: var(--status-cancelled-text); }

.card-body { padding: 25px; }

/* Info Strip */
.info-strip {
    display: flex; gap: 20px; margin-bottom: 20px;
    background: #f1f5f9; padding: 15px; border-radius: 8px;
    font-size: 0.95rem;
}

/* Table Styling */
.table-responsive { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; min-width: 600px; }
th { text-align: left; padding: 12px; background: #f8fafc; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; font-weight: 600; }
td { padding: 15px 12px; border-bottom: 1px solid #f1f5f9; font-size: 0.95rem; }
tr:last-child td { border-bottom: none; }

.grand-total {
    text-align: right; font-size: 1.2rem; font-weight: 700;
    color: var(--primary); margin-top: 20px; padding-top: 15px;
    border-top: 2px dashed var(--border);
}

/* Action Form */
.action-area {
    margin-top: 20px; display: flex; align-items: center; gap: 10px;
    background: #f8fafc; padding: 15px; border-radius: 8px; justify-content: flex-end;
}
select {
    padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px;
    font-family: inherit; color: var(--text-main); outline: none;
}
button[name="update_status"] {
    background: var(--primary); color: white; border: none;
    padding: 8px 20px; border-radius: 6px; font-weight: 500; cursor: pointer;
}
button[name="update_status"]:hover { background: #4338ca; transform: translateY(-1px); }

/* Responsive */
@media(max-width: 768px){
    .sidebar{ width: 70px; } .sidebar a span { display: none; } .sidebar a { justify-content: center; }
    .content{ margin-left: 70px; padding: 20px; }
    .card-header { flex-direction: column; align-items: flex-start; }
    .action-area { flex-direction: column; align-items: stretch; }
}
</style>
</head>

<body>

<div class="navbar">
    <div class="navbar-brand">DigitalBazaar <span style="font-weight:400; font-size:0.8em; color:#333;">Admin</span></div>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<div class="sidebar">
    <a href="dashboard.php" title="Dashboard"><span>📊</span> &nbsp; Dashboard</a>
    <a href="products.php" title="Products"><span>📦</span> &nbsp; Products</a>
    <a href="manage_offers.php" title="Offers"><span>🎁</span> &nbsp; Offers</a>
    <a href="orders.php" class="active" title="Orders"><span>🛒</span> &nbsp; Orders</a>
    <a href="users.php" title="Users"><span>👥</span> &nbsp; Users</a>
    <a href="contactus.php" title="Messages"><span>📩</span> &nbsp; Messages</a>
</div>

<div class="content">
    <h2>📦 Manage Orders</h2>

    <?php if(mysqli_num_rows($orders) == 0): ?>
        <div style="text-align:center; padding:50px; color:gray; background:white; border-radius:10px;">
            <h3>No Orders Found</h3>
            <p>New orders will appear here.</p>
        </div>
    <?php endif; ?>

    <?php while($order = mysqli_fetch_assoc($orders)): ?>

    <div class="order-card">
        <div class="card-header">
            <div class="order-info">
                <b>Order <?= $order['order_id']; ?></b>
                <small>User ID: <?= $order['user_id']; ?> &bull; <?= date("d M Y, h:i A", strtotime($order['created_at'])); ?></small>
            </div>
            <span class="badge <?= $order['order_status']; ?>">
                <?= $order['order_status']; ?>
            </span>
        </div>

        <div class="card-body">
            
            <div class="info-strip">
                <div><b>Payment:</b> <?= $order['payment_method']; ?></div>
                <div style="border-left:1px solid #ccc; margin:0 10px;"></div>
                <div><b>Category:</b> <?= $order['category']; ?></div>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $items = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id='{$order['order_id']}'");
                        $i = 1;
                        while($item = mysqli_fetch_assoc($items)):
                            $line_total = $item['price'] * $item['quantity'];
                        ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td>
                                <b><?= $item['product_name']; ?></b><br>
                                <small style="color:#888;">ID: <?= $item['product_id']; ?></small>
                            </td>
                            <td><?= $item['category']; ?></td>
                            <td>₹<?= number_format($item['price']); ?></td>
                            <td>x <?= $item['quantity']; ?></td>
                            <td style="font-weight:600;">₹<?= number_format($line_total); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="grand-total">
                Grand Total: ₹<?= number_format($order['total_amount']); ?>
            </div>

            <div class="action-area">
                <form method="POST" style="display:flex; gap:10px; align-items:center; width:100%; justify-content:flex-end;">
                    <span style="font-size:0.9rem; font-weight:500;">Update Status:</span>
                    <input type="hidden" name="order_db_id" value="<?= $order['id']; ?>">
                    <select name="status">
                        <option value="Pending" <?= $order['order_status']=="Pending"?"selected":"" ?>>Pending</option>
                        <option value="Processing" <?= $order['order_status']=="Processing"?"selected":"" ?>>Processing</option>
                        <option value="Completed" <?= $order['order_status']=="Completed"?"selected":"" ?>>Completed</option>
                        <option value="Cancelled" <?= $order['order_status']=="Cancelled"?"selected":"" ?>>Cancelled</option>
                    </select>
                    <button name="update_status" type="submit">Save</button>
                </form>
            </div>

        </div>
    </div>
    <?php endwhile; ?>
</div>

</body>
</html>