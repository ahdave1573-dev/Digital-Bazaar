<style>
        /* ::::: FOOTER CSS ::::: */
        .footer-area {
            background-color: #0f172a; /* Dark Blue Theme */
            color: #94a3b8; /* Light Gray Text */
            padding-top: 60px;
            font-family: 'Poppins', sans-serif;
            margin-top: auto; /* Pushes footer to bottom */
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }

        /* Columns */
        .footer-col h3 {
            color: #ffffff;
            font-size: 1.2rem;
            margin-bottom: 20px;
            font-weight: 600;
        }

        /* Brand Style */
        .footer-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 15px;
        }
        .footer-logo span { color: #2563eb; }

        .footer-desc {
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* Links */
        .footer-links { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.95rem;
            transition: 0.3s;
        }
        .footer-links a:hover {
            color: #2563eb;
            padding-left: 5px; /* Slide effect */
        }

        /* Contact Info */
        .contact-info p {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }
        .contact-info i { color: #2563eb; }

        /* Social Icons */
        .social-icons { display: flex; gap: 15px; margin-top: 20px; }
        .social-btn {
            width: 35px; height: 35px;
            background: #1e293b;
            color: white;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%;
            text-decoration: none;
            transition: 0.3s;
        }
        .social-btn:hover { background: #2563eb; transform: translateY(-3px); }

        /* Copyright Bar */
        .copyright-area {
            background: #020617; /* Darker shade */
            padding: 20px 0;
            margin-top: 50px;
            text-align: center;
            font-size: 0.85rem;
            border-top: 1px solid #1e293b;
        }
    </style>

    <footer class="footer-area">
        <div class="footer-container">
            
            <div class="footer-col">
                <a href="index.php" class="footer-logo">Digital<span>Bazaar</span></a>
                <p class="footer-desc">
                    Your one-stop destination for the latest electronics, gadgets, and accessories. Experience seamless shopping with fast delivery and secure payments.
                </p>
                <div class="social-icons">
                    <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="about.php">About Us</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h3>Contact Us</h3>
                <div class="contact-info">
                    <p><i class="fas fa-map-marker-alt"></i> 123, Tech Park, Rajkot, India</p>
                    <p><i class="fas fa-phone-alt"></i> +91 88499 19418</p>
                    <p><i class="fas fa-envelope"></i> support@digitalbazaar.com</p>
                    <p><i class="fas fa-clock"></i> Mon - Sat, 9:00 AM - 6:00 PM</p>
                </div>
            </div>

        </div>

        <div class="copyright-area">
            <p>&copy; <?php echo date("Y"); ?> <strong>DigitalBazaar</strong>. All Rights Reserved. | Designed for Project.</p>
        </div>
    </footer>

    <script>
        // Agar aapne header me mobile menu button lagaya hai to ye script kaam karegi
        // Filhal ye placeholder hai
        console.log("DigitalBazaar Loaded Successfully");
    </script>

</body>
</html>