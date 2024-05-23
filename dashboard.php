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
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4a9d01e598.js" crossorigin="anonymous"></script>
</head>
<body>
<header>
    <div class="header-container">

        <h1 class="logo"><i class="fa-solid fa-book-open"></i> My Notebook</h1>
        <div class="user-info">
            <span class="username"><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($name); ?></span>
            <div class="dropdown">
                <button class="dropbtn"><i class="fa-solid fa-caret-down"></i></button>
                <div class="dropdown-content">
                    <a href=""><i class="fa-solid fa-user"></i>Profile</a>
                    <a href="#myNotes"><i class="fa-solid fa-note-sticky"></i>Notes</a>
                    <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>
<main>
    <div class="main-content">
        <div class="top-container">
            <h1><i class="fa-solid fa-pencil"></i> Notes</h1>
        </div>
        <div class="note-form-container">
            <form method="post" action="add_note.php" class="note-form">
                <h1>Add Note</h1>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
                <br>
                <label for="content">Content:</label>
                <textarea id="content" name="content" required></textarea>
                <br>
                <button type="submit" id="note-button"><i class="fas fa-circle-plus"></i> Note</button>
            </form>
        </div>
        <div class="notes-container">
            <h1>Notes Details</h1>
            <div class="notes" id="myNotes">
                <?php if (!empty($notes)): ?>
                    <?php foreach ($notes as $note): ?>
                        
                        <div class="note">
                            
                            <h2><?php echo htmlspecialchars($note['title']); ?></h2>
                            
                            <small><?php echo htmlspecialchars($note['created_at']); ?></small><br>
                            <div class="note-buttons">
                                <form action="view_note.php" method="post" style="display:inline;">
                                    <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                                    <button type="submit" class="icon-button"><i class="fa-solid fa-eye"></i></button>
                                </form>
                                <form action="edit_note.php" method="post" style="display:inline;">
                                    <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                                    <button type="submit" class="icon-button"><i class="fa-solid fa-edit"></i></button>
                                </form>
                                <form action="delete_note.php" method="post" style="display:inline;">
                                    <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this note?');" class="icon-button"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No notes found. Create your first note below.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main><hr>
<footer id="footer">
    <p>
        <small>&copy; 2024 | Notebook by <a href="https://github.com/">Group2</a>, WebDev</small>
    </p>
</footer>
</body>
</html>

