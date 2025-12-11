# Winter Shop (XAMPP) - Installation & Quick Start

This is a simple PHP + MySQL (PDO) sample e-commerce project for selling winter clothing. It is prepared to run under XAMPP.

## Structure
- `index.php` - homepage listing approved products
- `inc/` - shared includes (config, functions, header, footer)
- `seller/` - seller dashboard and product management
- `admin/` - admin dashboard
- `assets/` - images, JS
- `sql/schema.sql` - database schema and sample insert
- `README_XAMPP.md` - this file

## Requirements
- XAMPP with Apache + MySQL + PHP (7.4+ recommended)
- Put this project into `C:/xampp/htdocs/winter-shop` (or your htdocs folder)
- Start Apache and MySQL via XAMPP Control Panel

## Setup steps
1. Copy files into `htdocs/winter-shop`.
2. Open `phpMyAdmin` or MySQL CLI and run `sql/schema.sql`.
   - NOTE: `schema.sql` includes a placeholder `{PASSWORD_PLACEHOLDER}` for the admin password hash.
   - To create an admin password hash, you can run a short PHP script:
     ```php
     <?php
     echo password_hash('your_admin_password_here', PASSWORD_DEFAULT);
     ```
     Put the resulting hash into `sql/schema.sql` replacing `{PASSWORD_PLACEHOLDER}` and re-run the SQL.
3. Alternatively, create the database and tables manually and create a user with role `admin`.
4. Edit `inc/config.php` if your DB host, user, or password differ (XAMPP default user is `root` with empty password).
5. Visit: `http://localhost/winter-shop/` to see the site.

## Notes & Features
- Uses `password_hash()` and `password_verify()` for secure passwords.
- Roles: `user`, `seller`, `admin`.
- Seller products must be approved by admin (`approved` flag) before shown on the main page.
- Quick view modal loads `product_quick.php`.
- Cart is stored in session for simplicity; there is a `cart` table if you wish to implement persistent carts.
- Tailwind CSS and Google Font Kanit are loaded via CDN.

## To customize
- Replace `assets/images/placeholder.png` with product images.
- Improve validation and add CSRF protection for production.
- Add file upload for product images.

If you want, I can expand features (AJAX, image uploads, fuller admin controls) — tell me which part to flesh out next.
