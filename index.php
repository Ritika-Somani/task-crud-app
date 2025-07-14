<?php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login - Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>

    <div class="login-card">
        <h4 class="text-center">Sign In to Your Account</h4>

        <?php if ($error): ?>
            <div class="alert alert-danger py-1 text-center small mt-2"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="mt-3">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-outline-dark btn-login w-100">Sign In</button>
        </form>

        <div class="d-flex justify-content-between mt-3">
            <a href="forgot_password.php" class="text-decoration-none text-small">Forgot Password?</a>
            <div>
                <label class="form-label d-inline mb-0">New User?</label>
                <a href="register.php" class="text-decoration-none text-small ms-1">Sign Up</a>
            </div>
        </div>
    </div>

</body>

</html>