<?php
include("../auth.php");
include("../db.php");

// 1. Check ID
if(!isset($_GET['id'])){
    header("Location: view.php");
    exit();
}

$id = $_GET['id'];
$image_path_folder = "../assets/images/"; // Image Path Logic

// 2. Fetch Product Data
$product = mysqli_query($conn,"SELECT * FROM products WHERE id='$id'");
if(mysqli_num_rows($product) == 0){
    die("Product Not Found");
}
$row = mysqli_fetch_assoc($product);

$message = "";
$status = ""; // For CSS class

/* ::::: UPDATE PRODUCT ::::: */
if(isset($_POST['update_product'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['selling_price'];
    
    // Check if category_id exists in POST, else keep old one (Safety)
    $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : $row['category_id'];

    $image_name = $row['image']; // Keep old image by default

    // If New Image Selected
    if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ""){
        
        $new_image = time()."_".$_FILES['image']['name'];
        $upload_path = $image_path_folder . $new_image;

        if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)){
            // Delete Old Image (Optional)
            if(!empty($row['image']) && file_exists($image_path_folder.$row['image'])){
                unlink($image_path_folder.$row['image']);
            }
            $image_name = $new_image;
        }
    }

    $update = mysqli_query($conn,
        "UPDATE products SET 
         name='$name', 
         description='$description',
         selling_price='$price',
         category_id='$category_id', 
         image='$image_name'
         WHERE id='$id'"
    );

    if($update){
        $message = "Product Updated Successfully!";
        $status = "success";
        
        // Refresh data
        $row['name'] = $name;
        $row['description'] = $description;
        $row['selling_price'] = $price;
        $row['image'] = $image_name;
        
    }else{
        $message = "Failed to Update Product";
        $status = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Product - DigitalBazaar</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* 1. Global Variables & Reset */
:root{
    --primary: #4f46e5;
    --primary-dark: #4338ca;
    --sidebar-bg: #1f2937; /* Dark sidebar */
    --bg: #f8fafc;
    --card-bg: #ffffff;
    --text-main: #1f2937;
    --text-muted: #6b7280;
    --border: #e2e8f0;
    --danger: #ef4444;
    --success-bg: #dcfce7;
    --success-text: #166534;
    --error-bg: #fee2e2;
    --error-text: #991b1b;
}

* { box-sizing: border-box; transition: all 0.2s ease; }
body { margin: 0; font-family: 'Poppins', sans-serif; background: var(--bg); color: var(--text-main); }

/* 2. Sidebar Style */
.sidebar {
    position: fixed; top: 60px; left: 0; bottom: 0; width: 250px;
    background: var(--sidebar-bg);
    padding-top: 20px;
}

.sidebar a {
    display: flex; align-items: center; padding: 15px 25px;
    color: #d1d5db; text-decoration: none; font-weight: 500;
    border-left: 4px solid transparent; transition: 0.2s;
}
.sidebar a:hover { background: #374151; color: #fff; }
.sidebar a.active {
    background: #374151; color: #fff; border-left: 4px solid #3b82f6;
}
/* Colorful Icons */
.icon { margin-right: 15px; font-size: 1.2rem; width: 25px; text-align: center; }

/* 3. Main Content & Navbar */
.content { margin-left: 250px; margin-top: 60px; padding: 40px; display: flex; justify-content: center; }

.navbar {
    position: fixed; top: 0; left: 0; right: 0; height: 60px;
    background: #fff; display: flex; align-items: center; justify-content: space-between;
    padding: 0 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); z-index: 100;
}
.brand { font-size: 1.4rem; font-weight: 700; color: #333; }
.brand span { color: var(--primary); }

.logout-btn {
    background: #fee2e2; color: #dc2626; padding: 8px 20px; border-radius: 6px;
    text-decoration: none; font-weight: 600; font-size: 0.9rem;
}
.logout-btn:hover { background: #dc2626; color: white; }

/* 4. Edit Form Card */
.edit-card {
    background: var(--card-bg);
    width: 100%; max-width: 600px;
    padding: 40px; border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    border: 1px solid var(--border);
}

.card-header-text { text-align: center; margin-bottom: 30px; }
.card-header-text h2 { margin: 0; color: var(--primary); font-size: 1.8rem; }
.card-header-text p { margin: 5px 0 0; color: var(--text-muted); font-size: 0.9rem; }

/* Alerts */
.alert { padding: 12px; border-radius: 8px; text-align: center; margin-bottom: 20px; font-weight: 500; }
.success { background: var(--success-bg); color: var(--success-text); }
.error { background: var(--error-bg); color: var(--error-text); }

/* Form Controls */
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.95rem; }
.form-control {
    width: 100%; padding: 10px 15px; border: 1px solid var(--border);
    border-radius: 8px; font-size: 1rem; font-family: inherit; outline: none;
}
.form-control:focus { border-color: var(--primary); }
textarea.form-control { resize: vertical; min-height: 100px; }

/* Image Preview */
.img-preview-box {
    text-align: center; background: #f9fafb; border: 2px dashed var(--border);
    padding: 20px; border-radius: 12px; margin-bottom: 10px;
}
.img-preview-box img {
    max-width: 150px; max-height: 150px; border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1); object-fit: cover;
}
.no-img-text { color: var(--text-muted); font-size: 0.9rem; font-style: italic; }

/* Submit Button */
.btn-submit {
    width: 100%; padding: 12px; background: var(--primary); color: white;
    border: none; border-radius: 8px; font-size: 1rem; font-weight: 600;
    cursor: pointer; margin-top: 10px;
}
.btn-submit:hover { background: var(--primary-dark); }

.back-link {
    display: block; text-align: center; margin-top: 20px;
    color: var(--text-muted); text-decoration: none; font-weight: 500;
}
.back-link:hover { color: var(--primary); text-decoration: underline; }

/* Mobile */
@media(max-width: 768px){
    .sidebar{ width: 60px; } .sidebar a span:last-child { display: none; } .sidebar a { justify-content: center; padding: 15px 0; }
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

    <div class="edit-card">
        <div class="card-header-text">
            <h2>Edit Product</h2>
            <p>Updating: "<?= htmlspecialchars($row['name']); ?>"</p>
        </div>

        <?php if($message != ""): ?>
            <div class="alert <?= $status; ?>"><?= $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"><?= htmlspecialchars($row['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Selling Price (₹)</label>
                <input type="number" name="selling_price" class="form-control" value="<?= htmlspecialchars($row['selling_price']); ?>" required>
            </div>

            <input type="hidden" name="category_id" value="<?= $row['category_id']; ?>">

            <div class="form-group">
                <label>Current Image</label>
                <div class="img-preview-box">
                    <?php 
                        $img_full_path = $image_path_folder . $row['image'];
                        if(!empty($row['image']) && file_exists($img_full_path)): 
                    ?>
                        <img src="<?= $img_full_path; ?>" alt="Current Product Image">
                        <p style="margin:5px 0 0; font-size:0.8rem; color:#6b7280;">(Current Uploaded Image)</p>
                    <?php else: ?>
                        <p class="no-img-text">No Image Uploaded</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Change Image (Optional)</label>
                <input type="file" name="image" class="form-control" style="padding:10px;">
            </div>

            <button type="submit" name="update_product" class="btn-submit">Update Product</button>

        </form>

        <a href="products.php" class="back-link">← Back to Product List</a>

    </div>

</div>

</body>
</html>