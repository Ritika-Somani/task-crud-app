<?php
require_once 'db.php';
session_start();

$errors = [];

// Disposable email domains list
$disposableDomains = [
    'tempmail.com', '10minutemail.com', 'mailinator.com', 'yopmail.com',
    'guerrillamail.com', 'trashmail.com', 'fakeinbox.com', 'emailondeck.com'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    $domain = strtolower(explode('@', $email)[1] ?? '');

    // Validation check on all respective fields
    if (empty($email) || empty($password) || empty($confirm)) {
        $errors[] = "All fields are required.";
    } elseif (
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        preg_match('/\.\./', $email) || // prevent double dots
        !preg_match('/^[a-zA-Z0-9][a-zA-Z0-9._%+-]*[a-zA-Z0-9]@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email) || // valid structure
        in_array($domain, $disposableDomains)
    ) {
        $errors[] = "Please enter a valid, well-formatted, and non-temporary email address.";
    } elseif ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    } elseif (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[\W_]/', $password)
    ) {
        $errors[] = "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.";
    } elseif (preg_match('/(0123|1234|2345|abcd|bcde|cdef|qwerty)/i', $password)) {
        $errors[] = "Password should not contain common sequences like '1234', 'abcd', or 'qwerty'.";
    }

    // Email uniqueness check
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT user_id FROM tbl_user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Email is already registered.";
        }
    }

    // Insert user
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO tbl_user (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['email'] = $email;
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="form-card">
        <h4 class="text-center mb-3">Create a New Account</h4>

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger py-2 small">
                <ul class="mb-0">
                    <?php foreach ($errors as $e) : ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" class="mt-3">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-outline-dark w-100">Sign Up</button>
        </form>

        <div class="text-center mt-3">
            <label class="form-label d-inline mb-0">Already have an account?</label>
            <a href="index.php" class="text-decoration-none text-small ms-1">Sign In</a>
        </div>
    </div>
</body>
</html>
