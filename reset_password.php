<?php
require_once 'db.php';
session_start();

$errors = [];
$success = '';

// Autofill token from URL or POST
$token = $_GET['token'] ?? ($_POST['token'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($token) || empty($newPassword) || empty($confirmPassword)) {
        $errors[] = "All fields are required.";
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    } elseif (
        strlen($newPassword) < 8 ||
        !preg_match('/[A-Z]/', $newPassword) ||
        !preg_match('/[a-z]/', $newPassword) ||
        !preg_match('/[0-9]/', $newPassword) ||
        !preg_match('/[\W_]/', $newPassword)
    ) {
        $errors[] = "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.";
    } elseif (preg_match('/(0123|1234|2345|abcd|bcde|cdef|qwerty)/i', $newPassword)) {
        $errors[] = "Password should not contain common sequences like '1234', 'abcd', or 'qwerty'.";
    } else {
        $stmt = $conn->prepare("SELECT user_id, token_expiry FROM tbl_user WHERE reset_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (strtotime($user['token_expiry']) >= time()) {
                $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

                $update = $conn->prepare("UPDATE tbl_user SET password = ?, reset_token = NULL, token_expiry = NULL WHERE user_id = ?");
                $update->bind_param("si", $hashed, $user['user_id']);
                $update->execute();

                $success = "Password successfully updated. You can now <a href='index.php'>Sign In</a>.";
            } else {
                $errors[] = "Reset token has expired.";
            }
        } else {
            $errors[] = "Invalid token.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <div class="form-card">
        <h4 class="text-center mb-3">Reset Password</h4>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger py-2 small">
                <ul class="mb-0">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success small text-center"><?= $success ?></div>
        <?php endif; ?>

        <?php if (!$success): ?>
            <form method="POST" class="mt-3">
                <div class="mb-3">
                    <label class="form-label">Reset Token</label>
                    <input type="password" name="token" class="form-control" value="<?= htmlspecialchars($token) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>

                <div class="text-end">
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Cancel</a>
                    <button type="submit" class="btn btn-outline-dark btn-sm">Reset Password</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>