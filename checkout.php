<?php
session_start();
include('includes/header.php'); 
include('config/db.php');

/* ================= AUTH & CART CHECK ================= */
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

if (empty($_SESSION['cart'])) {
    echo "<script>window.location.href='products.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

/* ================= GET PAYMENT METHOD FROM CART ================= */
if (isset($_POST['payment_method'])) {
    $payment_method = strtoupper($_POST['payment_method']);
} else {
    $payment_method = 'COD'; // fallback
}

/* Normalize Payment Method Name */
if ($payment_method == 'CARD') { $payment_method = 'DEBIT'; }

/* ================= CALCULATE TOTAL FOR DISPLAY ================= */
// Aa logic add karyu che jethi user ne "Order Summary" ma Total dekhay
$display_total = 0;
$cart_count = 0;
foreach ($_SESSION['cart'] as $pid => $qty) {
    $pid_safe = mysqli_real_escape_string($conn, $pid);
    $res = mysqli_query($conn, "
        SELECT p.selling_price, o.discount_price 
        FROM products p 
        LEFT JOIN offers o ON p.id = o.product_id AND o.active = 1
        WHERE p.id='$pid_safe'
    ");
    if($row = mysqli_fetch_assoc($res)){
        $current_price = !empty($row['discount_price']) ? $row['discount_price'] : $row['selling_price'];
        $display_total += $current_price * $qty;
        $cart_count += $qty;
    }
}

/* ================= PLACE ORDER LOGIC ================= */
if (isset($_POST['place_order_btn'])) {

    $order_unique_id = "ORD" . time() . rand(100,999); // Random number added for extra unique ID
    $total_amount = 0;
    $order_category = "";

    // 1. Calculate Final Total (Security Check)
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $pid_safe = mysqli_real_escape_string($conn, $pid);
        $now = date('Y-m-d H:i:s');
        $query = "
            SELECT p.*, o.discount_percentage, o.discount_price, o.start_date, o.end_date 
            FROM products p 
            LEFT JOIN offers o ON p.id = o.product_id AND o.active = 1
            WHERE p.id='$pid_safe'
        ";
        $result = mysqli_query($conn, $query);
        $p = mysqli_fetch_assoc($result);
        
        if (!$p) continue;

        $current_price = (float)$p['selling_price'];
        
        // Final secure validation: only use discount if not expired
        if (!empty($p['discount_price'])) {
            $is_started = empty($p['start_date']) || $p['start_date'] <= $now;
            $is_not_expired = empty($p['end_date']) || $p['end_date'] >= $now;
            if ($is_started && $is_not_expired) {
                $current_price = (float)$p['discount_price'];
            }
        }

        $total_amount += $current_price * $qty;
        $order_category = $p['category']; 
    }

    $payment_status = "Pending";
    $order_status   = "Pending";

    /* 2. INSERT ORDER */
    
    // Address Validation & Sanitization
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);

    if(empty($address) || empty($city) || empty($pincode)){
        $error_msg = "Please fill all shipping details!";
    } else {

        $order_sql = "
            INSERT INTO orders 
            (order_id, user_id, category, total_amount, payment_method, payment_status, order_status, address, city, pincode)
            VALUES 
            ('$order_unique_id','$user_id','$order_category','$total_amount','$payment_method','$payment_status','$order_status','$address','$city','$pincode')
        ";

    if (mysqli_query($conn, $order_sql)) {

        /* 3. INSERT ORDER ITEMS */
        foreach ($_SESSION['cart'] as $pid => $qty) {
            $pid_safe = mysqli_real_escape_string($conn, $pid);
            $now = date('Y-m-d H:i:s');
            $p = mysqli_fetch_assoc(mysqli_query($conn, "
                SELECT p.*, o.discount_percentage, o.discount_price, o.start_date, o.end_date 
                FROM products p 
                LEFT JOIN offers o ON p.id = o.product_id AND o.active = 1
                WHERE p.id='$pid_safe'
            "));
            
            if (!$p) continue;

            $p_name = mysqli_real_escape_string($conn, $p['name']);
            $p_cat  = mysqli_real_escape_string($conn, $p['category']);
            
            $original_p = (float)$p['selling_price'];
            $discount_pct = 0;
            $final_p = $original_p;

            if (!empty($p['discount_price'])) {
                $is_started = empty($p['start_date']) || $p['start_date'] <= $now;
                $is_not_expired = empty($p['end_date']) || $p['end_date'] >= $now;
                if ($is_started && $is_not_expired) {
                    $discount_pct = (int)$p['discount_percentage'];
                    $final_p = (float)$p['discount_price'];
                }
            }

            mysqli_query($conn, "
                INSERT INTO order_items 
                (order_id, product_id, product_name, category, original_price, discount_percentage, price, quantity)
                VALUES 
                ('$order_unique_id','{$p['id']}','$p_name','$p_cat','$original_p','$discount_pct','$final_p','$qty')
            ");
        }

        /* 4. CLEAR CART & REDIRECT */
        unset($_SESSION['cart']);

        echo "<script>window.location.href='invoice.php?order_id=$order_unique_id';</script>";
        exit();
    } else {
        $error_msg = "Error placing order: " . mysqli_error($conn);
    }
    } // End of Address Validation Else
} // End of POST Check
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Order</title>
    <style>
        /* Modern Checkout CSS */
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .checkout-wrapper {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            color: #1a1a1a;
            font-size: 28px;
            font-weight: 700;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 2fr 1fr; /* Left side motu, Right side nanu */
            gap: 25px;
        }

        /* Card Styling */
        .card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            padding: 25px;
            border: 1px solid #e5e7eb;
        }

        .card-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header h3 {
            margin: 0;
            color: #333;
            font-size: 18px;
            font-weight: 600;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #4b5563;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 15px;
            background-color: #f9fafb;
            color: #374151;
            box-sizing: border-box;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Order Summary Box */
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: #555;
            font-size: 15px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px dashed #e5e7eb;
            font-weight: 700;
            font-size: 18px;
            color: #111;
        }

        .payment-badge {
            display: inline-block;
            background-color: #e0f2fe;
            color: #0369a1;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 5px;
        }

        /* Button */
        .btn-confirm {
            width: 100%;
            padding: 14px;
            background-color: #16a34a; /* Green */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(22, 163, 74, 0.2);
        }

        .btn-confirm:hover {
            background-color: #15803d;
            transform: translateY(-1px);
        }

        /* Error Alert */
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .checkout-grid {
                grid-template-columns: 1fr; /* Stack vertically on mobile */
            }
            .checkout-wrapper {
                margin: 20px auto;
            }
        }
    </style>
</head>
<body>

<div class="checkout-wrapper">
    
    <div class="page-header">
        <h1>Review & Place Order</h1>
    </div>

    <?php if(isset($error_msg)) { ?>
        <div class="alert-error"><?= $error_msg ?></div>
    <?php } ?>

    <div class="checkout-grid">
        
        <div class="card">
            <div class="card-header">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <h3>Billing Information</h3>
            </div>
            
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest User') ?>" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" value="<?= htmlspecialchars($_SESSION['user_email'] ?? 'email@example.com') ?>" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Shipping Address</label>
                    <textarea name="address" class="form-control" rows="3" placeholder="Enter your full address" required></textarea>
                </div>

                <div class="form-group" style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" placeholder="City" required>
                    </div>
                    <div style="flex: 1;">
                        <label class="form-label">Pincode</label>
                        <input type="text" name="pincode" class="form-control" placeholder="Pincode" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Selected Payment Method</label>
                    <div style="font-weight: 600; color: #333; margin-top:5px;">
                        <?= $payment_method ?>
                    </div>
                </div>

                <input type="hidden" name="payment_method" value="<?= htmlspecialchars($payment_method) ?>">

            </div>

        <div class="card" style="height: fit-content;">
            <div class="card-header">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <h3>Order Summary</h3>
            </div>

            <div class="summary-row">
                <span>Total Items</span>
                <span><?= $cart_count ?></span>
            </div>

            <div class="summary-row">
                <span>Payment Mode</span>
                <span class="payment-badge"><?= $payment_method ?></span>
            </div>

            <div class="summary-total">
                <span>Grand Total</span>
                <span style="color: #16a34a;">₹<?= number_format($display_total) ?></span>
            </div>

            <p style="font-size: 12px; color: #888; margin-top: 15px; text-align: center;">
                By clicking Confirm, you agree to our Terms of Service.
            </p>

            <button type="submit" name="place_order_btn" class="btn-confirm">
                Confirm Order
            </button>
            </form> </div>

    </div>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>