<?php
require_once 'auth.php';
require_once 'db.php';

if (isset($_GET['task_id'])) {
    $task_id = intval($_GET['task_id']); // Convert to int safely
    $user_id = $_SESSION['user_id'];

    // Only allow deleting the task owned by the logged-in user
    $stmt = $conn->prepare("DELETE FROM tbl_task WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);

    if ($stmt->execute()) {
        // Success - redirect back to dashboard
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Failed to delete task.";
    }
} else {
    // If no ID provided, redirect to dashboard
    header("Location: dashboard.php");
    exit;
}
