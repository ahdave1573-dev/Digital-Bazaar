<?php
session_start();

/* 🔐 LOGIN CHECK */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* 📦 DATABASE */
include('config/db.php');
include('includes/header.php');

$user_id = $_SESSION['user_id'];
$msg = "";
$error = "";

/* ======================
   FETCH USER DATA
====================== */
$user = null;
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id' LIMIT 1");
if ($query && mysqli_num_rows($query) == 1) {
    $user = mysqli_fetch_assoc($query);
}

/* ======================
   UPDATE PROFILE
====================== */
if (isset($_POST['update_profile'])) {

    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    if (empty($name) || empty($email) || empty($phone)) {
        $error = "All fields are required!";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address!";
    }
    elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = "Enter valid 10 digit phone number!";
    }
    else {
        // Check if email already used by another user
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' AND id!='$user_id'");

        if ($check && mysqli_num_rows($check) > 0) {
            $error = "Email already used by another account!";
        } else {
            mysqli_query($conn, 
                "UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id='$user_id'"
            );

            $_SESSION['user_name'] = $name; // Update session
            $msg = "Profile updated successfully!";
            
            // Refresh User Data for immediate display
            $user['name'] = $name;
            $user['email'] = $email;
            $user['phone'] = $phone;
        }
    }
}
?>

<style>
    body {
        background-color: #f8fafc;
        font-family: 'Poppins', sans-serif;
    }

    .edit-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh; /* Centers vertically */
        padding: 40px 20px;
    }

    .edit-card {
        background: #ffffff;
        width: 100%;
        max-width: 500px;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    .card-header {
        text-align: center;
        margin-bottom: 30px;
    }
    .card-header h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 5px;
    }
    .card-header p {
        color: #64748b;
        font-size: 0.95rem;
    }

    /* Alerts */
    .alert {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        text-align: center;
        font-weight: 500;
    }
    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    /* Form Styles */
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #334155;
        font-size: 0.95rem;
    }
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 1rem;
        font-family: inherit;
        outline: none;
        transition: 0.3s;
    }
    .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    /* Buttons */
    .btn-update {
        width: 100%;
        padding: 14px;
        background: #4f46e5;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-update:hover {
        background: #4338ca;
        transform: translateY(-2px);
    }

    .cancel-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        text-decoration: none;
        color: #64748b;
        font-size: 0.9rem;
        transition: 0.3s;
    }
    .cancel-link:hover { color: #4f46e5; }

    @media (max-width: 480px) {
        .edit-card { padding: 25px; }
    }
</style>

<div class="edit-wrapper">
    <div class="edit-card">
        
        <div class="card-header">
            <h2>Edit Profile</h2>
            <p>Update your personal information</p>
        </div>

        <?php if($msg): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= $msg ?>
            </div>
        <?php endif; ?>

        <?php if($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if($user): ?>
        <form method="post">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control"
                       value="<?= htmlspecialchars($user['name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control"
                       value="<?= htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control"
                       value="<?= htmlspecialchars($user['phone']); ?>" required>
            </div>

            <button type="submit" name="update_profile" class="btn-update">
                Save Changes
            </button>

            <a href="profile.php" class="cancel-link">Cancel</a>

        </form>
        <?php else: ?>
            <p style="text-align:center;color:red;">User data not found.</p>
        <?php endif; ?>

    </div>
</div>

<?php include('includes/footer.php'); ?>