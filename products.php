<?php
session_start();
include('config/db.php');
include('includes/header.php');

// GET PARAMETERS
$product_id  = isset($_GET['id']) ? intval($_GET['id']) : null;
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;
?>

<style>
/* =========================================
   UPDATED CSS STYLES
   ========================================= */
:root {
    --primary: #2b63e0;       /* Updated Blue */
    --primary-dark: #1a4db3;
    --secondary: #0f172a;     /* Dark Text */
    --bg: #f8fafc;            /* Light Gray BG */
    --border: #e2e8f0;
    --text-light: #64748b;
}

body {
    background: var(--bg);
    font-family: 'Plus Jakarta Sans', sans-serif; /* Consistent Font */
    margin: 0;
}

.container {
    max-width: 1200px;
    margin: auto;
    padding: 0 20px;
}

.page-header {
    text-align: center;
    margin: 40px 0;
}

.page-header h2 {
    font-size: 32px;
    font-weight: 800;
    color: var(--secondary);
    margin-bottom: 10px;
}

.page-header p {
    color: var(--text-light);
    font-size: 1.1rem;
}

/* ===== CATEGORY FILTERS ===== */
.category-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
    margin-bottom: 40px;
}

.category-buttons a {
    padding: 10px 20px;
    background: #fff;
    color: var(--secondary);
    border: 1px solid var(--border);
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.category-buttons a:hover {
    border-color: var(--primary);
    color: var(--primary);
    transform: translateY(-2px);
}

.category-buttons a.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    box-shadow: 0 4px 12px rgba(43, 99, 224, 0.3);
}

/* ===== PRODUCT GRID ===== */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 30px;
    margin-bottom: 80px;
}

.product-card {
    background: white;
    border-radius: 16px;
    border: 1px solid var(--border);
    overflow: hidden;
    transition: 0.3s;
    display: flex;
    flex-direction: column;
    height: 100%;
    position: relative;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    border-color: var(--primary);
}

.discount-tag {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #ef4444;
    color: white;
    padding: 5px 10px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 800;
    z-index: 10;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.card-img {
    height: 220px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    border-bottom: 1px solid #f1f5f9;
    position: relative;
    overflow: hidden;
}

.card-img img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.5s ease;
}

.product-card:hover .card-img img {
    transform: scale(1.08);
}

.card-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    flex-grow: 1; 
}

.card-cat {
    font-size: 0.8rem;
    color: var(--text-light);
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--secondary);
    margin-bottom: 10px;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 2.8em;
}

.card-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}
.card-title a:hover { color: var(--primary); }

.price {
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 20px;
    display: flex;
    align-items: baseline;
    gap: 10px;
}

.old-price {
    text-decoration: line-through;
    color: var(--text-light);
    font-size: 0.9rem;
    font-weight: 600;
}

/* BUTTONS ROW */
.btn-box {
    margin-top: auto;
    display: flex;
    gap: 10px;
}

.btn {
    padding: 12px;
    text-align: center;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.95rem;
    transition: 0.3s;
    cursor: pointer;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-cart {
    flex: 1;
    background: var(--primary);
    color: white;
    border: 1px solid var(--primary);
}

.btn-cart:hover {
    background: var(--primary-dark);
    box-shadow: 0 5px 15px rgba(43, 99, 224, 0.2);
}

/* Eye Icon Button */
.btn-view {
    width: 48px; /* Square button */
    background: white;
    color: var(--primary);
    border: 1px solid #e2e8f0;
    font-size: 1.2rem;
    padding: 0; /* Center icon */
}

.btn-view:hover {
    border-color: var(--primary);
    background: #f0f7ff;
    color: var(--primary);
}


/* ===== SINGLE PRODUCT DETAIL ===== */
.detail-box {
    max-width: 1000px;
    margin: 40px auto;
    background: white;
    padding: 40px;
    border-radius: 20px;
    border: 1px solid var(--border);
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    display: flex;
    gap: 50px;
    flex-wrap: wrap;
}

.detail-img {
    flex: 1;
    min-width: 300px;
    background: #f8fafc;
    border-radius: 15px;
    padding: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.detail-img img {
    max-width: 100%;
    max-height: 400px;
    object-fit: contain;
}

.detail-info { flex: 1.2; min-width: 300px; display: flex; flex-direction: column; }

.back-link {
    display: inline-block;
    color: var(--text-light);
    text-decoration: none;
    margin-bottom: 20px;
    font-weight: 600;
}
.back-link:hover { color: var(--primary); }

.detail-info h1 {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--secondary);
    margin-top: 0;
    margin-bottom: 10px;
    line-height: 1.2;
}

.detail-price {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary);
    margin: 15px 0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.badge-discount {
    background: #fee2e2;
    color: #ef4444;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 700;
}

.detail-desc {
    color: var(--text-light);
    line-height: 1.7;
    margin-bottom: 30px;
    border-top: 1px solid #f1f5f9;
    padding-top: 20px;
}

.badge-cat {
    background: #e0e7ff;
    color: var(--primary);
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 15px;
}

/* Toast */
.toast { position: fixed; bottom: 20px; right: 20px; background: #10b981; color: white; padding: 12px 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: none; z-index: 1000; font-weight: 600; animation: slideUp 0.3s ease; }
@keyframes slideUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php
/* =================================================
   SINGLE PRODUCT VIEW LOGIC
   ================================================= */
if($product_id):

    $q = mysqli_query($conn,"
        SELECT p.*, c.name AS category_name, o.discount_percentage, o.discount_price
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN offers o ON p.id = o.product_id AND o.active = 1
        WHERE p.id='$product_id' LIMIT 1
    ");

    if(mysqli_num_rows($q)==1):
        $prod = mysqli_fetch_assoc($q);
        
        // Calculate Price Display
        $has_offer = !empty($prod['discount_price']) && $prod['discount_price'] > 0;
        $final_price = $has_offer ? $prod['discount_price'] : $prod['selling_price'];
        $original_price = $prod['selling_price'];
?>

<div class="detail-box">

    <div class="detail-img">
        <?php if($prod['image']): ?>
            <img src="assets/images/<?= $prod['image']; ?>" alt="<?= htmlspecialchars($prod['name']); ?>">
        <?php else: ?>
            <span>No Image</span>
        <?php endif; ?>
    </div>

    <div class="detail-info">
        <a href="products.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Shop</a>

        <span class="badge-cat"><?= htmlspecialchars($prod['category_name'] ?? 'General'); ?></span>

        <h1><?= htmlspecialchars($prod['name']); ?></h1>
        
        <div class="detail-price">
            <?php if($has_offer): ?>
                <span class="old-price" style="font-size: 1.2rem; color: #94a3b8;">₹<?= number_format($original_price); ?></span>
                ₹<?= number_format($final_price); ?>
                <span class="badge-discount"><?= $prod['discount_percentage']; ?>% OFF</span>
            <?php else: ?>
                ₹<?= number_format($original_price); ?>
            <?php endif; ?>
        </div>

        <p class="detail-desc">
            <?= nl2br(htmlspecialchars($prod['description'] ?? 'No description available.')); ?>
        </p>

        <button onclick="addToCart(<?= $prod['id']; ?>, this)" class="btn btn-cart" style="padding: 15px; font-size: 1.1rem; width: fit-content;">
            <i class="fa-solid fa-cart-shopping"></i> Add to Cart
        </button>
    </div>

</div>

<?php else: ?>
    <div style="text-align:center; padding: 100px 0;">
        <h2>Product Not Found</h2>
        <a href="products.php" class="btn btn-cart" style="display:inline-block; width:auto; padding: 10px 30px; margin-top:20px;">Go Back</a>
    </div>
<?php endif; ?>


<?php 
/* =================================================
   ALL PRODUCTS / FILTERED VIEW
   ================================================= */
else: 
?>

<div class="page-header">
    <h2><?= $category_id ? "Filtered Products" : "Our Collection"; ?></h2>
    <p>Find the best gadgets and accessories for you.</p>
</div>

<div class="category-buttons">
    <a href="products.php" class="<?= !$category_id ? 'active' : '' ?>">All Products</a>

    <?php
    $cats = mysqli_query($conn,"SELECT * FROM categories");
    while($c = mysqli_fetch_assoc($cats)):
    ?>
        <a href="products.php?category_id=<?= $c['id']; ?>" 
           class="<?= ($category_id == $c['id']) ? 'active' : '' ?>">
           <?= htmlspecialchars($c['name']); ?>
        </a>
    <?php endwhile; ?>
</div>

<div class="container">
    <div class="product-grid">

    <?php
    $sql = "SELECT p.*, c.name AS category_name, o.discount_percentage, o.discount_price
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN offers o ON p.id = o.product_id AND o.active = 1";
    
    if($category_id){
        $sql .= " WHERE p.category_id = '$category_id'";
    }
    
    $sql .= " ORDER BY p.id DESC";
    $q = mysqli_query($conn, $sql);

    if(mysqli_num_rows($q) > 0):
        while($row = mysqli_fetch_assoc($q)):
            $has_offer = !empty($row['discount_price']) && $row['discount_price'] > 0;
            $final_price = $has_offer ? $row['discount_price'] : $row['selling_price'];
    ?>

    <div class="product-card">

        <?php if($has_offer): ?>
            <div class="discount-tag"><?= $row['discount_percentage']; ?>% OFF</div>
        <?php endif; ?>

        <div class="card-img">
            <a href="products.php?id=<?= $row['id']; ?>" style="display:contents;">
                <?php if($row['image']): ?>
                    <img src="assets/images/<?= $row['image']; ?>" alt="<?= htmlspecialchars($row['name']); ?>">
                <?php else: ?>
                    <span style="color:#ccc">No Image</span>
                <?php endif; ?>
            </a>
        </div>

        <div class="card-body">
            
            <div class="card-cat"><?= htmlspecialchars($row['category_name'] ?? 'Gadget'); ?></div>

            <div class="card-title">
                <a href="products.php?id=<?= $row['id']; ?>" title="<?= htmlspecialchars($row['name']); ?>">
                    <?= htmlspecialchars($row['name']); ?>
                </a>
            </div>

            <div class="price">
                <?php if($has_offer): ?>
                    <span class="old-price">₹<?= number_format($row['selling_price']); ?></span>
                    ₹<?= number_format($final_price); ?>
                <?php else: ?>
                    ₹<?= number_format($row['selling_price']); ?>
                <?php endif; ?>
            </div>

            <div class="btn-box">
                <button onclick="addToCart(<?= $row['id']; ?>, this)" class="btn btn-cart">
                    <i class="fa-solid fa-cart-shopping"></i> Add
                </button>

                <a href="products.php?id=<?= $row['id']; ?>" class="btn btn-view" title="View Details">
                    <i class="fa-regular fa-eye"></i>
                </a>
            </div>

        </div>
    </div>

    <?php endwhile; else: ?>
        <h3 style="text-align:center; width:100%; grid-column: 1/-1; color:#999; padding: 50px;">No products found.</h3>
    <?php endif; ?>

    </div>
</div>

<?php endif; ?>

<div id="cart-toast" class="toast">Product added to cart!</div>

<script>
function addToCart(productId, btn) {
    const originalContent = btn.innerHTML;
    // Show Loading Spinner
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
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
            // Update Cart Badge
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
        // Show Success Check
        btn.innerHTML = '<i class="fa-solid fa-check"></i>';
        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.style.opacity = '1';
            btn.disabled = false;
        }, 1500);
    });
}

function showToast(msg) {
    const toast = document.getElementById('cart-toast');
    toast.innerText = msg;
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}
</script>

<?php include('includes/footer.php'); ?>