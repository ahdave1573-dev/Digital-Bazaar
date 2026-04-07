<?php
include("../auth.php");
include("../db.php");

/* ================= SAFE COUNT FUNCTION ================= */
function getCount($conn, $sql){
    $res = mysqli_query($conn, $sql);
    if($res){
        $row = mysqli_fetch_assoc($res);
        return $row['total'] ?? 0;
    }
    return 0;
}

/* ================= DASHBOARD COUNTS ================= */

// Products
$total_products = getCount($conn,
    "SELECT COUNT(*) AS total FROM products"
);

// Orders (total)
$total_orders = getCount($conn,
    "SELECT COUNT(*) AS total FROM orders"
);

// Users
$total_users = getCount($conn,
    "SELECT COUNT(*) AS total FROM users"
);

// Messages
$total_messages = getCount($conn,
    "SELECT COUNT(*) AS total FROM contact_messages"
);

// Offers
$total_offers = getCount($conn,
    "SELECT COUNT(*) AS total FROM offers"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - DigitalBazaar</title>

<style>
:root{
    --primary:#4f46e5;
    --sidebar:#1f2937;
    --bg:#f3f4f6;
    --card:#ffffff;
    --text:#111827;
}
*{box-sizing:border-box}
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:var(--bg);
    color:var(--text);
}

/* NAVBAR */
.navbar{
    position:fixed;
    top:0;left:0;right:0;
    height:60px;
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 25px;
    border-bottom:1px solid #e5e7eb;
    z-index:100;
}
.navbar-brand{
    font-size:1.2rem;
    font-weight:700;
    color:black;
}
.logout-btn{
    background:#fee2e2;
    color:#dc2626;
    padding:8px 16px;
    border-radius:6px;
    text-decoration:none;
    font-weight:600;
}

/* SIDEBAR */
.sidebar{
    position:fixed;
    top:60px;
    left:0;
    bottom:0;
    width:260px;
    background:var(--sidebar);
    padding-top:20px;
}
.sidebar a{
    display:block;
    padding:14px 25px;
    color:#d1d5db;
    text-decoration:none;
}
.sidebar a:hover,
.sidebar a.active{
    background:#374151;
    color:#fff;
    border-left:4px solid var(--primary);
}

/* CONTENT */
.content{
    margin-left:260px;
    margin-top:60px;
    padding:30px;
}
h2{margin-top:0}

/* CARDS */
.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:30px;
}
.card{
    background:var(--card);
    padding:25px;
    border-radius:12px;
    border:1px solid #e5e7eb;
}
.card h3{
    margin:0;
    font-size:.9rem;
    color:#6b7280;
    text-transform:uppercase;
}
.card .number{
    font-size:2.2rem;
    font-weight:700;
    margin-top:10px;
}

.welcome-box{
    background:#fff;
    padding:30px;
    border-radius:12px;
    border:1px solid #e5e7eb;
}

/* MOBILE */
@media(max-width:768px){
    .sidebar{width:70px}
    .content{margin-left:70px}
}
</style>
</head>

<body>

<div class="navbar">
    <div class="navbar-brand">DigitalBazaar <span style="font-weight:400;">Admin</span></div>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<div class="sidebar">
    <a href="admin.php" class="active">📊 Dashboard</a>
    <a href="view.php">📦 Products</a>
    <a href="add_offer.php">🎁 Offers</a>
    <a href="orders.php">🛒 Orders</a>
    <a href="users.php">👥 Users</a>
    <a href="contactus.php">📩 Messages</a>
</div>

<div class="content">

<h2>Welcome Back, Admin 👋</h2>

<div class="stats-grid">
    <div class="card">
        <h3>Total Products</h3>
        <div class="number"><?= $total_products ?></div>
    </div>
    <div class="card">
        <h3>Total Offers</h3>
        <div class="number"><?= $total_offers ?></div>
    </div>
    <div class="card">
        <h3>Total Orders</h3>
        <div class="number"><?= $total_orders ?></div>
    </div>
    <div class="card">
        <h3>Registered Users</h3>
        <div class="number"><?= $total_users ?></div>
    </div>
    <div class="card">
        <h3>Messages</h3>
        <div class="number"><?= $total_messages ?></div>
    </div>
</div>

<div class="welcome-box">
    <h3>Admin Panel Ready ✅</h3>
    <p>You can manage products, orders, users and messages from the sidebar.</p>
</div>

</div>

</body>
</html>
