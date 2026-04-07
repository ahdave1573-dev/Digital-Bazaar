<?php
session_start();

/* 🔐 LOGIN CHECK */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* 📦 DATABASE */
include("db.php");

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

        // check email already exists (except current user)
        $check = mysqli_query(
            $conn,
            "SELECT id FROM users WHERE email='$email' AND id!='$user_id'"
        );

        if ($check && mysqli_num_rows($check) > 0) {
            $error = "Email already used by another account!";
        } else {

            mysqli_query(
                $conn,
                "UPDATE users 
                 SET name='$name', email='$email', phone='$phone'
                 WHERE id='$user_id'"
            );

            $_SESSION['user_name'] = $name;
            $msg = "Profile updated successfully!";
        }
    }
}
?>

<?php include('includes/header.php'); ?>

<style>
.edit-container{
    max-width:700px;
    margin:40px auto;
    background:#ffffff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}
.edit-title{
    font-size:24px;
    font-weight:700;
    margin-bottom:20px;
}
.form-group{
    margin-bottom:15px;
}
.form-group label{
    font-weight:600;
    display:block;
    margin-bottom:6px;
}
.form-group input{
    width:100%;
    padding:10px;
    border-radius:6px;
    border:1px solid #e2e8f0;
}
.btn-update{
    padding:10px 20px;
    background:#2563eb;
    color:#fff;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
.btn-update:hover{
    background:#1d4ed8;
}
.success{
    color:green;
    margin-bottom:10px;
}
.error{
    color:red;
    margin-bottom:10px;
}
</style>

<div class="edit-container">

    <div class="edit-title">Edit Profile</div>

    <?php if($msg): ?>
        <p class="success"><?= $msg ?></p>
    <?php endif; ?>

    <?php if($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <?php if($user): ?>
    <form method="post">

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name"
                   value="<?= htmlspecialchars($user['name']); ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email"
                   value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone"
                   value="<?= htmlspecialchars($user['phone']); ?>" required>
        </div>

        <button type="submit" name="update_profile" class="btn-update">
            Update Profile
        </button>

        <a href="profile.php" style="margin-left:15px;">Cancel</a>
    </form>
    <?php endif; ?>

</div>

<?php include('includes/footer.php'); ?>
