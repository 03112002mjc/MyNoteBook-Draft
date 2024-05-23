<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $note_id = $_POST['note_id'];
    $user_id = $_SESSION['user_id'];

    // Fetch the note details
    $sql = "SELECT title, content, created_at FROM notes WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $note_id, $user_id);
    $stmt->execute();
    $stmt->bind_result($title, $content, $created_at);
    $stmt->fetch();
    $stmt->close();
} else {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Note</title>
    <link rel="stylesheet" href="css/view_note.css">
</head>
<body>
<div class="view-note-container">
    <h1><?php echo htmlspecialchars($title); ?></h1>
    <p><?php echo nl2br(htmlspecialchars($content)); ?></p>
    <small>Created at: <?php echo htmlspecialchars($created_at); ?></small>
    <br><br>
    <a href="dashboard.php">Back to Dashboard</a>
</div>
</body>
</html>
