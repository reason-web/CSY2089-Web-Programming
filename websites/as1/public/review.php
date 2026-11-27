<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

$auctionId = $_POST['auctionId'] ?? 0;
$reviewText = trim($_POST['reviewText'] ?? '');

if ($reviewText !== '') {
    $stmt = $pdo->prepare("SELECT userId FROM auction WHERE auctionId = ?");
    $stmt->execute([$auctionId]);
    $sellerId = $stmt->fetchColumn();

    if ($sellerId && $sellerId != $_SESSION['userId']) {
        $stmt = $pdo->prepare(
            "INSERT INTO review (userId, reviewerId, reviewText)
             VALUES (?, ?, ?)"
        );

        $stmt->execute([
            $sellerId,
            $_SESSION['userId'],
            $reviewText
        ]);
    }
}

header("Location: auction.php?id=" . $auctionId);
exit;
?>

