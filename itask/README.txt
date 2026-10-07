I-TASK — IT TASK AND MANAGEMENT SYSTEM (PHP + MySQL)

FEATURES (matches the proposal)
- Add Account (register) and sign in
- Add, edit, delete, and track tasks (Pending / Completed / Overdue)
- Due dates and priority tags
- Feedback: loading bar, button loading states, success and error messages
- Confirming destructive behavior: warning modal before a task is deleted

SETUP (XAMPP)
1. Start Apache and MySQL.
2. Copy this folder into C:\xampp\htdocs\
3. In phpMyAdmin, import itask_db.sql.
4. Check db.php (default XAMPP: user root, blank password).
5. Open http://localhost/<folder-name>/

FILES
index.php, login.php, register.php, dashboard.php, logout.php, auth.php, db.php
icons.php       Lucide-style line icons (24px, 2px stroke)
auth_art.php    Blue panel shown beside the login/register forms
css/style.css   All styling, based on the UI Style Guide (colors, type, spacing, components)
js/app.js       Edit modal, delete warning modal, loading states, task filter, show/hide password
itask_db.sql    Database and tables
