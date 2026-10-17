MUSEUM BANDAR CIMANUK
=====================
Local stack: XAMPP, Apache, MySQL/MariaDB, PHP 8+.

FOLDER DAN URL
- Admin project: C:\xampp\htdocs\admin
- Visitor site: C:\xampp\htdocs\museum-cimanuk
- Visitor website: http://localhost/museum-cimanuk/
- Admin login: http://localhost/admin/login.php
- Admin demo account: username admin, password admin123

DATABASE BARU
1. Start Apache and MySQL in XAMPP.
2. Open http://localhost/phpmyadmin.
3. Import database.sql from C:\xampp\htdocs\admin.
4. Open the visitor website and admin login URLs above.

DATABASE YANG SUDAH ADA
- Back up the museum-cimanuk database first.
- Import database_upgrade.sql through phpMyAdmin. The upgrade adds the category relationship, opening hours, publish/order metadata, contact fields, and visitor-message table without deleting existing content.
- The admin and visitor pages use the same museum-cimanuk database.

PENGELOLAAN
- Admin menus manage homepage content, Jelajah sections, collection categories/items, gallery photos, articles, contact details, opening hours, and visitor messages.
- Images uploaded from admin are stored in C:\xampp\htdocs\admin\assets\uploads.
- Article and collection detail URLs use their database IDs.
- Visitor search covers published collections and articles.
- Contact messages appear in the admin inbox. Opening a message marks it as read.

KONFIGURASI
- Default XAMPP MySQL connection uses user root, an empty password, and port 3306.
- If MySQL uses a password or a different port, update admin\koneksi.php and admin\museum-cimanuk\config\koneksi.php to match.
- Change the demo admin password before deployment beyond a local development machine.
