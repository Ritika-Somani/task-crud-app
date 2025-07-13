<?php
require_once 'auth.php';
require_once 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'] ?? 'Pending';

    // Basic validation
    if (empty($title) || empty($description)) {
        $errors[] = "Title and Description are required.";
    }

    // Check for duplicate title for the same user
    if (empty($errors)) {
        $check = $conn->prepare("SELECT task_id FROM tbl_task WHERE user_id = ? AND title = ?");
        $check->bind_param("is", $userId, $title);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $_SESSION['errors'][] = "You already have a task with this title.";
            header("Location: add_task.php");
            exit;
        }
    }

    // Insert if no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO tbl_task (user_id, title, description, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $title, $description, $status);

        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Failed to add task.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Task - Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>

    <div class="form-card">
        <h4 class="text-center mb-3">Add New Task</h4>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger py-2">
                <ul class="mb-0">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Pending" <?= ($_POST['status'] ?? '') === 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Completed" <?= ($_POST['status'] ?? '') === 'Completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>

            <div class="text-end">
                <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">Cancel</a>
                <button type="submit" class="btn btn-dark btn-sm">Add Task</button>
            </div>
        </form>
    </div>
</body>

</html>