<?php
session_start();
include('db.php');

// 1. If already logged in, redirect to home
if(isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit();
}

$msg = "";

if(isset($_POST['register_btn'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

    // 2. Check if Email Already Exists
    $check_email = "SELECT email FROM users WHERE email='$email'";
    $check_email_run = mysqli_query($conn, $check_email);

    if(mysqli_num_rows($check_email_run) > 0){
        $msg = "Email Already Registered!";
    }
    else{
        // 3. Confirm Password Check
        if($password == $cpassword){
            
            // 4. Password Hashing (Security)
            $hash_password = password_hash($password, PASSWORD_DEFAULT);

            // 5. Insert User Data (Default Role = 0 for User)
            $query = "INSERT INTO users (name,email,phone,password,role_as) VALUES ('$name','$email','$phone','$hash_password','0')";
            $query_run = mysqli_query($conn, $query);

            if($query_run){
                $_SESSION['message'] = "Registered Successfully! Please Login.";
                header("Location: login.php");
                exit();
            }else{
                $msg = "Something went wrong!";
            }

        }else{
            $msg = "Passwords do not match!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | DigitalBazaar</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            display: flex;
            background: #fff;
            width: 900px;
            max-width: 100%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        /* ::::: LEFT PANEL (Branding) ::::: */
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
        }
        .left-panel h2 { font-size: 2.5rem; font-weight: 700; margin-bottom: 15px; }
        .left-panel p { font-size: 1rem; line-height: 1.6; opacity: 0.9; max-width: 320px; }
        .brand-name span { color: #06b6d4; }

        /* ::::: RIGHT PANEL (Form) ::::: */
        .right-panel {
            flex: 1;
            background: white;
            padding: 40px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header { margin-bottom: 20px; text-align: left; }
        .form-header h3 { font-size: 1.8rem; color: #1e293b; font-weight: 700; }
        .form-header p { color: #64748b; font-size: 0.9rem; }

        .form-group { margin-bottom: 15px; position: relative; }
        
        .form-group i {
            position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; font-size: 0.9rem;
        }

        input {
            width: 100%;
            padding: 12px 15px 12px 40px;
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

        .btn-register {
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
        .btn-register:hover { background: #1d4ed8; transform: translateY(-2px); }

        .error-msg {
            background: #fef2f2; color: #ef4444;
            padding: 10px; border-radius: 6px;
            font-size: 0.85rem; margin-bottom: 15px;
            border: 1px solid #fecaca; text-align: center;
        }

        .footer-links { margin-top: 20px; text-align: center; font-size: 0.9rem; color: #64748b; }
        .footer-links a { color: #2563eb; text-decoration: none; font-weight: 600; }
        .footer-links a:hover { text-decoration: underline; }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .container { flex-direction: column; width: 90%; }
            .left-panel { display: none; }
            .right-panel { padding: 30px; }
        }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="left-panel">
            <h2 class="brand-name">Digital<span>Bazaar</span></h2>
            <p>Join us today! Create an account to start shopping for the best electronics.</p>
        </div>

        <div class="right-panel">
            
            <div class="form-header">
                <h3>Create Account</h3>
                <p>Please fill in the details to sign up</p>
            </div>

            <?php if($msg != ""): ?>
                <div class="error-msg">
                    <i class="fas fa-exclamation-circle"></i> <?= $msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                
                <div class="form-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" placeholder="Full Name" required>
                </div>

                <div class="form-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email Address" required>
                </div>

                <div class="form-group">
                    <i class="fas fa-phone"></i>
                    <input type="number" name="phone" placeholder="Phone Number" required>
                </div>

                <div class="form-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="form-group">
                    <i class="fas fa-key"></i>
                    <input type="password" name="cpassword" placeholder="Confirm Password" required>
                </div>

                <button type="submit" name="register_btn" class="btn-register">Register Now</button>

            </form>

            <div class="footer-links">
                <p>Already have an account? <a href="login.php">Login Here</a></p>
            </div>

        </div>

    </div>

</body>
</html>