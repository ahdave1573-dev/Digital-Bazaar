<?php
include("../auth.php");
include("../db.php");

$errors = [];
$success = false;

// If product_id or id provided, load product to prefill
$prefill = ['id'=>0, 'title'=>'','image'=>'','price'=>0];
$pid = 0;
if (!empty($_GET['product_id'])) {
    $pid = (int)$_GET['product_id'];
} elseif (!empty($_GET['id'])) {
    $pid = (int)$_GET['id'];
}

if ($pid > 0) {
    $pRes = mysqli_query($conn, "SELECT * FROM products WHERE id = $pid LIMIT 1");
    if ($pRes && mysqli_num_rows($pRes) > 0) {
        $p = mysqli_fetch_assoc($pRes);
        $prefill['id'] = $p['id'];
        $prefill['title'] = $p['name'] ?? '';
        $prefill['image'] = $p['image'] ?? '';
        $prefill['price'] = $p['selling_price'] ?? 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $title = trim($_POST['title'] ?? '');
    $discount = isset($_POST['discount_percentage']) ? (int)$_POST['discount_percentage'] : 0;
    $original_price = isset($_POST['base_price']) ? (float)$_POST['base_price'] : 0.0;
    $discount_price = isset($_POST['discount_price']) ? (float)$_POST['discount_price'] : 0.0;
    $start_date = trim($_POST['start_date'] ?? '');
    $end_date = trim($_POST['end_date'] ?? '');

    if ($title === '') {
        $errors[] = 'Title is required.';
    }

    // Ensure upload dir exists
    $uploadDir = __DIR__ . '/../assets/images/offers';
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0755, true);
    }

    $imageBasename = '';
    if (!empty($_FILES['image']['name'])) {
        $tmp = $_FILES['image']['tmp_name'];
        $origName = basename($_FILES['image']['name']);
        $ext = pathinfo($origName, PATHINFO_EXTENSION);
        $imageBasename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $targetPath = $uploadDir . '/' . $imageBasename;

        if (!@move_uploaded_file($tmp, $targetPath)) {
            $errors[] = 'Failed to upload image.';
            $imageBasename = '';
        }
    }

    // If no file uploaded but came from a product, copy product image into offers folder
    if (empty($imageBasename) && !empty($_POST['product_image']) && empty($errors)) {
        $productImg = basename($_POST['product_image']);
        $productPath = __DIR__ . '/../assets/images/' . $productImg;
        if (file_exists($productPath)) {
            $ext = pathinfo($productImg, PATHINFO_EXTENSION);
            $imageBasename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $targetPath = $uploadDir . '/' . $imageBasename;
            @copy($productPath, $targetPath);
            if (!file_exists($targetPath)) {
                $errors[] = 'Failed to copy product image.';
                $imageBasename = '';
            }
        }
    }

    if (empty($errors)) {
        $pid_val = (int)$product_id;
        $t = $title;
        $img = $imageBasename;
        $disc = (int)$discount;
        $op = (float)$original_price;
        $dp = (float)$discount_price;

        // Prepare start and end dates; if empty, set sensible defaults
        if ($start_date === '') {
            $start_date = date('Y-m-d H:i:s');
        } else {
            // convert from html5 datetime-local to SQL datetime if needed
            $sd = str_replace('T', ' ', $start_date);
            $start_date = date('Y-m-d H:i:s', strtotime($sd));
        }

        if ($end_date === '') {
            $end_date = date('Y-m-d H:i:s', strtotime('+7 days'));
        } else {
            $ed = str_replace('T', ' ', $end_date);
            $end_date = date('Y-m-d H:i:s', strtotime($ed));
        }

        $s = mysqli_real_escape_string($conn, $start_date);
        $e = mysqli_real_escape_string($conn, $end_date);

        // Build INSERT dynamically based on actual offers table columns
        $colsRes = mysqli_query($conn, "SHOW COLUMNS FROM offers");
        $tableCols = [];
        if ($colsRes) {
            while ($c = mysqli_fetch_assoc($colsRes)) {
                $tableCols[] = $c['Field'];
            }
        }

        // map logical to actual
        $map = [];
        $map['product_id'] = in_array('product_id', $tableCols) ? 'product_id' : null;
        $map['title'] = in_array('title', $tableCols) ? 'title' : (in_array('name', $tableCols) ? 'name' : null);
        $map['discount'] = in_array('discount_percentage', $tableCols) ? 'discount_percentage' : (in_array('discount', $tableCols) ? 'discount' : null);
        $map['original_price'] = in_array('original_price', $tableCols) ? 'original_price' : null;
        $map['discount_price'] = in_array('discount_price', $tableCols) ? 'discount_price' : null;
        $map['start_date'] = in_array('start_date', $tableCols) ? 'start_date' : null;
        $map['end_date'] = in_array('end_date', $tableCols) ? 'end_date' : null;
        $map['image'] = in_array('image', $tableCols) ? 'image' : null;
        $map['active'] = in_array('active', $tableCols) ? 'active' : null;

        $insertCols = [];
        $placeholders = [];
        $values = [];
        $types = '';
        
        if ($map['product_id'] && $pid_val > 0) { $insertCols[] = $map['product_id']; $placeholders[] = '?'; $values[] = $pid_val; $types .= 'i'; }
        if ($map['title']) { $insertCols[] = $map['title']; $placeholders[] = '?'; $values[] = $t; $types .= 's'; }
        if ($map['discount']) { $insertCols[] = $map['discount']; $placeholders[] = '?'; $values[] = $disc; $types .= 'i'; }
        if ($map['original_price']) { $insertCols[] = $map['original_price']; $placeholders[] = '?'; $values[] = $op; $types .= 'd'; }
        if ($map['discount_price']) { $insertCols[] = $map['discount_price']; $placeholders[] = '?'; $values[] = $dp; $types .= 'd'; }
        if ($map['start_date']) { $insertCols[] = $map['start_date']; $placeholders[] = '?'; $values[] = $s; $types .= 's'; }
        if ($map['end_date']) { $insertCols[] = $map['end_date']; $placeholders[] = '?'; $values[] = $e; $types .= 's'; }
        if ($map['image']) { $insertCols[] = $map['image']; $placeholders[] = '?'; $values[] = $img; $types .= 's'; }
        if ($map['active']) { $insertCols[] = $map['active']; $placeholders[] = '?'; $values[] = 1; $types .= 'i'; }

        if (count($insertCols) === 0) {
            $errors[] = 'Offers table has no compatible columns: ' . implode(', ', $tableCols);
        } else {
            $colList = implode(', ', $insertCols);
            $phList = implode(', ', $placeholders);
            $sql = "INSERT INTO offers ($colList) VALUES ($phList)";

            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt === false) {
                $errors[] = 'Database prepare error: ' . mysqli_error($conn) . ' -- SQL: ' . $sql;
            } else {
                // bind params dynamically
                $bind_params = [];
                $bind_params[] = $types;
                for ($i = 0; $i < count($values); $i++) {
                    $bind_params[] = &$values[$i];
                }
                if (!call_user_func_array(array($stmt, 'bind_param'), $bind_params)) {
                    $errors[] = 'Failed to bind parameters';
                }

                if (mysqli_stmt_execute($stmt)) {
                    $success = true;
                } else {
                    $errors[] = 'Database execute error: ' . mysqli_stmt_error($stmt) . ' -- SQL: ' . $sql;
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Offer - Admin | DigitalBazaar</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #eef2ff;
            --secondary: #10b981;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --bg-body: #f8fafc;
            --card-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 40px;
            color: white;
            text-align: center;
            position: relative;
        }

        .header h2 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .header p {
            font-size: 0.95rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .header .back-link {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            transition: var(--transition);
        }

        .header .back-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-50%) translateX(-4px);
        }

        .form-content {
            padding: 40px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-dark);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            transition: var(--transition);
        }

        input, textarea, select {
            width: 100%;
            padding: 12px 16px 12px 42px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-dark);
            background: #ffffff;
            transition: var(--transition);
            outline: none;
        }

        input::placeholder { color: #94a3b8; }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-light);
        }

        input:focus + i {
            color: var(--primary);
        }

        input[readonly] {
            background-color: #f1f5f9;
            cursor: not-allowed;
            border-color: #e2e8f0;
        }

        .product-preview {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: var(--primary-light);
            border-radius: 16px;
            margin-top: 10px;
            border: 1px dashed var(--primary);
        }

        .product-preview img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 10px;
            background: white;
            padding: 8px;
        }

        .product-preview .info {
            flex: 1;
        }

        .product-preview .info h4 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 4px;
        }

        .product-preview .info p {
            font-size: 0.8rem;
            color: var(--primary);
            font-weight: 600;
        }

        .actions {
            margin-top: 32px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 16px;
        }

        .btn {
            padding: 14px 32px;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.4);
        }

        .btn-cancel {
            background: transparent;
            color: var(--text-muted);
            text-decoration: none;
        }

        .btn-cancel:hover {
            color: var(--text-dark);
            background: #f1f5f9;
        }

        .state-msg {
            padding: 16px 20px;
            border-radius: 14px;
            margin-bottom: 24px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }

        /* CUSTOM FILE UPLOAD */
        .file-input-group {
            position: relative;
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .file-input-group:hover {
            background: var(--primary-light);
            border-color: var(--primary);
        }

        .file-input-group input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }

        .file-input-group i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .file-input-group p {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        @media (max-width: 640px) {
            .form-grid { grid-template-columns: 1fr; }
            .header { padding: 30px 20px; }
            .form-content { padding: 30px 20px; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <a href="dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h2>Create Offer</h2>
        <p>Set up a new discount for your premium collections</p>
    </div>

    <div class="form-content">
        <?php if ($success): ?>
            <div class="state-msg success">
                <i class="fas fa-check-circle"></i>
                <span>Offer created successfully! <a href="dashboard.php" style="color: inherit; text-decoration: underline;">View in Dashboard</a></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="state-msg error">
                <i class="fas fa-exclamation-triangle"></i>
                <span><?php echo implode('<br>', $errors); ?></span>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?= isset($_POST['product_id']) ? (int)$_POST['product_id'] : (int)$prefill['id'] ?>">
            
            <div class="form-grid">
                <!-- Title -->
                <div class="form-group full-width">
                    <label>Offer Title</label>
                    <div class="input-wrapper">
                        <i class="fas fa-tag"></i>
                        <input type="text" name="title" placeholder="e.g. Summer Clearance Sale" required 
                               value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : htmlspecialchars($prefill['title']) ?>">
                    </div>
                </div>

                <!-- Original Price -->
                <div class="form-group">
                    <label>Original Price (₹)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-indian-rupee-sign"></i>
                        <input type="number" id="base_price" name="base_price" placeholder="0.00"
                               <?= !empty($_GET['product_id']) || !empty($_GET['id']) ? 'readonly' : '' ?> 
                               value="<?= isset($_POST['base_price']) ? (float)$_POST['base_price'] : (float)$prefill['price'] ?>" 
                               oninput="calculateDiscount()">
                    </div>
                </div>

                <!-- Discount Percentage -->
                <div class="form-group">
                    <label>Discount (%)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-percent"></i>
                        <input type="number" id="discount_percentage" name="discount_percentage" min="0" max="100" placeholder="20"
                               value="<?= isset($_POST['discount_percentage']) ? (int)$_POST['discount_percentage'] : 0 ?>" 
                               oninput="calculateDiscount()">
                    </div>
                </div>

                <!-- Start Date -->
                <div class="form-group">
                    <label>Start Date</label>
                    <div class="input-wrapper">
                        <i class="fas fa-calendar-plus"></i>
                        <input type="datetime-local" name="start_date" 
                               value="<?= isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date']) : '' ?>">
                    </div>
                </div>

                <!-- End Date -->
                <div class="form-group">
                    <label>End Date</label>
                    <div class="input-wrapper">
                        <i class="fas fa-calendar-check"></i>
                        <input type="datetime-local" name="end_date" 
                               value="<?= isset($_POST['end_date']) ? htmlspecialchars($_POST['end_date']) : '' ?>">
                    </div>
                </div>

                <!-- Discount Price -->
                <div class="form-group full-width">
                    <label>Final Discounted Price (₹)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-hand-holding-dollar"></i>
                        <input type="number" step="0.01" id="discount_price" name="discount_price" min="0" placeholder="0.00"
                               value="<?= isset($_POST['discount_price']) ? (float)$_POST['discount_price'] : 0.00 ?>">
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="form-group full-width">
                    <label>Offer Banner / Image</label>
                    <div class="file-input-group">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click or drag image here to upload</p>
                        <input type="file" name="image" accept="image/*">
                    </div>

                    <?php if (!empty($prefill['image'])): ?>
                        <div class="product-preview">
                            <?php 
                            $pp = '../assets/images/' . htmlspecialchars($prefill['image']); 
                            if(file_exists(__DIR__ . '/../assets/images/' . $prefill['image'])): 
                            ?>
                                <img src="<?= $pp ?>" alt="Product">
                                <div class="info">
                                    <h4>Using Product Image</h4>
                                    <p>The original product image will be used if no new banner is uploaded.</p>
                                </div>
                                <input type="hidden" name="product_image" value="<?= htmlspecialchars($prefill['image']) ?>">
                            <?php else: ?>
                                <div class="info" style="color:#ef4444">
                                    <i class="fas fa-unlink"></i> Product image not found on disk.
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="actions">
                <a href="dashboard.php" class="btn btn-cancel">Discard</a>
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-plus"></i> Create Now
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function calculateDiscount() {
    const basePrice = parseFloat(document.getElementById('base_price').value) || 0;
    const percentage = parseFloat(document.getElementById('discount_percentage').value) || 0;
    
    if (basePrice > 0) {
        const discounted = basePrice - (basePrice * (percentage / 100));
        document.getElementById('discount_price').value = discounted.toFixed(2);
    }
}
</script>

</body>
</html>
