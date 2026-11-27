<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM auction WHERE auctionId = ? AND userId = ?");
$stmt->execute([$id, $_SESSION['userId']]);
$auction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$auction) {
    die("Auction not found or you are not allowed to edit it.");
}

$categories = $pdo->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare(
        "UPDATE auction SET title = ?, description = ?, categoryId = ?, endDate = ?
         WHERE auctionId = ? AND userId = ?"
    );

    $stmt->execute([
        trim($_POST['title']),
        trim($_POST['description']),
        $_POST['category'],
        $_POST['endDate'],
        $id,
        $_SESSION['userId']
    ]);

    header("Location: auction.php?id=" . $id);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Auction</title>
</head>
<body>
    <h1>Edit Auction</h1>

    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title"
               value="<?php echo htmlspecialchars($auction['title']); ?>" required>

        <br><br>

        <label>Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($auction['description']); ?></textarea>

        <br><br>

        <label>Category:</label>
        <select name="category" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['categoryId']; ?>"
                    <?php echo $category['categoryId'] == $auction['categoryId'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <label>End date:</label>
        <input type="datetime-local" name="endDate"
               value="<?php echo date('Y-m-d\TH:i', strtotime($auction['endDate'])); ?>" required>

        <br><br>

        <button type="submit">Save Changes</button>
    </form>
</body>
</html>