<?php
require_once '../functions.php';
header('Content-Type: application/json');

$conn = connectDB();

try {
    if (isset($_GET['id'])) {
        $user = getUserById($conn, intval($_GET['id']));
        echo json_encode(["status" => "success", "data" => $user]);
    } else {
        $stmt = $conn->query("SELECT * FROM user_tbl");
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
