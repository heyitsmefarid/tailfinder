<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp_input = $_POST['otp'] ?? '';
    $otp_session = $_SESSION['otp'] ?? '';
    $user_data = $_SESSION['temp_user'] ?? null;

    header('Content-Type: application/json');

    if (!$user_data) {
        echo json_encode(['status'=>'error','message'=>'No registration session found']);
        exit;
    }

    if ($otp_input != $otp_session) {
        echo json_encode(['status'=>'error','message'=>'Incorrect verification code']);
        exit;
    }

    // Insert user into DB after correct OTP
    try {
        $stmt = $conn->prepare("INSERT INTO user_tbl 
            (first_name, last_name, middle_initial, contact_number, street, barangay, city, province, email, password, role, date_registered, status)
            VALUES (:first_name,:last_name,:middle_initial,:contact_number,:street,:barangay,:city,:province,:email,:password,:role,:date_registered,'verified')");

        $stmt->execute([
            ':first_name'=>$user_data['first_name'],
            ':last_name'=>$user_data['last_name'],
            ':middle_initial'=>$user_data['middle_initial'],
            ':contact_number'=>$user_data['contact_number'],
            ':street'=>$user_data['street'],
            ':barangay'=>$user_data['barangay'],
            ':city'=>$user_data['city'],
            ':province'=>$user_data['province'],
            ':email'=>$user_data['email'],
            ':password'=>$user_data['password'],
            ':role'=>$user_data['role'],
            ':date_registered'=>$user_data['date_registered']
        ]);

        // Clear session
        unset($_SESSION['temp_user'], $_SESSION['otp']);

        echo json_encode(['status'=>'success','message'=>'Account successfully created']);
        exit;

    } catch(PDOException $e) {
        echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
        exit;
    }
}
?>
