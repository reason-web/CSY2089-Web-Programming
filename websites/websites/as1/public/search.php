<?php
require 'db.php';

$search = trim($_GET['search'] ?? '');
$categoryId = $_GET['category'] ?? '';

$sql = "
    SELECT auction.*, category.name AS categoryName
    FROM auction
    JOIN category ON auction.categoryId = category.categoryId
    WHERE (auction.title LIKE ? OR auction.description LIKE ?)
";

$params = ["%$search%", "%$search%"];

if ($categoryId !== '') {
    $sql .= " AND auction.categoryId = ?";
    $params[] = $categoryId;
}

$sql .= " ORDER BY auction.endDate ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Auctions</title>
    <link rel="stylesheet" href="carbuy.css">
</head>
<body>

<h1>Search Auctions</h1>

<form method="GET">
    <input type="text" name="search"
           value="<?php echo htmlspecialchars($search); ?>"
           placeholder="Search cars..." required>
    <button type="submit">Search</button>
</form>

<ul>
    <?php foreach ($results as $auction): ?>
        <li>
            <h2><?php echo htmlspecialchars($auction['title']); ?></h2>
            <p><?php echo htmlspecialchars($auction['categoryName']); ?></p>
            <p><?php echo htmlspecialchars($auction['description']); ?></p>
            <a href="auction.php?id=<?php echo $auction['auctionId']; ?>">More</a>
        </li>
    <?php endforeach; ?>
</ul>

<?php if (!$results): ?>
    <p>No auctions found.</p>
<?php endif; ?>

<a href="index.php">Back to homepage</a>

</body>
</html>