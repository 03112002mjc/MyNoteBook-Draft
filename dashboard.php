<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user's name
$sql = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name);
$stmt->fetch();
$stmt->close();

// Fetch user's notes
$sql = "SELECT id, title, content, created_at FROM notes WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notes = [];
while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <h1> <?php echo htmlspecialchars($name); ?></h1>
        <a href="logout.php">Logout</a>
    </header>
    <main>
        <form method="post" action="add_note.php">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <br>
            <label for="content">Content:</label>
            <textarea id="content" name="content" required></textarea>
            <br>
            <button type="submit"><i class="fas fa-circle-plus"></i> Note</button>
        </form><br>
        <div class="notes">
            <?php if (!empty($notes)): ?>
                <?php foreach ($notes as $note): ?>
                    <div class="note">
                        <h2><?php echo htmlspecialchars($note['title']); ?></h2>
                        <p><?php echo nl2br(htmlspecialchars($note['content'])); ?></p>
                        <small><?php echo htmlspecialchars($note['created_at']); ?></small><br>

                        <form action="view_note.php" method="post" style="display:inline;">
                            <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                            <button type="submit"><i class="fas fa-eye"></i></button>
                        </form>
                        <form action="edit_note.php" method="post" style="display:inline;">
                            <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                            <button type="submit"><i class="fas fa-edit"></i></button>
                        </form>
                        <form action="delete_note.php" method="post" style="display:inline;">
                            <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this note?');"><i class="fas fa-trash"></i></button>
                        </form>
                        
                    </div>
                    <?php endforeach; ?>
            <?php else: ?>
                <p>No notes found. Create your first note below.</p>
            <?php endif; ?>
        </div>
        
    </main>
    <footer id="footer">
    <p>
        <small>&copy; 2024 | Notebook by <a href="https://github.com/">Group2</a>, WebDev</small>
    </p>
</footer>
</body>
</html>
