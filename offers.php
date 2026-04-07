<?php
session_start();
include("db.php");
include("includes/header.php");

// Fetch Offers
$offers_result = false;
if (isset($conn)) {
    $offers_result = mysqli_query($conn, "SELECT * FROM offers WHERE active = 1 AND (end_date IS NULL OR end_date >= NOW()) ORDER BY id DESC");
}
?>

<style>
    :root {
        --primary-blue: #2b63e0;
        --dark-blue: #1a4db3;
        --text-black: #1a1a1a;
        --text-grey: #64748b;
        --bg-light: #f3f8ff;
    }

    body {
        background-color: #ffffff;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* HERO SECTION */
    .offers-hero {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
        padding: 60px 20px;
        text-align: center;
        color: white;
        border-radius: 20px;
        margin-bottom: 50px;
        box-shadow: 0 10px 25px rgba(43, 99, 224, 0.2);
        position: relative;
        overflow: hidden;
    }

    .offers-hero h1 { font-size: 2.8rem; font-weight: 800; margin: 0 0 15px 0; }
    .offers-hero p { font-size: 1.1rem; opacity: 0.9; margin: 0; max-width: 600px; margin-left: auto; margin-right: auto; }

    /* GRID SYSTEM */
    .offers-container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
    .offers-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }

    /* CARD DESIGN */
    .offer-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
    }

    .offer-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(43, 99, 224, 0.1);
        border-color: var(--primary-blue);
    }

    .offer-img-wrapper {
        position: relative;
        height: 260px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        overflow: hidden;
    }

    .offer-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
    }

    .offer-card:hover .offer-img { transform: scale(1.05); }

    .offer-badge {
        position: absolute;
        top: 15px; left: 15px;
        background: var(--primary-blue);
        color: white;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(43, 99, 224, 0.3);
        z-index: 2;
    }

    .offer-content {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    /* Clickable Title */
    .offer-title { margin: 0 0 10px 0; line-height: 1.4; height: 50px; overflow: hidden; }
    .offer-title a {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-black);
        text-decoration: none;
        transition: color 0.2s;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .offer-title a:hover { color: var(--primary-blue); }

    .price-row { display: flex; align-items: baseline; gap: 12px; margin-bottom: 20px; }
    .original-price { text-decoration: line-through; color: #94a3b8; font-size: 1rem; }
    .discount-price { color: var(--primary-blue); font-size: 1.5rem; font-weight: 800; }

    /* ACTION BUTTONS ROW */
    .action-buttons {
        margin-top: auto;
        display: flex;
        gap: 10px;
    }

    .offer-btn {
        flex: 1;
        background: var(--primary-blue);
        color: white;
        border: 1px solid var(--primary-blue);
        text-align: center;
        padding: 12px;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: 0.3s;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .offer-btn:hover { background: var(--dark-blue); box-shadow: 0 5px 15px rgba(43, 99, 224, 0.2); }

    .view-btn {
        width: 48px;
        background: white;
        color: var(--primary-blue);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: 0.3s;
        text-decoration: none;
        cursor: pointer;
    }

    .view-btn:hover {
        border-color: var(--primary-blue);
        background: #f0f7ff;
        color: var(--primary-blue);
    }

    /* EMPTY STATE */
    .no-offers { grid-column: 1 / -1; text-align: center; padding: 80px 20px; background: #f8fafc; border-radius: 20px; border: 2px dashed #e2e8f0; }

    /* TOAST */
    .toast { position: fixed; bottom: 20px; right: 20px; background: #10b981; color: white; padding: 12px 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: none; z-index: 1000; font-weight: 600; animation: slideUp 0.3s ease; }
    @keyframes slideUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    @media (max-width: 768px) {
        .offers-hero h1 { font-size: 2rem; }
        .offers-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="offers-container">
    <div class="offers-hero">
        <h1>Exclusive Deals & Offers</h1>
        <p>Grab the best discounts on your favorite premium tech products. Limited time only!</p>
    </div>

    <div class="offers-grid">
        <?php
        $rendered = 0;

        if ($offers_result && mysqli_num_rows($offers_result) > 0) {
            while ($row = mysqli_fetch_assoc($offers_result)) {
                // Determine Data
                $offer_id = $row['id'];
                $title = $row['title'] ?? 'Exclusive Offer';
                $imageVal = $row['image'] ?? '';
                $imgFile = !empty($imageVal) ? 'assets/images/offers/' . basename($imageVal) : 'assets/images/no-image.png';
                $discount = $row['discount_percentage'] ?? 0;
                
                // IMPORTANT: This links to the NEW file we are creating below
                $detailLink = "offer_details.php?id=" . $offer_id;
                ?>
                
                <div class="offer-card">
                    <div class="offer-img-wrapper">
                        <a href="<?= $detailLink ?>" style="display:contents;">
                            <img src="<?= htmlspecialchars($imgFile) ?>" alt="<?= htmlspecialchars($title) ?>" class="offer-img">
                        </a>
                        <div class="offer-badge"><?= $discount ?>% OFF</div>
                    </div>
                    
                    <div class="offer-content">
                        <h3 class="offer-title">
                            <a href="<?= $detailLink ?>" title="<?= htmlspecialchars($title) ?>">
                                <?= htmlspecialchars($title) ?>
                            </a>
                        </h3>
                        
                        <div class="price-row">
                            <span class="original-price">₹<?= number_format($row['original_price'] ?? 0) ?></span>
                            <span class="discount-price">₹<?= number_format($row['discount_price'] ?? 0) ?></span>
                        </div>
                        
                        <div class="action-buttons">
                            <?php if(($row['product_id'] ?? 0) > 0): ?>
                                <a onclick="addToCart(<?= $row['product_id'] ?>, this)" class="offer-btn">
                                    <i class="fa-solid fa-cart-shopping"></i> Add
                                </a>
                            <?php else: ?>
                                <a href="products.php" class="offer-btn">Explore</a>
                            <?php endif; ?>

                            <a href="<?= $detailLink ?>" class="view-btn" title="View Details">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <?php
                $rendered++;
            }
        }

        if ($rendered === 0) {
            echo '<div class="no-offers">
                    <i class="fa-solid fa-gift" style="font-size: 3rem; margin-bottom: 20px; color:#cbd5e1;"></i>
                    <h3 style="color:#64748b;">No Active Offers</h3>
                    <p style="color:#94a3b8;">Check back later for new deals!</p>
                </div>';
        }
        ?>
    </div>
</div>

<div id="cart-toast" class="toast">Product added to cart!</div>

<script>
function addToCart(productId, btn) {
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
    btn.style.opacity = '0.7';
    btn.style.pointerEvents = 'none';

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
            btn.style.pointerEvents = 'auto';
        }, 2000);
    });
}

function showToast(msg) {
    const toast = document.getElementById('cart-toast');
    toast.innerText = msg;
    toast.style.display = 'block';
    setTimeout(() => {
        toast.style.display = 'none';
    }, 3000);
}
</script>

<?php include("includes/footer.php"); ?>