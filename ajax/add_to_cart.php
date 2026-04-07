<?php
session_start();
header('Content-Type: application/json');
include(__DIR__ . '/../db.php');

$response = ['success' => false, 'message' => 'Something went wrong'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $user_id = $_SESSION['user_id'] ?? 0;
    $now = date('Y-m-d H:i:s');

    // 1. Fetch product and its active offer securely
    $sql = "SELECT p.*, o.discount_percentage, o.discount_price, o.start_date, o.end_date 
            FROM products p 
            LEFT JOIN offers o ON p.id = o.product_id AND o.active = 1
            WHERE p.id = $product_id LIMIT 1";
    
    $res = mysqli_query($conn, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $product = mysqli_fetch_assoc($res);
        
        $original_price = (float)$product['selling_price'];
        $discount_pct = 0;
        $final_price = $original_price;

        // 2. Validate Offer (Check if active and not expired)
        if (!empty($product['discount_price'])) {
            $is_started = empty($product['start_date']) || $product['start_date'] <= $now;
            $is_not_expired = empty($product['end_date']) || $product['end_date'] >= $now;

            if ($is_started && $is_not_expired) {
                $discount_pct = (int)$product['discount_percentage'];
                $final_price = (float)$product['discount_price'];
            }
        }

        // 3. Update Session Cart
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }

        // 4. Persistent DB Cart (If logged in)
        if ($user_id > 0) {
            // Check if product already in DB cart
            $check_cart = mysqli_query($conn, "SELECT id FROM cart WHERE user_id = $user_id AND product_id = $product_id");
            if (mysqli_num_rows($check_cart) > 0) {
                mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1, discounted_price = $final_price WHERE user_id = $user_id AND product_id = $product_id");
            } else {
                mysqli_query($conn, "INSERT INTO cart (user_id, product_id, original_price, discount_percentage, discounted_price, quantity) 
                                    VALUES ($user_id, $product_id, $original_price, $discount_pct, $final_price, 1)");
            }
        }

        $response['success'] = true;
        $response['message'] = 'Product added to cart successfully!';
        $response['cart_count'] = array_sum($_SESSION['cart']);
    } else {
        $response['message'] = 'Product not found.';
    }
} else {
    $response['message'] = 'Invalid request.';
}

echo json_encode($response);
?>
