<?php
session_start();
include('db.php');

$success = "";
$error = "";

/* ===== FORM SUBMIT ===== */
if(isset($_POST['send_message'])){

    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if($name=="" || $email=="" || $message==""){
        $error = "All fields are required!";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Invalid email address!";
    }
    else{
        $insert = mysqli_query($conn,
            "INSERT INTO contact_messages (name,email,message)
             VALUES ('$name','$email','$message')"
        );

        if($insert){
            $success = "Message sent successfully!";
        }else{
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<?php include('includes/header.php'); ?>

<style>
.contact-wrapper{
    max-width:1200px;
    margin:60px auto;
    padding:0 20px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:40px;
}
.contact-box{
    background:#ffffff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
}
.contact-box h3{
    margin-bottom:20px;
    color:#0f172a;
}
.contact-info p{
    margin-bottom:15px;
    color:#475569;
    font-size:14px;
}
.contact-info i{
    color:#2563eb;
    margin-right:10px;
}
.form-group{
    margin-bottom:15px;
}
.form-group input,
.form-group textarea{
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #e2e8f0;
    outline:none;
}
.form-group textarea{
    resize:none;
    height:120px;
}
.btn-send{
    background:#2563eb;
    color:#fff;
    border:none;
    padding:12px 20px;
    border-radius:8px;
    cursor:pointer;
}
.btn-send:hover{
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
@media(max-width:768px){
    .contact-wrapper{
        grid-template-columns:1fr;
    }
}
</style>

<div class="contact-wrapper">

    <!-- LEFT INFO -->
    <div class="contact-box contact-info">
        <h3>Get in Touch</h3>

        <p><i class="fas fa-map-marker-alt"></i> DigitalBazaar Electronics, Rajkot, Gujarat, India</p>
        <p><i class="fas fa-phone"></i> +91 88499 19418</p>
        <p><i class="fas fa-envelope"></i> support@digitalbazaar.com</p>
        <p><i class="fas fa-clock"></i> Mon – Sat : 9:00 AM – 7:00 PM</p>
    </div>

    <!-- RIGHT FORM -->
    <div class="contact-box">
        <h3>Send us a message</h3>

        <?php if($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>

        <?php if($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <input type="text" name="name" placeholder="Your Name" required>
            </div>

            <div class="form-group">
                <input type="email" name="email" placeholder="Your Email" required>
            </div>

            <div class="form-group">
                <textarea name="message" placeholder="Your Message" required></textarea>
            </div>

            <button type="submit" name="send_message" class="btn-send">
                Send Message
            </button>
        </form>
    </div>

</div>

<?php include('includes/footer.php'); ?>
