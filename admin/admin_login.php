<?php
session_start();

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Database Connection
    include("../db.php");

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Secure Login Query
    $stmt = $conn->prepare("SELECT id, username FROM admins WHERE username=? AND password=? LIMIT 1");
    $stmt->bind_param("ss",$username,$password);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows == 1){
        
        $stmt->bind_result($id,$u);
        $stmt->fetch();

        // Admin Session Set
        $_SESSION['admin_id'] = $id;
        $_SESSION['admin_username'] = $u;

        header("Location: dashboard.php");
        exit();
    }
    else{
        $message = "Invalid username or password!";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - DigitalBazaar</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    /* ===== NEW CSS DESIGN ===== */
    :root {
        --primary-color: #4e54c8;
        --secondary-color: #8f94fb;
        --bg-gradient: linear-gradient(135deg, #4e54c8, #8f94fb);
        --text-color: #333;
        --input-bg: #f5f6fa;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--bg-gradient);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .login-container {
        background-color: #ffffff;
        padding: 50px 40px;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2); /* Deep shadow */
        text-align: center;
        width: 100%;
        max-width: 420px;
        transition: transform 0.3s ease;
    }

    /* Thodu animation hover par */
    .login-container:hover {
        transform: translateY(-5px);
    }

    h2 {
        margin: 0 0 30px 0;
        color: var(--primary-color);
        font-weight: 700;
        font-size: 28px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .input-group {
        margin-bottom: 20px;
        text-align: left;
    }

    .label-text {
        font-size: 14px;
        color: #666;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 15px;
        border: 2px solid transparent;
        background-color: var(--input-bg);
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s ease;
        color: #333;
    }

    /* Input par click kare tyare style */
    input:focus {
        background-color: #fff;
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 10px rgba(78, 84, 200, 0.1);
    }

    button {
        width: 100%;
        padding: 15px;
        border: none;
        color: white;
        font-size: 16px;
        font-weight: 700;
        border-radius: 50px; /* Round button */
        cursor: pointer;
        background: var(--bg-gradient);
        box-shadow: 0 10px 20px rgba(78, 84, 200, 0.3);
        margin-top: 10px;
        transition: all 0.3s ease;
    }

    button:hover {
        transform: scale(1.02); /* Button thodu motu thase */
        box-shadow: 0 15px 25px rgba(78, 84, 200, 0.4);
    }

    .error {
        background: #ffecec;
        color: #e74c3c;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
        border: 1px solid #ffcccc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
</style>

</head>
<body>

<div class="login-container">
    <h2>Welcome Back</h2>

    <?php if ($message): ?>
        <div class="error">
            ⚠️ <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <span class="label-text">Username</span>
            <input type="text" name="username" placeholder="Enter your ID" required>
        </div>

        <div class="input-group">
            <span class="label-text">Password</span>
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>

        <button type="submit">LOGIN</button>
    </form>
</div>

</body>
</html>