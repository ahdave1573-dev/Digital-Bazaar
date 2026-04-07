<?php
session_start();
// જો તમારી પાસે auth.php ન હોય તો નીચેની લાઈન comment કરી દેજો
include("../auth.php"); 
include("../db.php");

/* ===== IMAGE PATH CONFIGURATION ===== */
$upload_dir = "../assets/images/"; 

/* ===== FETCH PRODUCTS ===== */
$query = "SELECT * FROM products ORDER BY id DESC";
$products = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product List - DigitalBazaar</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* Variables */
    :root {
        --primary: #4f46e5;
        --sidebar-bg: #1e293b; /* Dark Color from Image */
        --bg-light: #f3f4f6;
        --text-dark: #111827;
        --text-gray: #6b7280;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    
    body { 
        background: var(--bg-light); 
        min-height: 100vh;
    }

    /* ================= NAVBAR (TOP HEADER) ================= */
    .navbar {
        position: fixed;
        top: 0; left: 0; right: 0;
        height: 70px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
        z-index: 100;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    .brand {
        font-size: 22px;
        font-weight: 700;
        color: var(--primary);
    }
    .brand span { color: var(--text-dark); }

    .logout-btn {
        background: #fee2e2;
        color: #ef4444;
        padding: 8px 20px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: 0.3s;
    }
    .logout-btn:hover { background: #ef4444; color: white; }


    /* ================= SIDEBAR (WITH CURVE) ================= */
    .sidebar {
        position: fixed;
        top: 70px; /* Navbar height જેટલી જગ્યા છોડીને નીચે */
        left: 0;
        bottom: 0;
        width: 260px;
        background: var(--sidebar-bg);
        color: #fff;
        padding-top: 30px;
        
        /* THE CURVE EFFECT */
        border-top-right-radius: 30px; 
        
        z-index: 90;
        display: flex;
        flex-direction: column;
        box-shadow: 4px 0 10px rgba(0,0,0,0.05);
    }

    .sidebar a {
        display: flex;
        align-items: center;
        padding: 16px 30px;
        color: #94a3b8;
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        margin-bottom: 5px;
    }

    /* Hover & Active State */
    .sidebar a:hover, .sidebar a.active {
        color: #fff;
        background: rgba(255, 255, 255, 0.05);
        border-left-color: var(--primary);
    }

    .sidebar a i {
        margin-right: 15px;
        font-style: normal;
        font-size: 18px;
        width: 25px;
        text-align: center;
    }


    /* ================= MAIN CONTENT ================= */
    .main-content {
        margin-left: 260px; /* Sidebar width */
        margin-top: 70px;   /* Navbar height */
        padding: 30px;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .page-header h2 { font-size: 24px; color: var(--text-dark); font-weight: 700; }

    /* Add Button */
    .btn-add {
        background: var(--primary);
        color: white;
        padding: 10px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        transition: 0.3s;
    }
    .btn-add:hover { background: #4338ca; transform: translateY(-2px); }

    /* Table Style */
    .table-box {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        overflow-x: auto;
    }

    table { width: 100%; border-collapse: collapse; min-width: 800px; }
    
    th {
        text-align: left; padding: 15px;
        background: #f9fafb; color: var(--text-gray);
        font-size: 12px; font-weight: 700; text-transform: uppercase;
        border-bottom: 2px solid #e5e7eb;
    }
    td {
        padding: 15px; border-bottom: 1px solid #f3f4f6;
        color: var(--text-dark); font-size: 14px; vertical-align: middle;
    }

    /* Images & Badges */
    .prod-img { width: 45px; height: 45px; border-radius: 6px; object-fit: cover; border: 1px solid #eee; }
    .badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; background: #e0e7ff; color: var(--primary); font-weight: 600; }

    /* Buttons */
    .btn-sm { padding: 6px 12px; border-radius: 6px; font-size: 12px; text-decoration: none; display: inline-block; font-weight: 500; }
    .edit { background: #dbeafe; color: #1e40af; margin-right: 5px; }
    .delete { background: #fee2e2; color: #991b1b; }
    .offer { background: #ecfdf5; color: #065f46; margin-top: 5px; display: block; text-align: center; width: fit-content; }

    /* Responsive */
    @media (max-width: 900px) {
        .sidebar { width: 70px; padding-top: 20px; }
        .sidebar a span { display: none; }
        .sidebar a { justify-content: center; padding: 15px; }
        .sidebar a i { margin: 0; font-size: 20px; }
        .main-content { margin-left: 70px; }
    }
</style>
</head>
<body>

    <div class="navbar">
        <div class="brand">DigitalBazaar <span>Admin</span></div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="sidebar">
        <a href="dashboard.php"><i>📊</i> <span>Dashboard</span></a>
        <a href="products.php" class="active"><i>📦</i> <span>Products</span></a>
        <a href="manage_offers.php"><i>🎁</i> <span>Offers</span></a>
        <a href="orders.php"><i>🛒</i> <span>Orders</span></a>
        <a href="users.php"><i>👥</i> <span>Users</span></a>
        <a href="contactus.php"><i>📩</i> <span>Messages</span></a>
        
      
    </div>

    <div class="main-content">
        
        <div class="page-header">
            <h2>Product List</h2>
            <a href="add_product.php" class="btn-add">+ Add Product</a>
        </div>

        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="10%">Image</th>
                        <th width="20%">Name</th>
                        <th width="15%">Category</th>
                        <th width="10%">Price</th>
                        <th width="25%">Description</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($products) > 0) {
                        while($row = mysqli_fetch_assoc($products)) {
                            $img_src = $upload_dir . $row['image'];
                            $desc = strlen($row['description']) > 35 ? substr($row['description'],0,35)."..." : $row['description'];
                    ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td>
                            <?php if(!empty($row['image']) && file_exists($img_src)): ?>
                                <img src="<?= $img_src; ?>" class="prod-img" alt="img">
                            <?php else: ?>
                                <span style="font-size:10px; color:#999;">No Img</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight:500;"><?= $row['name']; ?></td>
                        <td><span class="badge"><?= $row['category']; ?></span></td>
                        <td style="font-weight:600;">₹<?= number_format($row['selling_price']); ?></td>
                        <td style="color:#666; font-size:13px;"><?= $desc; ?></td>
                        <td>
                            <a href="edit_product.php?id=<?= $row['id']; ?>" class="btn-sm edit">Edit</a>
                            <a href="delete_product.php?id=<?= $row['id']; ?>" class="btn-sm delete" onclick="return confirm('Delete?');">Delete</a>
                            <a href="add_offer.php?id=<?= $row['id']; ?>" class="btn-sm offer">Add Offer</a>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else { echo "<tr><td colspan='7' align='center' style='padding:30px;'>No Products Found</td></tr>"; } 
                    ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>