<?php
session_start();
?>

<?php include('includes/header.php'); ?>

<style>
/* ===== ABOUT PAGE STYLES ===== */

.about-hero{
    background:#f1f5f9;
    padding:80px 20px;
    text-align:center;
}
.about-hero h1{
    font-size:40px;
    font-weight:700;
    color:#0f172a;
}
.about-hero span{
    color:#2563eb;
}
.about-hero p{
    max-width:800px;
    margin:20px auto 0;
    color:#64748b;
    font-size:16px;
    line-height:1.7;
}

/* SECTION */
.section{
    max-width:1200px;
    margin:60px auto;
    padding:0 20px;
    text-align:center;
}
.section h2{
    font-size:28px;
    font-weight:700;
    margin-bottom:10px;
    color:#0f172a;
}
.section-line{
    width:60px;
    height:4px;
    background:#2563eb;
    margin:0 auto 40px;
    border-radius:2px;
}

/* CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:30px;
}
.card{
    background:#ffffff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
    transition:0.3s;
}
.card:hover{
    transform:translateY(-5px);
}
.card i{
    font-size:32px;
    color:#2563eb;
    margin-bottom:15px;
}
.card h3{
    margin-bottom:10px;
    font-size:20px;
    color:#0f172a;
}
.card p{
    color:#64748b;
    font-size:14px;
    line-height:1.6;
}

/* BOTTOM SECTION */
.about-bottom{
    background:#ffffff;
    padding:60px 20px;
    text-align:center;
}
.about-bottom p{
    max-width:900px;
    margin:auto;
    color:#475569;
    font-size:15px;
    line-height:1.7;
}
</style>

<!-- ===== HERO SECTION ===== -->
<div class="about-hero">
    <h1>About <span>DigitalBazaar</span></h1>
    <p>
        DigitalBazaar Electronics is your premier destination for modern technology.
        We bridge the gap between quality and affordability, providing a seamless
        shopping experience for smartphones, laptops, and electronic gadgets.
    </p>
</div>

<!-- ===== WHO WE ARE ===== -->
<div class="section">
    <h2>Who We Are</h2>
    <div class="section-line"></div>

    <div class="cards">

        <div class="card">
            <i class="fas fa-eye"></i>
            <h3>Our Vision</h3>
            <p>
                To become India’s most trusted online marketplace for electronics
                by delivering quality products and reliable service.
            </p>
        </div>

        <div class="card">
            <i class="fas fa-bullseye"></i>
            <h3>Our Mission</h3>
            <p>
                To offer the latest technology at affordable prices with fast
                delivery and excellent customer support.
            </p>
        </div>

        <div class="card">
            <i class="fas fa-microchip"></i>
            <h3>What We Sell</h3>
            <p>
                Smartphones, laptops, accessories, and the latest electronic gadgets
                from trusted brands.
            </p>
        </div>

    </div>
</div>

<!-- ===== BOTTOM INFO ===== -->
<div class="about-bottom">
    <p>
        At <strong>DigitalBazaar</strong>, customer satisfaction is our top priority.
        We continuously strive to improve our services and ensure a smooth, secure,
        and enjoyable online shopping experience for everyone.
    </p>
</div>
 
<?php include('includes/footer.php'); 
?>
