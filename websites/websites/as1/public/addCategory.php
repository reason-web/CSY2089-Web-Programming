<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userId']) || $_SESSION['isAdmin'] != 1) {
    die("Access denied.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if ($name !== '') {
        $stmt = $pdo->prepare("INSERT INTO category (name) VALUES (?)");
        $stmt->execute([$name]);
        $message = "Category added successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
</head>
<body>
    <h1>Add Category</h1>
    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">
        <label>Category name:</label>
        <input type="text" name="name" required>
        <button type="submit">Add Category</button>
    </form>

    <a href="adminCategories.php">Back to Categories</a>
</body>
</html>