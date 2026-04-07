<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once(__DIR__ . '/../db.php');
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DigitalBazaar</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif}
body{background:#f8fafc}
a{text-decoration:none}

/* HEADER */
header{
    background:#fff;
    position:sticky;
    top:0;
    z-index:9999;
    box-shadow:0 2px 10px rgba(0,0,0,0.05)
}
.header-container{
    max-width:1200px;
    margin:auto;
    padding:0 20px;
    height:70px;
    display:flex;
    align-items:center;
    justify-content:space-between
}

/* LOGO */
.logo a{font-size:1.5rem;font-weight:700;color:#1f2937}
.logo span{color:#4f46e5}

/* NAV */
.nav-menu{display:flex;gap:30px}
.nav-link{color:#4b5563;font-weight:500}
.nav-link:hover{color:#4f46e5}

/* ICONS */
.nav-icons{display:flex;align-items:center;gap:20px}

/* CART */
.cart-box{position:relative;font-size:1.2rem;color:#1f2937}
.cart-badge{
    position:absolute;
    top:-8px;right:-8px;
    background:#ef4444;
    color:#fff;
    width:18px;height:18px;
    border-radius:50%;
    font-size:10px;
    display:flex;
    align-items:center;
    justify-content:center
}

/* USER DROPDOWN */
.user-dropdown{position:relative}
.user-btn{
    display:flex;
    align-items:center;
    gap:8px;
    cursor:pointer;
    padding:5px 10px;
    border-radius:6px
}
.user-btn:hover{background:#f1f5f9}

.dropdown-menu{
    display:none;
    position:absolute;
    right:0;
    top:45px;
    background:#fff;
    width:200px;
    border-radius:8px;
    border:1px solid #e2e8f0;
    box-shadow:0 4px 12px rgba(0,0,0,.1);
    z-index:10000
}
.user-dropdown.active .dropdown-menu{display:block}

.dropdown-item{
    display:flex;
    gap:10px;
    padding:12px 15px;
    color:#334155;
    font-size:.9rem
}
.dropdown-item:hover{background:#f8fafc;color:#4f46e5}

/* LOGIN */
.btn-login-nav{
    padding:8px 20px;
    background:#4f46e5;
    color:#fff;
    border-radius:6px
}

@media(max-width:768px){
    .nav-menu{display:none}
}
</style>
</head>

<body>

<header>
<div class="header-container">

<!-- LOGO -->
<div class="logo">
    <a href="index.php">Digital<span>Bazaar</span></a>
</div>

<!-- NAV -->
<nav class="nav-menu">
    <a href="index.php" class="nav-link">Home</a>
    <a href="products.php" class="nav-link">Shop</a>
    <a href="offers.php" class="nav-link">Offers</a>
    <a href="about.php" class="nav-link">About</a>
    <a href="contact.php" class="nav-link">Contact</a>
</nav>

<!-- ICONS -->
<div class="nav-icons">

<!-- CART -->
<a href="cart.php" class="cart-box">
    <i class="fas fa-shopping-cart"></i>
    <span class="cart-badge" style="<?= ($cart_count > 0) ? '' : 'display:none;' ?>"><?= $cart_count ?></span>
</a>

<?php if(isset($_SESSION['user_id'])): ?>

<!-- USER DROPDOWN -->
<div class="user-dropdown" id="userDropdown">
    <div class="user-btn" onclick="toggleDropdown()">
        <i class="fas fa-user-circle" style="font-size:1.4rem;color:#4f46e5"></i>
        <span><?= htmlspecialchars(explode(" ",$_SESSION['user_name'])[0]); ?></span>
        <i class="fas fa-chevron-down"></i>
    </div>

    <div class="dropdown-menu">

        <a href="edit_profile.php" class="dropdown-item">
            <i class="fas fa-user-edit"></i> Edit Profile
        </a>

        <!-- <a href="my_orders.php" class="dropdown-item">
            <i class="fas fa-box-open"></i> My Orders
        </a> -->

        <a href="user_dashboard.php" class="dropdown-item">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>

        <div style="border-top:1px solid #e2e8f0;margin:5px 0"></div>

        <a href="logout.php" class="dropdown-item" style="color:#ef4444">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>

    </div>
</div>

<?php else: ?>
<a href="login.php" class="btn-login-nav">Login</a>
<?php endif; ?>

</div>
</div>
</header>

<script>
function toggleDropdown(){
    document.getElementById("userDropdown").classList.toggle("active");
}
window.onclick=function(e){
    if(!e.target.closest(".user-dropdown")){
        document.getElementById("userDropdown")?.classList.remove("active");
    }
}
</script>
