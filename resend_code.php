<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';

header('Content-Type: application/json');

if(!isset($_SESSION['temp_user'])){
    echo json_encode(['status'=>'error','message'=>'No registration session']);
    exit;
}

// Generate new OTP
$otp = rand(100000, 999999);
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

    $mail->setFrom("gallanofredanthony15@gmail.com","Pet Adoption Verification");
    $mail->addAddress($_SESSION['temp_user']['email']);
    $mail->isHTML(true);
    $mail->Subject = "Your Verification Code";
    $mail->Body = "<h2>New OTP</h2><p>Your new verification code is:</p><h1>$otp</h1>";
    $mail->send();

    echo json_encode(['status'=>'success','message'=>'New OTP sent']);

} catch(Exception $e){
    echo json_encode(['status'=>'error','message'=>$mail->ErrorInfo]);
}
?>
