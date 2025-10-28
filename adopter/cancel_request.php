<?php
session_start();
require '../db_connect.php';
$conn = connectDB();
if(!isset($_SESSION['user_id'])){
    header("Location: ../login/index.html");
    exit;
}

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['request_id'])){
    $request_id = $_POST['request_id'];
    $user_id = $_SESSION['user_id'];

    // Only allow cancel if request belongs to user and is still pending
    $stmt = $conn->prepare("DELETE FROM adoption_request_tbl WHERE request_id=? AND user_id=? AND adoption_status='Pending'");
    $stmt->execute([$request_id, $user_id]);
}

header("Location: adoption_request.php");
exit;
