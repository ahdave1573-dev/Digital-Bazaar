<?php
session_start();
include("config/db.php");
include("includes/header.php");

// 1. Check if ID exists in URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>window.location.href='offers.php';</script>";
    exit;
}

$offer_id = mysqli_real_escape_string($conn, $_GET['id']);

// 2. Fetch Offer Details from Database
$query = "SELECT * FROM offers WHERE id = '$offer_id'";
$result = mysqli_query($conn, $query);

// Check if offer exists
if (mysqli_num_rows($result) == 0) {
    echo "<div style='padding:50px; text-align:center;'><h2>Offer Not Found!</h2><a href='offers.php'>Go Back</a></div>";
    include("includes/footer.php");
    exit;
}

$offer = mysqli_fetch_assoc($result);

// Prepare Data
$title = $offer['title'] ?? 'Offer Details';
$desc = $offer['description'] ?? 'No description available.';
$imageVal = $offer['image'] ?? '';
$imgFile = !empty($imageVal) ? 'assets/images/offers/' . basename($imageVal) : 'assets/images/no-image.png';
$product_id = $offer['product_id'] ?? 0;

?>

<style>
    :root {
        --primary-blue: #2b63e0;
        --dark-blue: #1a4db3;
        --text-black: #1a1a1a;
        --text-grey: #64748b;
        --bg-light: #f3f8ff;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }

    .detail-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 20px;
        color: var(--text-grey);
        text-decoration: none;
        font-weight: 600;
    }
    .back-link:hover { color: var(--primary-blue); }

    .detail-card {
        display: flex;
        flex-direction: column;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    @media (min-width: 768px) {
        .detail-card { flex-direction: row; }
    }

    /* Left Side: Image */
    .detail-image-box {
        flex: 1;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        position: relative;
    }

    .detail-img {
        max-width: 100%;
        max-height: 400px;
        object-fit: contain;
    }

    .badge-overlay {
        position: absolute;
        top: 20px; left: 20px;
        background: var(--primary-blue);
        color: white;
        padding: 8px 15px;
        border-radius: 8px;
        font-weight: 700;
    }

    /* Right Side: Content */
    .detail-content {
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
    }

    .detail-title {
        font-size: 2rem;
        font-weight: 800;
        margin: 0 0 15px 0;
        color: var(--text-black);
    }

    .price-box {
        display: flex;
        align-items: baseline;
        gap: 15px;
        margin-bottom: 25px;
        background: #f0f7ff;
        padding: 15px;
        border-radius: 10px;
        width: fit-content;
    }

    .p-original { text-decoration: line-through; color: #94a3b8; font-size: 1.2rem; }
    .p-discount { color: var(--primary-blue); font-size: 2rem; font-weight: 800; }

    .detail-desc {
        color: var(--text-grey);
        line-height: 1.6;
        font-size: 1rem;
        margin-bottom: 30px;
    }

    .meta-info {
        margin-bottom: 30px;
        border-top: 1px solid #e2e8f0;
        padding-top: 20px;
    }

    .meta-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }

    .add-cart-btn {
        margin-top: auto;
        background: var(--primary-blue);
        color: white;
        border: none;
        padding: 15px;
        width: 100%;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .add-cart-btn:hover {
        background: var(--dark-blue);
        transform: translateY(-2px);
    }

    /* Toast Notification */
    .toast { position: fixed; bottom: 20px; right: 20px; background: #10b981; color: white; padding: 12px 25px; border-radius: 8px; display: none; z-index: 1000; animation: slideUp 0.3s ease; }
    @keyframes slideUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>

<div class="detail-container">
    <a href="offers.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Offers</a>

    <div class="detail-card">
        <div class="detail-image-box">
            <div class="badge-overlay"><?= $offer['discount_percentage'] ?>% OFF</div>
            <img src="<?= htmlspecialchars($imgFile) ?>" alt="<?= htmlspecialchars($title) ?>" class="detail-img">
        </div>

        <div class="detail-content">
            <h1 class="detail-title"><?= htmlspecialchars($title) ?></h1>

            <div class="price-box">
                <span class="p-original">₹<?= number_format($offer['original_price']) ?></span>
                <span class="p-discount">₹<?= number_format($offer['discount_price']) ?></span>
            </div>

            <p class="detail-desc">
                <?= nl2br(htmlspecialchars($desc)) ?>
            </p>

            <div class="meta-info">
                <div class="meta-row">
                    <strong>Valid From:</strong>
                    <span><?= date('d M Y', strtotime($offer['start_date'])) ?></span>
                </div>
                <div class="meta-row">
                    <strong>Valid Till:</strong>
                    <span style="color: #ef4444; font-weight: 600;"><?= date('d M Y', strtotime($offer['end_date'])) ?></span>
                </div>
            </div>

            <?php if($product_id > 0): ?>
                <button class="add-cart-btn" onclick="addToCart(<?= $product_id ?>, this)">
                    <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                </button>
            <?php else: ?>
                <a href="products.php" class="add-cart-btn" style="text-decoration:none;">Explore Products</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="cart-toast" class="toast">Product added to cart!</div>

<script>
function addToCart(productId, btn) {
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';
    btn.style.opacity = '0.7';
    btn.disabled = true;

    const formData = new FormData();
    formData.append('product_id', productId);

    fetch('ajax/add_to_cart.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message);
            // Update cart badge if exists
            const cartCountElem = document.querySelector('.cart-badge');
            if (cartCountElem) {
                cartCountElem.innerText = data.cart_count;
                cartCountElem.style.display = 'flex';
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to add product to cart.');
    })
    .finally(() => {
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Added';
        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.style.opacity = '1';
            btn.disabled = false;
        }, 2000);
    });
}

function showToast(msg) {
    const toast = document.getElementById('cart-toast');
    toast.innerText = msg;
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}
</script>

<?php include("includes/footer.php"); ?>