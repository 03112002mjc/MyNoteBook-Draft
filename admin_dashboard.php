<?php
session_start();
include 'config.php';
include 'function.php';

// Ensure only admins can access this page
checkRole('admin');

// Fetch all users for the admin to manage
$sql = "SELECT id, name, email, role FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://kit.fontawesome.com/4a9d01e598.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/admin_dashboard.css">
</head>
<body>
    <h1><i class="fa-solid fa-user"></i>Admin</h1>
    <nav>
        <ul>
            <li><a href="manage_user.php"><i class="fa-solid fa-user"></i></a></li>
            <li><a href="settings.php"><i class="fa-solid fa-gear"></i></a></li>
            <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i></a></li>
        </ul>
    </nav>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr onclick="window.location='dashboard.php?id=<?php echo $row['id']; ?>';" style="cursor:pointer;">
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['role']; ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
