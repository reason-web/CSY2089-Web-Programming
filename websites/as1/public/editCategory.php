<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userId']) || $_SESSION['isAdmin'] != 1) {
    die("Access denied.");
}

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM category WHERE categoryId = ?");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die("Category not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if ($name !== '') {
        $stmt = $pdo->prepare("UPDATE category SET name = ? WHERE categoryId = ?");
        $stmt->execute([$name, $id]);

        header("Location: adminCategories.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
</head>
<body>
    <h1>Edit Category</h1>

    <form method="POST">
        <label>Category name:</label>
        <input type="text" name="name"
               value="<?php echo htmlspecialchars($category['name']); ?>" required>

        <button type="submit">Save Changes</button>
    </form>

    <a href="adminCategories.php">Back to Categories</a>
</body>
</html>