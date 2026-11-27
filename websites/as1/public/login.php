<?php
session_start();
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['userId'] = $user['userId'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['isAdmin'] = $user['isAdmin'];

        $message = "Login successful! Welcome " . htmlspecialchars($user['name']);
    } else {
        $message = "Incorrect email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <p><?php echo $message; ?></p>

    <form method="POST" action="login.php">
        <label>Email:</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit" name="submit">Login</button>
    </form>
</body>
</html>