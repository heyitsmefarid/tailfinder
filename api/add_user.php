<?php
require_once 'db_connect.php';
require_once 'functions.php';

/**
 * Add a new user to the database.
 * @param array $user_data - associative array of user info
 * @return bool|string - true if successful, or error message string if failed
 */
function addUser($user_data) {
    $conn = connectDB();

    try {
        $stmt = $conn->prepare("INSERT INTO user_tbl 
            (first_name, last_name, middle_initial, contact_number, street, barangay, city, province, email, password, role, date_registered, status)
            VALUES (:first_name, :last_name, :middle_initial, :contact_number, :street, :barangay, :city, :province, :email, :password, :role, :date_registered, :status)");

        $stmt->execute([
            ':first_name' => $user_data['first_name'],
            ':last_name' => $user_data['last_name'],
            ':middle_initial' => $user_data['middle_initial'],
            ':contact_number' => $user_data['contact_number'],
            ':street' => $user_data['street'],
            ':barangay' => $user_data['barangay'],
            ':city' => $user_data['city'],
            ':province' => $user_data['province'],
            ':email' => $user_data['email'],
            ':password' => $user_data['password'],
            ':role' => $user_data['role'],
            ':date_registered' => $user_data['date_registered'],
            ':status' => $user_data['status'] ?? 'verified'
        ]);

        return true;

    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
?>
