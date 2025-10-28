<?php
session_start();
require_once 'db_connect.php';
require_once 'functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/Exception.php';
require_once 'phpmailer/src/SMTP.php';

header('Content-Type: application/json');

$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $middle_initial = sanitizeInput($_POST['middle_initial']);
    $contact_number = sanitizeInput($_POST['contact_number']);
    $street = sanitizeInput($_POST['street']);
    $barangay = sanitizeInput($_POST['barangay']);
    $city = sanitizeInput($_POST['city']);
    $province = sanitizeInput($_POST['province']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!passwordsMatch($password, $confirm_password)) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match!"]);
        exit;
    }

    if (!isValidEmail($email)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format!"]);
        exit;
    }

    $check = $conn->prepare("SELECT * FROM user_tbl WHERE email = :email");
    $check->execute([':email' => $email]);
    if ($check->rowCount() > 0) {
        echo json_encode(["status" => "exists", "message" => "Email already exists! Please log in instead."]);
        exit;
    }

    $_SESSION['temp_user'] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'middle_initial' => $middle_initial,
        'contact_number' => $contact_number,
        'street' => $street,
        'barangay' => $barangay,
        'city' => $city,
        'province' => $province,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT),
        'role' => "Adopter",
        'date_registered' => date('Y-m-d')
    ];

    $otp = generateOTP();
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_time'] = time();

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "gallanofredanthony15@gmail.com";
        $mail->Password = "nnhmdijzjuhczxqe";
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;

        $mail->setFrom("gallanofredanthony15@gmail.com", "Pet Adoption Verification");
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Your Verification Code";
        $mail->Body = "<h2>Welcome!</h2><p>Your verification code is:</p><h1>$otp</h1>";

        $mail->send();

        echo json_encode(["status" => "success", "message" => "OTP sent successfully. Redirecting to verification page..."]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Mailer Error: " . $mail->ErrorInfo]);
    }
}
