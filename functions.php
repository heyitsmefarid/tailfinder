<?php
require_once 'db_connect.php';


function sanitizeInput($data)
{
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function generateOTP()
{
    return rand(100000, 999999);
}

function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function passwordsMatch($pass1, $pass2)
{
    return $pass1 === $pass2;
}

function redirectWithError($message, $location)
{
    $_SESSION['error'] = $message;
    header("Location: $location");
    exit();
}

function getUserByEmail($conn, $email)
{
    $stmt = $conn->prepare("SELECT * FROM user_tbl WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getUserById($conn, $id)
{
    $stmt = $conn->prepare("SELECT * FROM user_tbl WHERE user_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateUser($conn, $data, $id, $newPassword = null)
{
    if ($newPassword) {
        $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE user_tbl 
                SET first_name=:first_name, last_name=:last_name, middle_initial=:middle_initial, 
                    contact_number=:contact_number, street=:street, barangay=:barangay, city=:city, 
                    province=:province, password=:password 
                WHERE user_id=:id";
    } else {
        $sql = "UPDATE user_tbl 
                SET first_name=:first_name, last_name=:last_name, middle_initial=:middle_initial, 
                    contact_number=:contact_number, street=:street, barangay=:barangay, city=:city, 
                    province=:province 
                WHERE user_id=:id";
    }

    $stmt = $conn->prepare($sql);
    $data['id'] = $id;
    return $stmt->execute($data);
}

function getAllUsers()
{
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT * FROM user_tbl ORDER BY user_id ASC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function connectDBSafe()
{
    return connectDB();
}


function getAllPets()
{
    $conn = connectDBSafe();
    $stmt = $conn->prepare("SELECT * FROM pet_tbl ORDER BY pet_id DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
