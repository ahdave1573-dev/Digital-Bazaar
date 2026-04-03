<?php
include("../config/auth.php");
include("../config/db.php");

$success_msg = "";
$error_msg = "";

// Handle Deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // First get the image to delete it from disk
    $imgRes = mysqli_query($conn, "SELECT image FROM offers WHERE id = $id");
    if ($imgRes && mysqli_num_rows($imgRes) > 0) {
        $offer = mysqli_fetch_assoc($imgRes);
        if (!empty($offer['image'])) {
            $imgPath = __DIR__ . '/../assets/images/offers/' . $offer['image'];
            if (file_exists($imgPath)) {
                @unlink($imgPath);
            }
        }
    }

    $sql = "DELETE FROM offers WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $success_msg = "Offer deleted successfully!";
    } else {
        $error_msg = "Error deleting offer: " . mysqli_error($conn);
    }
}

$offers = mysqli_query($conn, "SELECT * FROM offers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Offers - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --primary: #4f46e5;
            --danger: #ef4444;
            --bg: #f8fafc;
        }
        body{font-family:'Poppins',sans-serif;background:var(--bg);margin:0;padding:20px}
        .container{max-width:1000px;margin:0 auto;background:#fff;padding:30px;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.05)}
        h2{margin-top:0;color:#1f2937}
        .alert{padding:12px;border-radius:8px;margin-bottom:20px}
        .alert-success{background:#dcfce7;color:#166534}
        .alert-error{background:#fee2e2;color:#991b1b}
        table{width:100%;border-collapse:collapse;margin-top:20px}
        th,td{padding:15px;text-align:left;border-bottom:1px solid #e2e8f0}
        th{background:#f1f5f9;font-weight:600}
        .offer-img{width:60px;height:60px;object-fit:cover;border-radius:6px}
        .btn-delete{background:var(--danger);color:#fff;padding:8px 12px;border-radius:6px;text-decoration:none;font-size:0.85rem;font-weight:600}
        .btn-delete:hover{background:#dc2626}
        .back-link{display:inline-block;margin-bottom:20px;text-decoration:none;color:var(--primary);font-weight:600}
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    <h2>Manage Offers</h2>

    <?php if ($success_msg): ?>
        <div class="alert alert-success"><?= $success_msg ?></div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
        <div class="alert alert-error"><?= $error_msg ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Product ID</th>
                <th>Discount</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($offers) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($offers)): ?>
                    <tr>
                        <td>
                            <?php if ($row['image']): ?>
                                <img src="../assets/images/offers/<?= $row['image'] ?>" class="offer-img">
                            <?php else: ?>
                                <div style="width:60px;height:60px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;border-radius:6px">🎁</div>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= $row['product_id'] > 0 ? $row['product_id'] : 'None' ?></td>
                        <td><?= $row['discount_percentage'] ?>%</td>
                        <td>₹<?= number_format($row['discount_price']) ?></td>
                        <td>
                            <a href="?delete=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this offer?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;padding:30px;color:#6b7280">No offers found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
