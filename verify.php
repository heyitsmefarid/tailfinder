<?php
session_start();
require_once 'api/add_user.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp_input = $_POST['otp'] ?? '';
    $otp_session = $_SESSION['otp'] ?? '';
    $user_data = $_SESSION['temp_user'] ?? null;

    if (!$user_data) {
        echo json_encode(['status' => 'error', 'message' => 'No registration session found']);
        exit;
    }

    if ($otp_input != $otp_session) {
        echo json_encode(['status' => 'error', 'message' => 'Incorrect verification code']);
        exit;
    }

    $user_data['status'] = 'verified';
    $result = addUser($user_data);

    if ($result === true) {
        unset($_SESSION['temp_user'], $_SESSION['otp']);
        echo json_encode(['status' => 'success', 'message' => 'Account successfully created']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $result]);
    }
}
