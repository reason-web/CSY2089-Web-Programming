<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

$message = '';

$stmt = $pdo->query("SELECT * FROM category ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $categoryId = $_POST['category'];
    $endDate = $_POST['endDate'];

    $stmt = $pdo->prepare(
        "INSERT INTO auction (title, description, categoryId, endDate, userId)
         VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->execute([
        $title,
        $description,
        $categoryId,
        $endDate,
        $_SESSION['userId']
    ]);

    $message = "Auction added successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Auction</title>
</head>
<body>
    <h1>Add a Car Auction</h1>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" required>

        <br><br>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <br><br>

        <label>Category:</label>
        <select name="category" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['categoryId']; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <label>Auction end date:</label>
        <input type="datetime-local" name="endDate" required>

        <br><br>

        <button type="submit">Add Auction</button>
    </form>
</body>
</html>