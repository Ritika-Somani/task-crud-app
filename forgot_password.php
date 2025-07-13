<?php
require_once 'db.php';
session_start();

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        $stmt = $conn->prepare("SELECT user_id FROM tbl_user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            // Generate secure token
            $token = bin2hex(random_bytes(16));
            $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            // Save token in a new table or in user table (here, assuming tbl_user has reset_token and token_expiry columns)
            $stmt->close();
            $update = $conn->prepare("UPDATE tbl_user SET reset_token = ?, token_expiry = ? WHERE email = ?");
            $update->bind_param("sss", $token, $expiry, $email);
            $update->execute();

            $resetLink = "http://localhost/task-crud-app/reset_password.php?token=$token";
            $success = "Password reset link (dummy): <a href='$resetLink'>$resetLink</a>";
        } else {
            $errors[] = "No user found with this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>

    <div class="form-card">
        <h4 class="text-center">Forgot Password</h4>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger py-2">
                <ul class="mb-0">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Registered Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="text-end">
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Cancel</a>
                    <button type="submit" class="btn btn-outline-dark btn-sm">Generate Link</button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-success small"> <?= $success ?> </div>
            <div class="text-end mt-3">
                <a href="index.php" class="btn btn-outline-secondary btn-sm">Cancel</a>
            </div>
        <?php endif; ?>





        <!-- <form method="POST">
            <div class="mb-3">
                <label class="form-label">Registered Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="text-end">
                <a href="index.php" class="btn btn-outline-secondary btn-sm">Cancel</a>
                <button type="submit" class="btn btn-dark btn-sm">Send Link</button>
            </div>
        </form> -->
    </div>

</body>

</html>