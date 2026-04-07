<?php
include("../auth.php");   // Admin Check
include("../db.php");

/* ===== DELETE USER ===== */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']); // Security measure (intval)
    mysqli_query($conn, "DELETE FROM users WHERE id='$id'");
    header("Location: users.php");
    exit();
}

/* ===== FETCH USERS ===== */
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Users - DigitalBazaar</title>
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
    --danger: #ef4444;
}

* { box-sizing: border-box; transition: all 0.2s ease; }
body { margin: 0; font-family: 'Poppins', sans-serif; background: var(--bg); color: var(--text-main); }

/* ===== NAVBAR & SIDEBAR ===== */
.navbar {
    position: fixed; top: 0; left: 0; right: 0; height: 70px;
    background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 30px; border-bottom: 1px solid var(--border); z-index: 100;
}
.navbar-brand { font-size: 1.5rem; font-weight: 700; color: var(--primary); }
.logout-btn { background: #fee2e2; color: var(--danger); padding: 8px 18px; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 0.9rem; }
.logout-btn:hover { background: var(--danger); color: white; }

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

/* ===== CONTENT ===== */
.content { margin-left: 260px; margin-top: 70px; padding: 40px; }
h2 { margin-top: 0; font-size: 1.8rem; margin-bottom: 25px; }

/* ===== TABLE CARD ===== */
.table-card {
    background: var(--card-bg);
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    border: 1px solid var(--border);
    overflow: hidden;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    white-space: nowrap;
}

thead {
    background: #f8fafc;
    border-bottom: 2px solid var(--border);
}

th {
    text-align: left;
    padding: 18px 25px;
    color: var(--text-muted);
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

td {
    padding: 18px 25px;
    border-bottom: 1px solid var(--border);
    color: var(--text-main);
    font-size: 0.95rem;
}

tr:hover {
    background-color: #f1f5f9;
}

/* User Profile Style in Table */
.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}
.avatar-circle {
    width: 35px; height: 35px;
    background: var(--secondary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

/* Delete Button */
.btn-delete {
    background: #fee2e2;
    color: var(--danger);
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-block;
}
.btn-delete:hover {
    background: var(--danger);
    color: white;
}

/* Responsive */
@media(max-width: 768px){
    .sidebar{ width: 70px; } .sidebar a span { display: none; } .sidebar a { justify-content: center; }
    .content{ margin-left: 70px; padding: 20px; }
}
</style>
</head>

<body>

<div class="navbar">
    <div class="navbar-brand">DigitalBazaar <span style="font-weight:400; font-size:0.8em; color:#333;">Admin</span></div>
    <a href="logout.php" class="logout-btn">Logout </a>
</div>

<div class="sidebar">
    <a href="dashboard.php" title="Dashboard"><span>📊</span> &nbsp; Dashboard</a>
    <a href="products.php" title="Products"><span>📦</span> &nbsp; Products</a>
    <a href="manage_offers.php" title="Offers"><span>🎁</span> &nbsp; Offers</a>
    <a href="orders.php" title="Orders"><span>🛒</span> &nbsp; Orders</a>
    <a href="users.php" class="active" title="Users"><span>👥</span> &nbsp; Users</a>
    <a href="contactus.php" title="Messages"><span>📩</span> &nbsp; Messages</a>
</div>

<div class="content">

    <h2>👥 Registered Users</h2>

    <div class="table-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Joined Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($users) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($users)): 
                            // Get first letter for avatar
                            $initial = strtoupper(substr($row['name'], 0, 1));
                        ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td>
                                <div class="user-info">
                                    <div class="avatar-circle"><?= $initial ?></div>
                                    <div style="font-weight:500;"><?= $row['name']; ?></div>
                                </div>
                            </td>
                            <td><?= $row['email']; ?></td>
                            <td><?= $row['phone']; ?></td>
                            <td style="color:#6b7280;">
                                <?= date('d M Y', strtotime($row['created_at'])); ?>
                            </td>
                            <td>
                                <a href="users.php?delete=<?= $row['id']; ?>" 
                                   class="btn-delete"
                                   onclick="return confirm('Are you sure you want to delete this user?');">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center; padding:30px; color:#888;">
                                No users found yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>