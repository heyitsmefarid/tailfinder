<?php
session_start();
require 'db_connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $middle_initial = trim($_POST['middle_initial']);
    $contact_number = trim($_POST['contact_number']);
    $street = trim($_POST['street']);
    $barangay = trim($_POST['barangay']);
    $city = trim($_POST['city']);
    $province = trim($_POST['province']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validations
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: login/index.html");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email!";
        header("Location: login/index.html");
        exit;
    }

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM user_tbl WHERE email=:email");
    $check->execute([':email'=>$email]);
    if ($check->rowCount() > 0) {
        $_SESSION['error'] = "Email already exists!";
        header("Location: login/index.php");
        exit;
    }

    // Save user temporarily in session
    $_SESSION['temp_user'] = [
        'first_name'=>$first_name,
        'last_name'=>$last_name,
        'middle_initial'=>$middle_initial,
        'contact_number'=>$contact_number,
        'street'=>$street,
        'barangay'=>$barangay,
        'city'=>$city,
        'province'=>$province,
        'email'=>$email,
        'password'=>password_hash($password, PASSWORD_DEFAULT),
        'role'=>"Adopter",
        'date_registered'=>date('Y-m-d')
    ];

    // Generate OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_time'] = time(); // for potential expiration

    // Send OTP email
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "gallanofredanthony15@gmail.com";
        $mail->Password = "nnhmdijzjuhczxqe"; // App password
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;

        $mail->setFrom("gallanofredanthony15@gmail.com", "Pet Adoption Verification");
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Your Verification Code";
        $mail->Body = "<h2>Welcome!</h2><p>Your verification code is:</p><h1>$otp</h1>";

        $mail->send();

        // Redirect to verification page
        header("Location: verify.html");
        exit;

    } catch(Exception $e) {
        $_SESSION['error'] = "Mail error: " . $mail->ErrorInfo;
        header("Location: login/index.php");
        exit;
    }
}
?>
