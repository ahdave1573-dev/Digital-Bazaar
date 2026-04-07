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
$total_users    = getCount($conn, "SELECT COUNT(*) AS total FROM users");
$total_messages = getCount($conn, "SELECT COUNT(*) AS total FROM contact_messages");
$total_offers   = getCount($conn, "SELECT COUNT(*) AS total FROM offers");
$total_products = getCount($conn, "SELECT COUNT(*) AS total FROM products");
$total_orders   = getCount($conn, "SELECT COUNT(*) AS total FROM orders");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - DigitalBazaar</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
:root{
    --primary: #4f46e5;       /* Indigo */
    --secondary: #818cf8;     /* Lighter Indigo */
    --sidebar-bg: #111827;    /* Dark Sidebar */
    --bg: #f8fafc;            /* Light Gray BG */
    --card-bg: #ffffff;
    --text-main: #1f2937;
    --text-muted: #6b7280;
    --danger: #ef4444;
}

* { box-sizing: border-box; transition: all 0.3s ease; }

body {
    margin: 0;
    font-family: 'Poppins', sans-serif; /* Modern Font */
    background: var(--bg);
    color: var(--text-main);
}

/* ================= NAVBAR ================= */
.navbar {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: 70px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px); /* Glass Effect */
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
    border-bottom: 1px solid #e2e8f0;
    z-index: 100;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}

.navbar-brand {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary);
    letter-spacing: -0.5px;
}
.navbar-brand span { color: var(--text-main); font-weight: 400; }

.logout-btn {
    background: #fee2e2;
    color: var(--danger);
    padding: 10px 20px;
    border-radius: 50px; /* Rounded Button */
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
}
.logout-btn:hover {
    background: var(--danger);
    color: white;
    transform: translateY(-2px);
}

/* ================= SIDEBAR ================= */
.sidebar {
    position: fixed;
    top: 70px;
    left: 0;
    bottom: 0;
    width: 260px;
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%); /* Gradient Sidebar */
    padding-top: 30px;
    box-shadow: 4px 0 10px rgba(0,0,0,0.05);
    border-top-right-radius: 20px; /* Unique Curve */
}

.sidebar a {
    display: flex;
    align-items: center;
    padding: 16px 30px;
    color: #94a3b8;
    text-decoration: none;
    font-weight: 500;
    border-left: 4px solid transparent;
}

.sidebar a:hover, .sidebar a.active {
    background: rgba(255,255,255,0.05);
    color: #fff;
    border-left: 4px solid var(--secondary);
    padding-left: 35px; /* Slide Effect */
}

/* ================= CONTENT ================= */
.content {
    margin-left: 260px;
    margin-top: 70px;
    padding: 40px;
}

h2 {
    font-size: 1.8rem;
    color: var(--text-main);
    margin-bottom: 30px;
}

/* ================= STATS CARDS ================= */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.card {
    background: var(--card-bg);
    padding: 30px;
    border-radius: 16px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
    position: relative;
    overflow: hidden;
}

/* Hover Effect for Cards */
.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border-color: var(--secondary);
}

/* Decorative Circle in Background */
.card::after {
    content: '';
    position: absolute;
    top: -20px;
    right: -20px;
    width: 80px;
    height: 80px;
    background: var(--primary);
    opacity: 0.1;
    border-radius: 50%;
}

.card h3 {
    margin: 0;
    font-size: 0.85rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

.card .number {
    font-size: 2.8rem;
    font-weight: 700;
    margin-top: 15px;
    /* Gradient Text */
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* ================= WELCOME BOX ================= */
.welcome-box {
    background: linear-gradient(135deg, #4f46e5 0%, #818cf8 100%);
    padding: 40px;
    border-radius: 16px;
    color: white;
    box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
}

.welcome-box h3 { margin: 0 0 10px 0; font-size: 1.5rem; }
.welcome-box p { margin: 0; opacity: 0.9; font-size: 1rem; }

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
    .sidebar {
        width: 80px;
        padding-top: 20px;
    }
    .sidebar a {
        padding: 15px;
        justify-content: center;
        font-size: 1.2rem; /* Make icons bigger */
    }
    .sidebar a span { display: none; } /* Hide text on mobile */
    
    .content { margin-left: 80px; padding: 20px; }
    
    .navbar-brand { font-size: 1.2rem; }
    .card .number { font-size: 2.2rem; }
}
</style>
</head>

<body>

<div class="navbar">
    <div class="navbar-brand">DigitalBazaar <span>Admin</span></div>
    <a href="admin_logout.php" class="logout-btn">Logout <span style="font-size:1.1em;"></span></a>
</div>

<div class="sidebar">
    <a href="dashboard.php" class="active" title="Dashboard"><span>📊</span> &nbsp; Dashboard</a>
    <a href="products.php" title="Products"><span>📦</span> &nbsp; Products</a>
    <a href="manage_offers.php" title="Offers"><span>🎁</span> &nbsp; Offers</a>
    <a href="orders.php" title="Orders"><span>🛒</span> &nbsp; Orders</a>
    <a href="users.php" title="Users"><span>👥</span> &nbsp; Users</a>
    <a href="contactus.php" title="Messages"><span>📩</span> &nbsp; Messages</a>
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
        <p>Manage your store efficiently. Check new orders and messages below.</p>
    </div>

</div>

</body>
</html>