<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userId']) || $_SESSION['isAdmin'] != 1) {
    die("Access denied.");
}

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("DELETE FROM category WHERE categoryId = ?");
$stmt->execute([$id]);

header("Location: adminCategories.php");
exit;
?>