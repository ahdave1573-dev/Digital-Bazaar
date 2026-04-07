<?php
include("../auth.php");
include("../db.php");

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
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #eef2ff;
            --secondary: #818cf8;
            --sidebar-bg: #1e293b;
            --danger: #ef4444;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --bg-body: #f8fafc;
            --card-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* ================= NAVBAR ================= */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 70px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        .navbar-brand span { color: var(--text-dark); font-weight: 400; }

        .logout-btn {
            background: #fee2e2;
            color: var(--danger);
            padding: 8px 18px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
        }
        .logout-btn:hover {
            background: var(--danger);
            color: white;
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            bottom: 0;
            width: 260px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            padding-top: 30px;
            z-index: 900;
            border-top-right-radius: 20px;
            transition: var(--transition);
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
            padding-left: 35px;
        }

        /* ================= CONTENT ================= */
        .content {
            margin-left: 260px;
            margin-top: 70px;
            padding: 40px;
            transition: var(--transition);
        }

        .container {
            width: 100%;
            max-width: 1200px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }


        .table-container {
            padding: 20px 0;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        th {
            padding: 12px 20px;
            text-align: left;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr.offer-row {
            background: white;
            transition: var(--transition);
        }

        tr.offer-row td {
            padding: 20px;
            vertical-align: middle;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
        }

        tr.offer-row td:first-child {
            border-left: 1px solid #f1f5f9;
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
        }

        tr.offer-row td:last-child {
            border-right: 1px solid #f1f5f9;
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        tr.offer-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .offer-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .offer-title-info h4 {
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .offer-title-info span {
            font-size: 0.8rem;
            color: var(--text-muted);
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .badge-discount {
            background: var(--primary-light);
            color: var(--primary);
            padding: 6px 12px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .price-info .discounted {
            font-weight: 800;
            color: var(--text-dark);
            font-size: 1.1rem;
            display: block;
        }

        .price-info .original {
            font-size: 0.85rem;
            color: var(--text-muted);
            text-decoration: line-through;
        }

        .actions {
            display: flex;
            gap: 12px;
        }

        .btn-action {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .btn-delete {
            background: #fef2f2;
            color: var(--danger);
        }

        .btn-delete:hover {
            background: var(--danger);
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        .alert {
            padding: 16px 24px;
            border-radius: 16px;
            margin: 0 40px 30px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #10b981; margin-bottom: 20px; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #ef4444; margin-bottom: 20px; }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: #e2e8f0;
            margin-bottom: 16px;
        }

        .empty-state p {
            color: var(--text-muted);
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar a span { display: none; }
            .sidebar a { justify-content: center; padding: 15px; }
            .content { margin-left: 70px; padding: 20px; }
            .header { flex-direction: column; text-align: center; gap: 20px; }
            .table-container { padding: 20px; }
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="navbar-brand">DigitalBazaar <span>Admin</span></div>
    <a href="admin_logout.php" class="logout-btn">Logout</a>
</div>

<div class="sidebar">
    <a href="dashboard.php" title="Dashboard"><span>📊</span> <span>&nbsp; Dashboard</span></a>
    <a href="products.php" title="Products"><span>📦</span> <span>&nbsp; Products</span></a>
    <a href="manage_offers.php" class="active" title="Offers"><span>🎁</span> <span>&nbsp; Offers</span></a>
    <a href="orders.php" title="Orders"><span>🛒</span> <span>&nbsp; Orders</span></a>
    <a href="users.php" title="Users"><span>👥</span> <span>&nbsp; Users</span></a>
    <a href="contactus.php" title="Messages"><span>📩</span> <span>&nbsp; Messages</span></a>
</div>

<div class="content">

    <h2 style="margin-bottom: 30px;">🎁 Manage Offers</h2>

    <div class="table-container">
        <?php if ($success_msg): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= $success_msg ?>
            </div>
        <?php endif; ?>

        <?php if ($error_msg): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= $error_msg ?>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th width="15%">Visual</th>
                    <th width="35%">Campaign Basis</th>
                    <th width="15%">Incentive</th>
                    <th width="20%">Price Point</th>
                    <th width="15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($offers) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($offers)): ?>
                        <tr class="offer-row">
                            <td>
                                <?php if ($row['image']): ?>
                                    <img src="../assets/images/offers/<?= $row['image'] ?>" class="offer-img">
                                <?php else: ?>
                                    <div style="width:70px;height:70px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;border-radius:12px">
                                        <i class="fas fa-gift" style="color:#cbd5e1; font-size: 1.5rem;"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="offer-title-info">
                                    <h4><?= htmlspecialchars($row['title']) ?></h4>
                                    <span>Product ID: <?= $row['product_id'] > 0 ? $row['product_id'] : 'Site-wide' ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-discount"><?= $row['discount_percentage'] ?>% OFF</span>
                            </td>
                            <td>
                                <div class="price-info">
                                    <span class="discounted">₹<?= number_format($row['discount_price']) ?></span>
                                    <span class="original">₹<?= number_format($row['original_price'] ?? 0) ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="?delete=<?= $row['id'] ?>" class="btn-action btn-delete" 
                                       onclick="return confirm('Are you sure you want to end this campaign?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-box-open"></i>
                                <p>No active offers found. Start by creating a new one!</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
