<?php
session_start();
require 'db.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT auction.*, category.name AS categoryName,
           user.name AS sellerName,
           COALESCE(MAX(bid.bid), 0) AS currentBid
    FROM auction
    JOIN category ON auction.categoryId = category.categoryId
    JOIN user ON auction.userId = user.userId
    LEFT JOIN bid ON auction.auctionId = bid.auctionId
    WHERE auction.auctionId = ?
    GROUP BY auction.auctionId
");

$stmt->execute([$id]);
$auction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$auction) {
    die("Auction not found.");
}
$stmt = $pdo->prepare("
    SELECT review.*, user.name AS reviewerName
    FROM review
    JOIN user ON review.reviewerId = user.userId
    WHERE review.userId = ?
    ORDER BY review.reviewDate DESC
");
$stmt->execute([$auction['userId']]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($auction['title']); ?></title>
    <link rel="stylesheet" href="carbuy.css">
</head>
<body>

<h1><?php echo htmlspecialchars($auction['title']); ?></h1>

<img src="car.png" alt="Car" width="300">

<h3><?php echo htmlspecialchars($auction['categoryName']); ?></h3>

<p><?php echo htmlspecialchars($auction['description']); ?></p>

<p>Auction created by:
<?php echo htmlspecialchars($auction['sellerName']); ?></p>

<p>Current bid: £<?php echo number_format($auction['currentBid'], 2); ?></p>

<p>Auction ends:
<?php echo htmlspecialchars($auction['endDate']); ?></p>
<?php if (isset($_SESSION['userId'])): ?>
<form method="POST" action="bid.php">
    <input type="hidden" name="auctionId"
           value="<?php echo $auction['auctionId']; ?>">

    <label>Enter bid amount:</label>
    <input type="number" name="bid" step="0.01" min="0.01" required>

    <button type="submit">Place Bid</button>
</form>
<?php else: ?>
<p><a href="login.php">Login to place a bid</a></p>
<?php endif; ?>
<?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == $auction['userId']): ?>
    <p><a href="editAuction.php?id=<?php echo $auction['auctionId']; ?>">Edit Auction</a></p>
<?php endif; ?>
<h2>Seller Reviews</h2>

<?php foreach ($reviews as $review): ?>
    <p>
        <strong><?php echo htmlspecialchars($review['reviewerName']); ?>:</strong>
        <?php echo htmlspecialchars($review['reviewText']); ?>
    </p>
<?php endforeach; ?>

<?php if (isset($_SESSION['userId']) && $_SESSION['userId'] != $auction['userId']): ?>
    <h3>Write a Review</h3>
    <form method="POST" action="review.php">
        <input type="hidden" name="auctionId" value="<?php echo $auction['auctionId']; ?>">
        <textarea name="reviewText" required></textarea>
        <button type="submit">Submit Review</button>
    </form>
<?php endif; ?>
<a href="index.php">Back to homepage</a>

</body>
</html>