<?php
require_once 'auth.php';
require_once 'db.php';

$errors = [];
$taskId = $_GET['task_id'] ?? null;

// Redirect if task ID is not given
if (!$taskId) {
    header("Location: dashboard.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch existing task
$stmt = $conn->prepare("SELECT * FROM tbl_task WHERE task_id = ? AND user_id = ?");
$stmt->bind_param("ii", $taskId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: dashboard.php");
    exit;
}

$task = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'] ?? 'Pending';

    if (empty($title) || empty($description)) {
        $errors[] = "Title and Description are required.";
    }

    // Check for duplicate title (excluding current task)
    if (empty($errors)) {
        $check = $conn->prepare("SELECT task_id FROM tbl_task WHERE user_id = ? AND title = ? AND task_id != ?");
        $check->bind_param("isi", $userId, $title, $taskId);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "You already have another task with this title.";
        }
    }

    // Update if no errors
    if (empty($errors)) {
        $update = $conn->prepare("UPDATE tbl_task SET title = ?, description = ?, status = ? WHERE task_id = ? AND user_id = ?");
        $update->bind_param("sssii", $title, $description, $status, $taskId, $userId);
        if ($update->execute()) {
            header("Location: dashboard.php");
            exit;
        } else {
            $errors[] = "Failed to update task.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Task - Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <div class="form-card">
        <h4 class="text-center mb-3">Edit Task</h4>

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
                <input type="text" name="title" class="form-control" required value="<?= htmlspecialchars($_POST['title'] ?? $task['title']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($_POST['description'] ?? $task['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Pending" <?= ($_POST['status'] ?? $task['status']) === 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Completed" <?= ($_POST['status'] ?? $task['status']) === 'Completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>

            <div class="text-end">
                <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">Cancel</a>
                <button type="submit" class="btn btn-dark btn-sm">Update Task</button>
            </div>
        </form>
    </div>
</body>

</html>