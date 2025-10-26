<?php
session_start();
require "db_connect.php";

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare("SELECT * FROM user_tbl WHERE email = :email LIMIT 1");
$stmt->bindParam(":email", $email);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// --- LOGIN ERROR ---
if (!$user) {
    $_SESSION['login_error'] = "notfound";
    header("Location: login/index.php");
    exit;
}

if (!password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = "wrongpass";
    header("Location: login/index.php");
    exit;
}

if ($user['role'] === 'Admin') {
    header("Location: admin/index.php");
} else {
    header("Location: adopter/home.php");
}
exit;
?>
