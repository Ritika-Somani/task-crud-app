# 📝 Task Management System (Core PHP + MySQL)

A mini CRUD application built using Core PHP and MySQL that allows users to:

- Register & login securely
- Add, edit, delete tasks
- Update task status
- Reset forgotten passwords via token-based flow (dummy email link)

---

## 🚀 Features

- 🔐 **Login & Register** with password hashing (`password_hash`)
- ✅ **CRUD Operations**: Add, Edit, Delete your tasks
- 📌 **Status Toggle**: Switch between Pending / Completed
- 🔄 **Forgot Password**: Request reset link and set a new password securely
- 🔒 **Session-based Access Control** (protected pages)
- 🧪 **Form Validation** on all inputs
- 🎨 **Modern UI** with Bootstrap 5

---

## 🗂️ Folder Structure

```plaintext
task-crud-app/
├── index.php           # Login Page
├── register.php        # User Registration
├── dashboard.php       # Task List (Dashboard)
├── add_task.php        # Add New Task
├── edit_task.php       # Edit Existing Task
├── delete_task.php     # Delete Task
├── forgot_password.php # Request Reset Token
├── reset_password.php  # Enter New Password
├── logout.php          # Logout & End Session
├── auth.php            # Session Checker (for protected pages)
├── db.php              # Database Connection
├── style.css           # Optional Custom Styling
├── database.sql        # MySQL Export File (schema + sample data)
└── .gitignore          # Ignores .env and logs
```

---

## 🛠️ Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone https://github.com/Ritika-Somani/task-crud-app.git
   cd task-crud-app
   ```

2. **Import the Database**
   - Open phpMyAdmin or MySQL Workbench
   - Create a new database (e.g. `task_crud_app`)
   - Import the file: `database.sql`

3. **Configure Database Connection**
   Edit `db.php` and set your local DB credentials:
   ```php
   $host = "localhost";
   $username = "root";
   $password = "";
   $dbname = "task_crud_app";
   ```
   > ⚠️ Update these values as per your local setup if needed.

4. **Run the App**
   - Start your local server (XAMPP, WAMP, etc.)
   - Visit: [http://localhost/task-crud-app](http://localhost/task-crud-app)

5. **Test Credentials**
   Use any of the users from `database.sql`, e.g.:
   - **Email:** admin@example.com
   - **Password:** Admin@1041
   > ⚠️ For security, change or remove these credentials in production.

---

## 📌 Notes

- Passwords are hashed using `password_hash()`
- Reset tokens are securely generated and expire in 15 minutes
- Reset password link is shown on-screen only (dummy) — no real email sending

---

## 🙋‍♀️ Developed By

Ritika Somani

---
