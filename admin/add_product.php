<?php
include("../auth.php");
include("../db.php");

$message = "";
$status = "";

/* ================= SAVE PRODUCT ================= */
if (isset($_POST['add_product'])) {

    $category_id   = (int)$_POST['category_id'];
    $name          = mysqli_real_escape_string($conn, $_POST['name']);
    $description   = mysqli_real_escape_string($conn, $_POST['description']);
    $selling_price = mysqli_real_escape_string($conn, $_POST['selling_price']);

    /* ===== FETCH CATEGORY NAME ===== */
    $cat_q = mysqli_query($conn, "SELECT name FROM categories WHERE id='$category_id' LIMIT 1");
    $cat_r = mysqli_fetch_assoc($cat_q);
    $category_name = $cat_r['name'] ?? '';

    /* ===== IMAGE UPLOAD ===== */
    $image_name = "";
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        
        // તમારા ફોલ્ડરનો પાથ
        $target_dir = "../assets/images/"; 

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_name);
    }

    /* ===== INSERT PRODUCT ===== */
    $sql = "INSERT INTO products (category_id, category, name, description, selling_price, image)
            VALUES ('$category_id', '$category_name', '$name', '$description', '$selling_price', '$image_name')";

    if (mysqli_query($conn, $sql)) {
        $message = "Product Added Successfully!";
        $status = "success";
    } else {
        $message = "Failed to Add Product!";
        $status = "error";
    }
}

/* ================= FETCH CATEGORIES ================= */
$catQuery = mysqli_query($conn, "SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Product - DigitalBazaar</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* 1. Global Variables */
:root{
    --primary: #4f46e5;
    --sidebar-bg: #1f2937; /* Dark Blue-Gray from screenshot */
    --bg: #f3f4f6;
    --text-main: #333;
    --text-light: #9ca3af;
    --success-bg: #dcfce7;
    --success-text: #166534;
    --error-bg: #fee2e2;
    --error-text: #991b1b;
}

* { box-sizing: border-box; }
body { margin: 0; font-family: 'Poppins', sans-serif; background: var(--bg); color: var(--text-main); }

/* 2. Sidebar Style (Exactly like screenshot) */
.sidebar {
    position: fixed; top: 60px; left: 0; bottom: 0; width: 250px;
    background: var(--sidebar-bg);
    padding-top: 20px;
}

.sidebar a {
    display: flex; align-items: center;
    padding: 15px 25px;
    color: #d1d5db; /* Light grey text */
    text-decoration: none;
    font-size: 1rem;
    font-weight: 500;
    transition: 0.2s;
    border-left: 4px solid transparent;
}

.sidebar a:hover {
    background: #374151;
    color: #fff;
}

.sidebar a.active {
    background: #374151; /* Active background */
    color: #fff;
    border-left: 4px solid #3b82f6; /* Blue accent */
}

/* Icons using Emojis to match screenshot colors */
.icon {
    margin-right: 15px;
    font-size: 1.2rem;
    width: 25px; text-align: center;
}

/* 3. Navbar Style */
.navbar {
    position: fixed; top: 0; left: 0; right: 0; height: 60px;
    background: #fff;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 30px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    z-index: 100;
}

.brand { font-size: 1.4rem; font-weight: 700; color: #333; }
.brand span { color: var(--primary); } /* Blue "DigitalBazaar" */

.logout-btn {
    background: #fee2e2; color: #dc2626;
    padding: 8px 20px; border-radius: 6px;
    text-decoration: none; font-weight: 600; font-size: 0.9rem;
}
.logout-btn:hover { background: #dc2626; color: white; }

/* 4. Content Area */
.content {
    margin-left: 250px;
    margin-top: 60px;
    padding: 40px;
    display: flex; justify-content: center; /* Center the form */
}

/* 5. Form Card */
.add-card {
    background: #fff;
    width: 100%; max-width: 600px;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.card-title { font-size: 1.5rem; font-weight: 600; margin-bottom: 25px; text-align: center; }

.alert { padding: 12px; border-radius: 8px; text-align: center; margin-bottom: 20px; }
.success { background: var(--success-bg); color: var(--success-text); }
.error { background: var(--error-bg); color: var(--error-text); }

.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 500; }
.form-control {
    width: 100%; padding: 10px 15px;
    border: 1px solid #d1d5db; border-radius: 8px;
    font-size: 1rem; font-family: 'Poppins', sans-serif;
}
.form-control:focus { outline: none; border-color: var(--primary); }
textarea.form-control { resize: vertical; min-height: 100px; }

.btn-submit {
    width: 100%; padding: 12px;
    background: var(--primary); color: white;
    border: none; border-radius: 8px;
    font-size: 1rem; font-weight: 600; cursor: pointer;
}
.btn-submit:hover { background: #4338ca; }

.back-link { display: block; text-align: center; margin-top: 20px; color: #6b7280; text-decoration: none; }
.back-link:hover { color: var(--primary); }

/* Mobile Responsive */
@media(max-width: 768px){
    .sidebar { width: 60px; }
    .sidebar a span { display: none; }
    .sidebar a { justify-content: center; padding: 15px 0; }
    .icon { margin: 0; font-size: 1.5rem; }
    .content { margin-left: 60px; padding: 20px; }
}
</style>
</head>

<body>

<div class="navbar">
    <div class="brand"><span>DigitalBazaar</span> Admin</div>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<div class="sidebar">
    <a href="dashboard.php">
        <span class="icon">📊</span> <span>Dashboard</span>
    </a>
    
    <a href="products.php" class="active">
        <span class="icon">📦</span> <span>Products</span>
    </a>
    
    <a href="orders.php">
        <span class="icon">🛒</span> <span>Orders</span>
    </a>
    
    <a href="users.php">
        <span class="icon">👥</span> <span>Users</span>
    </a>
    
    <a href="contactus.php">
        <span class="icon">📩</span> <span>Messages</span>
    </a>
</div>

<div class="content">

    <div class="add-card">
        <div class="card-title">Add New Product</div>

        <?php if ($message): ?>
            <div class="alert <?= $status; ?>"><?= $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php 
                        mysqli_data_seek($catQuery, 0);
                        while ($cat = mysqli_fetch_assoc($catQuery)): 
                    ?>
                        <option value="<?= $cat['id']; ?>"><?= $cat['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" class="form-control" placeholder="Product Name" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" placeholder="Product Details..."></textarea>
            </div>

            <div class="form-group">
                <label>Selling Price (₹)</label>
                <input type="number" step="0.01" name="selling_price" class="form-control" placeholder="0.00" required>
            </div>

            <div class="form-group">
                <label>Product Image</label>
                <input type="file" name="image" class="form-control" accept="image/*" style="padding:10px;" required>
            </div>

            <button type="submit" name="add_product" class="btn-submit">Add Product</button>

        </form>

        <a href="products.php" class="back-link">← Back to Product List</a>
    </div>

</div>

</body>
</html>