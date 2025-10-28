<?php
session_start();
require '../db_connect.php';
$conn = connectDB();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please log in first.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $pet_id = $_POST['pet_id'] ?? '';

    if (empty($pet_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Pet ID missing.']);
        exit;
    }

    // ✅ Check if the pet is available
    $stmt = $conn->prepare("SELECT * FROM pet_tbl WHERE pet_id = ? AND pet_status = 'Available'");
    $stmt->execute([$pet_id]);
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pet) {
        echo json_encode(['status' => 'error', 'message' => 'Pet not available.']);
        exit;
    }

    // ✅ Check if user already sent a pending adoption request for this pet
    $checkStmt = $conn->prepare("
        SELECT * 
        FROM adoption_request_tbl 
        WHERE user_id = ? AND pet_id = ? AND adoption_status = 'Pending'
    ");
    $checkStmt->execute([$user_id, $pet_id]);
    $existingRequest = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingRequest) {
        echo json_encode([
            'status' => 'exists',
            'message' => 'You already have a pending adoption request for this pet.'
        ]);
        exit;
    }

    // ✅ Insert new adoption request
    $stmtInsert = $conn->prepare("
        INSERT INTO adoption_request_tbl (user_id, pet_id, application_date, adoption_status)
        VALUES (?, ?, NOW(), 'Pending')
    ");

    try {
        $stmtInsert->execute([$user_id, $pet_id]);
        echo json_encode(['status' => 'success', 'message' => 'Your adoption request has been sent!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
