<?php
include("../config/auth.php");
include("../config/db.php");

$errors = [];
$success = false;

// If product_id provided, load product to prefill
$prefill = ['id'=>0, 'title'=>'','image'=>'','price'=>0];
if (!empty($_GET['product_id'])) {
    $pid = (int)$_GET['product_id'];
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
    <title>Add Offer - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Poppins',sans-serif;background:#f8fafc;padding:30px}
        .card{max-width:700px;margin:0 auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 6px 18px rgba(0,0,0,0.06)}
        input,textarea{width:100%;padding:10px;margin:6px 0;border:1px solid #e6e9ee;border-radius:6px}
        label{font-weight:600}
        .btn{background:#4f46e5;color:#fff;padding:10px 16px;border-radius:8px;border:none;cursor:pointer}
        .errors{color:#ef4444;margin-bottom:10px}
        .success{color:#16a34a;margin-bottom:10px}
    </style>
</head>
<body>

<div class="card">
    <h2>Add Offer</h2>

    <?php if ($success): ?>
        <div class="success">Offer added successfully. <a href="dashboard.php">Go back</a></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="errors"><?php echo implode('<br>', $errors); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= isset($_POST['product_id']) ? (int)$_POST['product_id'] : (int)$prefill['id'] ?>">
        <div>
            <label>Title</label>
            <input type="text" name="title" required value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : htmlspecialchars(
                (isset(
                    $prefill['title']) && !isset($_POST['title'])) ? $prefill['title'] : (isset(
                    
                    $_POST['title']) ? $_POST['title'] : '') ) ?>">
        </div>
        <div>
            <label>Original Price (₹)</label>
            <input type="number" id="base_price" name="base_price" <?= !empty($_GET['product_id']) ? 'readonly' : '' ?> value="<?= isset($_POST['base_price']) ? (float)$_POST['base_price'] : (float)$prefill['price'] ?>" oninput="calculateDiscount()">
        </div>
        <div>
            <label>Discount Percentage (%)</label>
            <input type="number" id="discount_percentage" name="discount_percentage" min="0" max="100" value="<?= isset($_POST['discount_percentage']) ? (int)$_POST['discount_percentage'] : 0 ?>" oninput="calculateDiscount()">
        </div>
        <div>
            <label>Start Date</label>
            <input type="datetime-local" name="start_date" value="<?= isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date']) : '' ?>">
        </div>
        <div>
            <label>End Date</label>
            <input type="datetime-local" name="end_date" value="<?= isset($_POST['end_date']) ? htmlspecialchars($_POST['end_date']) : '' ?>">
        </div>
        <div>
            <label>Discount Price (₹)</label>
            <input type="number" step="0.01" id="discount_price" name="discount_price" min="0" value="<?= isset($_POST['discount_price']) ? (float)$_POST['discount_price'] : 0.00 ?>">
        </div>
        <div>
            <label>Image (optional)</label>
            <input type="file" name="image" accept="image/*">
            <?php if (!empty($prefill['image'])): ?>
                <div style="margin-top:8px">
                    <strong>Product image available:</strong><br>
                    <?php $pp = '../assets/images/' . htmlspecialchars($prefill['image']); if(file_exists(__DIR__ . '/../assets/images/' . $prefill['image'])): ?>
                        <img src="<?= $pp ?>" style="width:120px;height:80px;object-fit:cover;border-radius:6px;margin-top:6px">
                        <input type="hidden" name="product_image" value="<?= htmlspecialchars($prefill['image']) ?>">
                    <?php else: ?>
                        <div style="color:#6b7280;margin-top:6px">Product image not found on disk.</div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div style="margin-top:10px">
            <button class="btn" type="submit">Create Offer</button>
            <a href="dashboard.php" style="margin-left:10px;color:#6b7280">Cancel</a>
        </div>
    </form>
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
