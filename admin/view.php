<?php
include("../auth.php");
include("../db.php");

/* ===== FETCH PRODUCTS ===== */
$products = mysqli_query($conn,"SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Product List</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
:root{
 --primary:#4f46e5;
 --sidebar:#1f2937;
 --bg:#f3f4f6;
 --border:#e5e7eb;
}
body{margin:0;font-family:Segoe UI;background:var(--bg)}
.navbar{
 height:60px;background:#fff;padding:0 25px;
 display:flex;align-items:center;justify-content:space-between;
 border-bottom:1px solid var(--border)
}
.sidebar{
 position:fixed;top:60px;left:0;width:260px;
 height:calc(100vh - 60px);background:var(--sidebar)
}
.sidebar a{
 display:block;padding:14px 25px;
 color:#d1d5db;text-decoration:none
}
.sidebar a:hover,.sidebar a.active{
 background:#374151;color:#fff
}
.content{
 margin-left:260px;padding:30px
}
.header{
 display:flex;justify-content:space-between;align-items:center
}
.add-btn{
 background:var(--primary);color:#fff;
 padding:10px 20px;border-radius:8px;
 text-decoration:none;font-weight:600
}
.table-box{
 margin-top:20px;background:#fff;
 border-radius:12px;border:1px solid var(--border);
 overflow-x:auto
}
table{width:100%;border-collapse:collapse}
th,td{
 padding:14px;border-bottom:1px solid var(--border);
 text-align:left
}
th{background:#f9fafb;font-size:13px}
img{width:60px;height:60px;object-fit:cover;border-radius:8px}
.actions a{
 padding:6px 12px;border-radius:6px;
 color:#fff;text-decoration:none;font-size:13px
}
.edit{background:#10b981}
.delete{background:#ef4444}
</style>
</head>

<body>

<div class="navbar">
    <b>DigitalBazaar Admin</b>
    <a href="logout.php">Logout</a>
</div>

<div class="sidebar">
    <a href="admin.php">Dashboard</a>
    <a href="view.php" class="active">Products</a>
    <a href="orders.php">Orders</a>
    <a href="users.php">Users</a>
    <a href="contactus.php">Messages</a>
</div>

<div class="content">

<div class="header">
    <h2>Product List</h2>
    <a href="add_product.php" class="add-btn">+ Add Product</a>
</div>

<div class="table-box">
<table>
<thead>
<tr>
    <th>ID</th>
    <th>Image</th>
    <th>Name</th>
    <th>Category ID</th>
    <th>Category</th>
    <th>Price</th>
    <th>Description</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php if(mysqli_num_rows($products)>0):
while($row=mysqli_fetch_assoc($products)): ?>
<tr>
    <td>#<?= $row['id']; ?></td>

    <td>
        <?php if($row['image'] && file_exists("../assets/images/".$row['image'])): ?>
            <img src="../assets/images/<?= $row['image']; ?>">
        <?php else: ?>
            No Image
        <?php endif; ?>
    </td>

    <td><b><?= $row['name']; ?></b></td>

    <td><?= $row['category_id']; ?></td>

    <td><?= $row['category']; ?></td>

    <td><b>₹<?= number_format($row['selling_price']); ?></b></td>

    <td><?= substr($row['description'],0,40); ?>...</td>

    <td class="actions">
        <a class="edit" href="edit_product.php?id=<?= $row['id']; ?>">Edit</a>
        <a class="delete"
           href="delete_product.php?id=<?= $row['id']; ?>"
           onclick="return confirm('Product delete karvo chhe?')">
           Delete
        </a>
    </td>
</tr>
<?php endwhile; else: ?>
<tr>
<td colspan="8" align="center">No Products Found</td>
</tr>
<?php endif; ?>

</tbody>
</table>
</div>

</div>

</body>
</html>
