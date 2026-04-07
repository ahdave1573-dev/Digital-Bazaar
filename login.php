<?php
session_start();
include('db.php');

// 1. Agar User pehle se Login hai, to Home par bhejo
if(isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

$msg = "";

if(isset($_POST['login_btn'])){
    
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // 2. Email Check Karna
    $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $query_run = mysqli_query($conn, $query);

    if(mysqli_num_rows($query_run) > 0){
        $data = mysqli_fetch_assoc($query_run);

        // 3. Password Verify Karna (Hash vs Plain)
        if(password_verify($password, $data['password'])){
            
            // Session Variables Set Karna
            $_SESSION['auth'] = true; // Login status
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['user_name'] = $data['name'];
            $_SESSION['user_email'] = $data['email'];
            $_SESSION['role_as'] = $data['role_as']; // 0 = User, 1 = Admin

            $_SESSION['message'] = "Logged in Successfully";
            
            // Redirect based on Role (Optional)
            if($data['role_as'] == 1){
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();

        } else {
            $msg = "Incorrect Password!";
        }
    } else {
        $msg = "Email is not registered!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | DigitalBazaar</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            /* Background Gradient */
            background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Main Container - Split Layout */
        .container {
            display: flex;
            background: #fff;
            width: 900px;
            height: 550px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        /* ::::: LEFT SIDE (Welcome Panel) ::::: */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
            position: relative;
        }

        .left-panel h2 { font-size: 2.5rem; font-weight: 700; margin-bottom: 15px; }
        .left-panel p { font-size: 1rem; line-height: 1.6; opacity: 0.9; max-width: 320px; }
        .brand-name span { color: #06b6d4; }

        /* ::::: RIGHT SIDE (Login Form) ::::: */
        .right-panel {
            flex: 1;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 50px;
        }

        .login-header { margin-bottom: 30px; text-align: left; }
        .login-header h3 { font-size: 1.8rem; color: #1e293b; font-weight: 700; margin-bottom: 5px; }
        .login-header p { color: #64748b; font-size: 0.9rem; }

        /* Input Fields */
        .form-group { margin-bottom: 20px; position: relative; }
        
        .form-group i {
            position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
            color: #94a3b8;
        }

        input {
            width: 100%;
            padding: 12px 15px 12px 45px; /* Space for Icon */
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            color: #334155;
            outline: none;
            transition: 0.3s;
            background: #f8fafc;
        }

        input:focus {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Button */
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
        }
        .btn-login:hover { background: #1d4ed8; transform: translateY(-2px); }

        /* Error Box */
        .error-msg {
            background: #fef2f2; color: #ef4444;
            padding: 10px; border-radius: 6px;
            font-size: 0.9rem; margin-bottom: 20px;
            border: 1px solid #fecaca; display: flex; align-items: center; gap: 8px;
        }

        /* Links */
        .footer-links { margin-top: 20px; text-align: center; font-size: 0.9rem; color: #64748b; }
        .footer-links a { color: #2563eb; text-decoration: none; font-weight: 600; }
        .footer-links a:hover { text-decoration: underline; }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .container { flex-direction: column; height: auto; width: 90%; }
            .left-panel { padding: 30px; display: none; /* Mobile pe sirf form dikhaye */ }
            .right-panel { padding: 30px; }
        }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="left-panel">
            <h2 class="brand-name">Digital<span>Bazaar</span></h2>
            <p>Welcome back! Log in to access your orders, wishlist, and exclusive deals.</p>
        </div>

        <div class="right-panel">
            
            <div class="login-header">
                <h3>Sign In</h3>
                <p>Enter your credentials to continue</p>
            </div>

            <?php if($msg != ""): ?>
                <div class="error-msg">
                    <i class="fas fa-exclamation-circle"></i> <?= $msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                
                <div class="form-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email Address" required autocomplete="off">
                </div>

                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" name="login_btn" class="btn-login">Login Securely</button>

            </form>

            <div class="footer-links">
                <p>Don't have an account? <a href="register.php">Register Here</a></p>
                <br>
                <a href="index.php" style="color: #64748b; font-size: 0.85rem;">← Back to Home</a>
            </div>

        </div>

    </div>

</body>
</html>