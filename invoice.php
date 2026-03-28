<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
// include('config/db.php'); // Un-comment this in your actual code
// Simulation for testing without DB (Remove this block when using real DB)
$conn = true; 
$order_id = $_GET['order_id'] ?? 'ORD-DEMO';
$order = [
    'payment_method' => 'UPI',
    'created_at' => date('Y-m-d H:i:s'),
    'order_status' => 'success',
    'user_name' => 'Anshul Dave',
    'user_email' => 'ahdave1573@gmail.com'
];
// End simulation

/* ================= CHECK ORDER ID ================= */
if (!isset($_GET['order_id'])) {
    // die("Invalid Order ID"); // Uncomment in production
}

// $order_id = mysqli_real_escape_string($conn, $_GET['order_id']);

/* ================= FETCH ORDER ================= */
// $order_q = mysqli_query($conn, "SELECT * FROM orders WHERE order_id='$order_id' LIMIT 1");
// if (!$order_q || mysqli_num_rows($order_q) == 0) {
//     die("Order Not Found");
// }
// $order = mysqli_fetch_assoc($order_q);

/* ================= FETCH ORDER ITEMS ================= */
// $item_q = ... (Your DB Logic)

/* ================= PAYMENT METHOD (DB BASED) ================= */
$method = strtoupper(trim($order['payment_method']));

switch ($method) {
    case 'COD': $payment_display = 'Cash on Delivery (COD)'; break;
    case 'UPI': $payment_display = 'UPI Payment'; break;
    case 'ONLINE': $payment_display = 'Online Payment'; break;
    case 'DEBIT': $payment_display = 'Debit Card Payment'; break;
    default: $payment_display = 'Not Specified';
}

/* ================= USER INFO SAFE ================= */
$user_name  = $order['user_name'] ?? ($_SESSION['user_name'] ?? 'Customer');
$user_email = $order['user_email'] ?? ($_SESSION['user_email'] ?? 'N/A');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?= $order_id ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --bg-color: #f3f4f6;
            --text-main: #1f2937;
            --text-light: #6b7280;
            --white: #ffffff;
        }

        body { 
            font-family: 'Outfit', sans-serif; 
            background: var(--bg-color); 
            padding: 40px 20px; 
            color: var(--text-main);
            margin: 0;
        }

        .invoice-box {
            max-width: 800px; 
            margin: auto; 
            background: var(--white);
            padding: 40px; 
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }

        /* Decorative Top Strip */
        .invoice-box::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), #818cf8);
        }

        /* Header Section */
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 2px dashed #e5e7eb;
            padding-bottom: 30px;
        }

        .header h2 { 
            margin: 0; 
            color: var(--primary); 
            font-weight: 700;
            font-size: 28px;
            letter-spacing: -0.5px;
        }

        .company-info {
            margin-top: 8px;
            color: var(--text-light);
            font-size: 14px;
            line-height: 1.5;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-id {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-main);
        }

        .invoice-date {
            color: var(--text-light);
            font-size: 13px;
            margin-top: 4px;
        }

        /* Badge Styling */
        .badge {
            display: inline-block;
            margin-top: 8px;
            padding: 6px 12px; 
            border-radius: 50px;
            background: #dcfce7; 
            color: #166534;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Pending/Failure Badge colors (optional logic) */
        .badge.pending { background: #fef3c7; color: #92400e; }

        /* Billing Info */
        .billing-section {
            background: #f9fafb;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
        }

        .billing-title {
            font-size: 12px;
            text-transform: uppercase;
            color: var(--text-light);
            font-weight: 700;
            margin-bottom: 8px;
            display: block;
        }

        .billing-details {
            font-size: 15px;
            font-weight: 500;
            line-height: 1.6;
        }

        /* Table Styling */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }

        th { 
            background: #f8fafc; 
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        td { 
            padding: 16px 15px; 
            border-bottom: 1px solid #f1f5f9; 
            font-size: 14px;
            color: var(--text-main);
        }

        .right { text-align: right; }
        .center { text-align: center; }

        /* Total Section */
        .total-row td, .total-row th {
            border-top: 2px solid var(--text-main);
            border-bottom: none;
            padding-top: 20px;
        }
        
        .grand-total-label {
            font-size: 16px;
            font-weight: 700;
            text-align: right;
        }

        .grand-total-amount {
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
        }

        /* Buttons */
        .actions {
            margin-top: 40px;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn { 
            padding: 12px 24px; 
            border-radius: 8px; 
            text-decoration: none; 
            font-weight: 500; 
            font-size: 14px;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }

        .btn-print { 
            background: var(--text-main); 
            color: var(--white); 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-print:hover { background: #000; transform: translateY(-1px); }

        .btn-back { 
            background: white; 
            color: var(--primary); 
            border: 1px solid var(--primary); 
        }
        .btn-back:hover { background: #eff6ff; }

        /* Print Specifics */
        @media print { 
            body { background: white; padding: 0; }
            .invoice-box { box-shadow: none; border: none; padding: 0; max-width: 100%; }
            .no-print { display: none; } 
            .btn { display: none; }
            .badge { border: 1px solid #000; color: #000; background: none !important; }
            /* Force background graphics */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>
<body>

<div class="invoice-box">

    <div class="header">
        <div>
            <h2>DigitalBazaar</h2>
            <div class="company-info">
                123 Tech Park, Gujarat, India<br>
                support@digitalbazaar.com
            </div>
        </div>
        <div class="invoice-meta">
            <div class="invoice-id">Invoice #<?= $order_id ?></div>
            <div class="invoice-date"><?= date('d M, Y', strtotime($order['created_at'])) ?></div>
            <span class="badge"><?= strtoupper($order['order_status']) ?></span>
        </div>
    </div>

    <div class="billing-section">
        <span class="billing-title">Billed To</span>
        <div class="billing-details">
            <?= htmlspecialchars($user_name) ?><br>
            <?= htmlspecialchars($user_email) ?><br>
            <span style="color:var(--text-light); font-size:13px; margin-top:5px; display:block;">
                Method: <?= $payment_display ?>
            </span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">Id</th>
                <th style="width: 45%">Product</th>
                <th style="width: 20%">Category</th>
                <th class="right">Price</th>
                <th class="center">Qty</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            $grand = 0;

            // FAKE DATA FOR DISPLAY (Remove this in your real code and uncomment DB loop below)
            // Start Fake Data
            $fake_items = [
                ['product_name' => 'JBL Tune 770NC Wireless', 'category_name' => 'Accessories', 'price' => 5400, 'quantity' => 1]
            ];
            foreach ($fake_items as $row) {
                 $total = $row['price'] * $row['quantity'];
                 $grand += $total;
            // End Fake Data

            // UNCOMMENT THIS IN REAL CODE:
            /*
            if ($item_q && mysqli_num_rows($item_q) > 0) {
                while ($row = mysqli_fetch_assoc($item_q)) {
                    $total = $row['price'] * $row['quantity'];
                    $grand += $total;
            */
            ?>
            <tr>
                <td style="color: #9ca3af;"><?= str_pad($i++, 2, '0', STR_PAD_LEFT) ?></td>
                <td style="font-weight: 500;"><?= htmlspecialchars($row['product_name']) ?></td>
                <td style="color: #6b7280; font-size: 13px;"><?= htmlspecialchars($row['category_name']) ?></td>
                <td class="right">₹<?= number_format($row['price']) ?></td>
                <td class="center"><?= $row['quantity'] ?></td>
                <td class="right" style="font-weight: 600;">₹<?= number_format($total) ?></td>
            </tr>
            <?php 
                } 
            // } // Close DB if
            ?>

            <tr class="total-row">
                <td colspan="4"></td>
                <td class="grand-total-label">TOTAL</td>
                <td class="right grand-total-amount">₹<?= number_format($grand) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="actions no-print">
        <button class="btn btn-print" onclick="window.print()">Print Invoice</button>
        <a href="my_orders.php" class="btn btn-back">Back to Orders</a>
    </div>

</div>

</body>
</html>