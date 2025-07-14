<?php
require_once 'auth.php';
require_once 'db.php';

$userId = $_SESSION['user_id'];

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'], $_POST['status'])) {
    $taskId = (int) $_POST['task_id'];
    $status = $_POST['status'];

    if (in_array($status, ['Pending', 'Completed'])) {
        $stmt = $conn->prepare("UPDATE tbl_task SET status = ? WHERE task_id = ?");
        $stmt->bind_param("si", $status, $taskId);
        $stmt->execute();
    }
}

// Fetch tasks
$query = "SELECT * FROM tbl_task WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard - Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-light px-4 py-2">
        <span class="navbar-brand fw-bold fs-4">Task Management System</span>
        <div class="dropdown">
            <button class="btn btn-outline-dark rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-5"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="logout.php">Sign Out</a></li>
            </ul>
        </div>
    </nav>

    <div class="container my-2">
        <div class="d-flex justify-content-between align-items-center mb-3">

            <h4 class="fw-semibold">My Tasks</h4>
            <a href="add_task.php" class="btn btn-outline-dark btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Task
            </a>
        </div>

        <div class="table-container">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <!-- <td><?= htmlspecialchars($row['description']) ?></td> -->
                            <td class="text-truncate" title="<?= htmlspecialchars($row['description']) ?>">
                                <?= htmlspecialchars($row['description']) ?>
                            </td>

                            <td><?= date('d-M-Y h:i A', strtotime($row['created_at'])) ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="task_id" value="<?= $row['task_id'] ?>">

                                    <div class="dropdown">
                                        <button class="btn btn-sm dropdown-toggle 
                                        <?= $row['status'] === 'Completed' ? 'btn-outline-success' : 'btn-outline-danger' ?>"
                                            type="submit" data-bs-toggle="dropdown" aria-expanded="false">
                                            <?= $row['status'] ?>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button type="submit" name="status" value="Pending" class="dropdown-item">Pending</button>
                                            </li>
                                            <li>
                                                <button type="submit" name="status" value="Completed" class="dropdown-item">Completed</button>
                                            </li>
                                        </ul>
                                    </div>
                                </form>
                            </td>
                            <td class="text-center">
                                <a href="edit_task.php?task_id=<?= $row['task_id'] ?>" class="btn btn-outline-dark btn-icon btn-sm me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete_task.php?task_id=<?= $row['task_id'] ?>" class="btn btn-outline-dark btn-icon btn-sm me-1" title="Delete" onclick="return confirm('Are you sure you want to delete this task?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>