<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userId']) || $_SESSION['isAdmin'] != 1) {
    die("Access denied.");
}

$categories = $pdo->query("SELECT * FROM category ORDER BY name")
                  ->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Categories</title>
</head>
<body>
    <h1>Manage Categories</h1>

    <a href="addCategory.php">Add Category</a>

    <ul>
        <?php foreach ($categories as $category): ?>
            <li>
                <?php echo htmlspecialchars($category['name']); ?>

                <a href="editCategory.php?id=<?php echo $category['categoryId']; ?>">Edit</a>

                <a href="deleteCategory.php?id=<?php echo $category['categoryId']; ?>">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>