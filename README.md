<div align="center">

# 🛍️ Digital Bazaar

### A Modern Multi-Category E-Commerce Marketplace

[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![HTML5](https://img.shields.io/badge/HTML5-Frontend-E34F26?style=flat-square&logo=html5&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/HTML)
[![CSS3](https://img.shields.io/badge/CSS3-Glassmorphism-1572B6?style=flat-square&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=flat-square&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

*A comprehensive, visually stunning digital marketplace offering seamless shopping across all product categories.*

[Features](#-features) • [Installation](#-installation) • [Project Structure](#-project-structure) • [Tech Stack](#-tech-stack) • [Contributing](#-contributing)

</div>

---

## 📌 About The Project

**Digital Bazaar** is a full-featured, multi-category e-commerce web application built with **PHP** and **MySQL**. Designed as a modern digital marketplace, it provides users with a seamless and visually appealing shopping experience powered by a **3D-inspired Glassmorphism UI**.

Whether you're browsing products, managing your cart, tracking orders, or administering the store — Digital Bazaar handles it all with a clean and intuitive interface.

---

## ✨ Features

### 🔐 User Authentication
- Secure registration and login system
- User profile management and editing
- Session-based authentication (`login.php`, `register.php`, `edit-profile.php`)

### 🛒 Product Catalog
- Browse products across multiple categories
- Dynamic search and filtering
- Detailed product view pages (`products.php`)

### 🧺 Shopping Cart & Checkout
- Add/remove items and manage quantities
- Secure and smooth checkout process (`cart.php`, `checkout.php`)

### 📦 Order Management
- View past order history
- Download and view invoices in PDF format (`my_orders.php`, `invoice.php`)

### 🎁 Special Offers
- Dedicated deals and discounts section
- Detailed offer pages (`offers.php`, `offer_details.php`)

### 🛠️ Admin Dashboard
- Robust backend panel (`/admin`)
- Manage product catalog, user orders, and store configurations

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | PHP 7.4+ |
| **Database** | MySQL |
| **Frontend** | HTML5, CSS3, JavaScript (ES6) |
| **UI Design** | Glassmorphism, 3D-inspired responsive design |
| **Server** | XAMPP / WAMP / LAMP |

---

## ⚙️ Installation

### Prerequisites

Before getting started, make sure you have the following installed:

- A local server environment — [XAMPP](https://www.apachefriends.org/), [WAMP](https://www.wampserver.com/), or [LAMP](https://ubuntu.com/server/docs/lamp-applications)
- PHP **7.4** or higher
- MySQL database

---

### Step-by-Step Setup

**1. Clone or Download the Project**

```bash
git clone https://github.com/ahdave1573-dev/Digital-Bazaar.git
```

Or download the ZIP and extract it.

**2. Place in Web Server Root**

Move the project folder to your server's root directory:

```
# XAMPP (Windows)
C:\xampp\htdocs\DD\

# WAMP (Windows)
C:\wamp64\www\DD\

# LAMP (Linux)
/var/www/html/DD/
```

**3. Database Setup**

- Open **phpMyAdmin** (or your preferred MySQL client)
- Create a new database:

```sql
CREATE DATABASE digitalbazaar;
```

- Import the provided SQL dump:

```
phpMyAdmin → Select 'digitalbazaar' → Import → Choose 'digitalbazaar.sql' → Go
```

**4. Configure Database Connection**

Open `db.php` (or the config file inside `/config`) and update your credentials:

```php
<?php
$host     = 'localhost';
$dbname   = 'digitalbazaar';
$username = 'root';       // your MySQL username
$password = '';           // your MySQL password
?>
```

**5. Launch the Application**

Open your browser and navigate to:

```
http://localhost/DD
```

---

## 📁 Project Structure

```
digital-bazaar/
│
├── 📂 admin/               # Admin panel — manage products, orders, users
├── 📂 assets/              # CSS, JavaScript, images, static resources
│   ├── css/
│   ├── js/
│   └── images/
├── 📂 ajax/                # Backend handlers for async (AJAX) requests
├── 📂 config/              # Core configuration files
├── 📂 includes/            # Reusable PHP templates (header, footer, nav)
│
├── index.php               # Home / landing page
├── products.php            # Product catalog with search & filter
├── cart.php                # Shopping cart
├── checkout.php            # Checkout process
├── offers.php              # Special offers listing
├── offer_details.php       # Single offer detail page
├── my_orders.php           # User order history
├── invoice.php             # Order invoice view / download
├── login.php               # User login
├── register.php            # User registration
├── edit-profile.php        # User profile editor
├── db.php                  # Database connection
└── digitalbazaar.sql       # SQL dump for database setup
```

---

## 🔑 Default Admin Access

> ⚠️ **Important:** Change the default admin credentials immediately after setup.

After importing the SQL dump, check the `digitalbazaar.sql` file for default admin login details, or navigate to:

```
http://localhost/DD/admin
```

---

## 🤝 Contributing

Contributions are welcome! Here's how you can help:

1. **Fork** the repository
2. **Create** your feature branch (`git checkout -b feature/AmazingFeature`)
3. **Commit** your changes (`git commit -m 'Add some AmazingFeature'`)
4. **Push** to the branch (`git push origin feature/AmazingFeature`)
5. **Open** a Pull Request on [github.com/ahdave1573-dev/Digital-Bazaar](https://github.com/ahdave1573-dev/Digital-Bazaar)

---

## 📄 License

This project is licensed under the **MIT License** — see the [LICENSE](LICENSE) file for details.

---

## 📬 Contact

Have questions or suggestions? Feel free to open an [issue](https://github.com/ahdave1573-dev/Digital-Bazaar/issues) or reach out via email at [ahdave1573@gmail.com](mailto:ahdave1573@gmail.com).

🔗 **GitHub Profile:** [github.com/ahdave1573-dev](https://github.com/ahdave1573-dev)

---

<div align="center">

Made with ❤️ using PHP & MySQL

⭐ **[Star this repo](https://github.com/ahdave1573-dev/Digital-Bazaar)** if you found it helpful!

</div>
