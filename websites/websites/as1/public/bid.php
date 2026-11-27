<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

$auctionId = $_POST['auctionId'] ?? 0;
$bidAmount = $_POST['bid'] ?? 0;

$stmt = $pdo->prepare(
    "SELECT MAX(bid) AS highestBid FROM bid WHERE auctionId = ?"
);
$stmt->execute([$auctionId]);
$highestBid = $stmt->fetchColumn() ?: 0;

if ($bidAmount > $highestBid && is_numeric($bidAmount) && $bidAmount > 0) {
    $stmt = $pdo->prepare(
        "INSERT INTO bid (auctionId, userId, bid) VALUES (?, ?, ?)"
    );

    $stmt->execute([
        $auctionId,
        $_SESSION['userId'],
        $bidAmount
    ]);

    header("Location: auction.php?id=" . $auctionId);
    exit;
}

echo "Your bid must be higher than the current highest bid.";
?>