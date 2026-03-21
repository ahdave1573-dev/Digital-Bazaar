<?php
session_start();

include("config/db.php");
include("includes/header.php");

/* ================= FETCH ACTIVE OFFERS ================= */
// Check connection before query to avoid errors
if(isset($conn)){
    $active_offers = mysqli_query($conn, "SELECT * FROM offers WHERE active = 1 AND (end_date IS NULL OR end_date >= NOW()) ORDER BY id DESC LIMIT 3");
    $new_arrivals = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT 8");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigitalBazaar - The Best Tech</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-blue: #2b63e0;
            --soft-blue-bg: #f3f8ff;
            --text-black: #1a1a1a;
            --text-grey: #64748b;
            --white: #ffffff;
            --border-color: #e2e8f0;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-black);
            background: var(--white);
        }

        /* HERO SECTION */
        .hero {
            background: var(--soft-blue-bg);
            padding: 80px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-height: 500px;
        }

        .hero-content { max-width: 600px; }

        .new-collection-tag {
            background: #dbeafe;
            color: var(--primary-blue);
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 25px;
        }

        .hero h1 {
            font-size: 56px;
            font-weight: 800;
            line-height: 1.1;
            margin: 0 0 20px 0;
        }

        .hero p {
            font-size: 18px;
            color: var(--text-grey);
            margin-bottom: 35px;
            line-height: 1.6;
        }

        .btn-shop {
            background: var(--primary-blue);
            color: #fff;
            padding: 16px 35px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
        }
        
        .btn-shop:hover {
            background: #1a4db3;
            transform: translateY(-2px);
        }

        .hero-img {
            width: 100%;
            max-width: 500px; 
            height: auto;
            filter: drop-shadow(0 25px 50px rgba(0,0,0,0.15));
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        /* FEATURES BAR */
        .features {
            display: flex;
            justify-content: space-around;
            padding: 60px 5%;
            flex-wrap: wrap;
            gap: 30px;
            background: #fff;
        }

        .feat-item {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .feat-icon-box {
            width: 55px;
            height: 55px;
            background: var(--soft-blue-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 20px;
        }

        .feat-item h4 { margin: 0; font-size: 16px; font-weight: 700; }
        .feat-item p { margin: 0; color: var(--text-grey); font-size: 14px; }

        /* OFFERS SECTION (THEMED) */
        .offers-section {
            padding: 60px 8%;
            background: var(--white);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .section-header h2 {
            font-size: 32px;
            font-weight: 800;
            margin: 0;
        }

        .section-header span { color: var(--primary-blue); }

        .view-all-btn {
            text-decoration: none;
            color: var(--primary-blue);
            font-weight: 600;
            font-size: 14px;
            border: 1px solid #dbeafe;
            padding: 10px 22px;
            border-radius: 30px;
            transition: 0.3s;
        }

        .view-all-btn:hover {
            background: var(--primary-blue);
            color: white;
        }

        .offers-grid {
            display: grid;
            /* Improved Grid: Minimum 320px width to prevent squishing */
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }

        /* --- FIXED DESIGN FOR CARDS --- */
        .offer-card-index {
            background: var(--white);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: 0.3s ease;
            display: flex;       /* Add Flexbox */
            flex-direction: column; /* Stack content vertically */
            height: 100%;        /* Force full height */
        }

        .offer-card-index:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(43, 99, 224, 0.08);
            border-color: var(--primary-blue);
        }

        .offer-img-wrapper {
            position: relative;
            height: 250px; /* Fixed height for image area */
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 20px;
        }
        
        .offer-img-wrapper img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.5s ease;
        }

        .offer-card-index:hover .offer-img-wrapper img {
            transform: scale(1.05);
        }

        .offer-discount-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--primary-blue);
            color: white;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            box-shadow: 0 4px 10px rgba(43, 99, 224, 0.3);
            z-index: 10;
        }

        .offer-card-content { 
            padding: 25px; 
            display: flex;
            flex-direction: column;
            flex-grow: 1; /* This pushes the content to fill the card */
        }

        .offer-card-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 10px 0;
            line-height: 1.4;
            /* Limit title to 2 lines to keep alignment */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 50px; /* Fixed height for title area */
        }

        .offer-pricing-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .original-price {
            text-decoration: line-through;
            color: #94a3b8;
            font-size: 14px;
            font-weight: 500;
        }

        .discount-price {
            color: var(--primary-blue);
            font-size: 20px;
            font-weight: 800;
        }

        .offer-card-dates {
            background: #f8fafc;
            padding: 10px 15px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            border: 1px solid #f1f5f9;
        }

        .offer-card-dates-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        
        .offer-card-dates-row:last-child {
            margin-bottom: 0;
        }

        /* Push button to bottom */
        .offer-card-btn {
            width: 100%;
            padding: 14px;
            background: var(--white);
            color: var(--primary-blue);
            border: 1px solid var(--primary-blue);
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            margin-top: auto; /* CRITICAL: Pushes button to bottom */
        }

        .offer-card-btn:hover {
            background: var(--primary-blue);
            color: var(--white);
            box-shadow: 0 10px 20px rgba(43, 99, 224, 0.15);
        }

        /* PRODUCTS SECTION */
        .product-container { padding: 0 8% 80px; }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 30px;
        }

        .product-card {
            background: var(--white);
            border-radius: 20px;
            padding: 15px;
            text-decoration: none;
            color: inherit;
            border: 1px solid var(--border-color);
            transition: 0.3s;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.06);
            border-color: var(--primary-blue);
        }

        .img-holder {
            background: #f8fafc;
            border-radius: 15px;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            padding: 10px;
        }

        .img-holder img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        .product-info h3 {
            font-size: 16px;
            margin: 0 0 8px 0;
            font-weight: 600;
             /* Truncate text */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .price {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary-blue);
        }

        @media(max-width:768px){
            .hero { flex-direction: column; text-align: center; padding: 40px 5%; }
            .hero h1 { font-size: 36px; }
            .hero-img { margin-top: 40px; }
            .section-header { flex-direction: column; gap: 15px; text-align: center; }
            .offers-grid { grid-template-columns: 1fr; } 
        }
    </style>
</head>
<body>

<section class="hero">
    <div class="hero-content">
        <div class="new-collection-tag">New Collection 2026</div>
        <h1>The Best Tech <br> For Your Lifestyle</h1>
        <p>Discover premium electronics with fast delivery and secure payments. Upgrade your gear today.</p>
        <a href="products.php" class="btn-shop">
            Shop Now <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    <div class="hero-image">
        <img src="https://pngimg.com/d/headphones_PNG7645.png" alt="Headphones" class="hero-img">
    </div>
</section>

<div class="features">
    <div class="feat-item">
        <div class="feat-icon-box"><i class="fa-solid fa-truck-fast"></i></div>
        <div><h4>Free Shipping</h4><p>On all orders</p></div>
    </div>
    <div class="feat-item">
        <div class="feat-icon-box"><i class="fa-solid fa-shield-halved"></i></div>
        <div><h4>Secure Payment</h4><p>100% Safe</p></div>
    </div>
    <div class="feat-item">
        <div class="feat-icon-box"><i class="fa-solid fa-rotate-left"></i></div>
        <div><h4>Easy Return</h4><p>30 Days</p></div>
    </div>
    <div class="feat-item">
        <div class="feat-icon-box"><i class="fa-solid fa-headset"></i></div>
        <div><h4>24/7 Support</h4><p>Always Ready</p></div>
    </div>
</div>

<section class="offers-section">
    <div class="section-header">
        <h2>🎁 Active <span>Offers</span></h2>
        <a href="offers.php" class="view-all-btn">View All Offers →</a>
    </div>

    <div class="offers-grid">
        <?php 
        if(isset($active_offers) && mysqli_num_rows($active_offers) > 0): 
            while($offer = mysqli_fetch_assoc($active_offers)): 
        ?>
            <div class="offer-card-index">
                <div class="offer-img-wrapper">
                    <div class="offer-discount-badge"><?php echo $offer['discount_percentage']; ?>% OFF</div>
                    <?php if(!empty($offer['image'])): ?>
                        <img src="assets/images/offers/<?php echo htmlspecialchars($offer['image']); ?>" alt="Offer">
                    <?php else: ?>
                        <div style="font-size: 50px;">🎁</div>
                    <?php endif; ?>
                </div>

                <div class="offer-card-content">
                    <div class="offer-card-title" title="<?php echo htmlspecialchars($offer['title'] ?? ''); ?>">
                        <?php echo htmlspecialchars($offer['title'] ?? 'Exclusive Offer'); ?>
                    </div>
                    
                    <div class="offer-pricing-row">
                        <span class="original-price">₹<?php echo number_format($offer['original_price'] ?? 0); ?></span>
                        <span class="discount-price">₹<?php echo number_format($offer['discount_price'] ?? 0); ?></span>
                    </div>

                    <div class="offer-card-dates">
                        <div class="offer-card-dates-row">
                            <span style="color:var(--text-grey)">Starts:</span>
                            <span style="font-weight:600"><?php echo date('d M Y', strtotime($offer['start_date'])); ?></span>
                        </div>
                        <div class="offer-card-dates-row">
                            <span style="color:var(--text-grey)">Ends:</span>
                            <span style="color:#ef4444; font-weight:700"><?php echo date('d M Y', strtotime($offer['end_date'])); ?></span>
                        </div>
                    </div>

                    <?php if(($offer['product_id'] ?? 0) > 0): ?>
                        <button class="offer-card-btn" onclick="addToCart(<?= $offer['product_id'] ?>, this)">
                            Add to Cart
                        </button>
                    <?php else: ?>
                        <button class="offer-card-btn" onclick="window.location.href='products.php'">
                            Explore Offers
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php 
            endwhile;
        else:
            echo "<p style='grid-column: 1/-1; text-align:center;'>No active offers found.</p>";
        endif; 
        ?>
    </div>
</section>

<div class="section-header" style="padding: 0 8% 40px;">
    <h2>New <span>Arrivals</span></h2>
    <a href="products.php" class="view-all-btn">View All Products →</a>
</div>

<section class="product-container">
    <div class="product-grid">
        <?php if(isset($new_arrivals) && mysqli_num_rows($new_arrivals) > 0): ?>
            <?php while($p = mysqli_fetch_assoc($new_arrivals)): ?>
                <a href="products.php?id=<?= $p['id']; ?>" class="product-card">
                    <div class="img-holder">
                        <img src="assets/images/<?= $p['image']; ?>" alt="<?= htmlspecialchars($p['name']); ?>">
                    </div>
                    <div class="product-info">
                        <h3><?= htmlspecialchars($p['name']); ?></h3>
                        <div class="price">₹<?= number_format($p['selling_price']); ?></div>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products available.</p>
        <?php endif; ?>
    </div>
</section>

<div id="cart-toast" style="position: fixed; bottom: 20px; right: 20px; background: #10b981; color: white; padding: 12px 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: none; z-index: 1000;">Product added!</div>

<script>
function addToCart(productId, btn) {
    const originalText = btn.innerText;
    btn.innerText = 'Adding...';
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
            // Optional: Update cart badge if you have one with class .cart-badge
            const cartBadge = document.querySelector('.cart-badge');
            if(cartBadge) {
                cartBadge.innerText = data.cart_count;
                cartBadge.style.display = 'flex';
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error adding to cart', error))
    .finally(() => {
        btn.innerText = originalText;
        btn.disabled = false;
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

</body>
</html>