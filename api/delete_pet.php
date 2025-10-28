<?php
require '../db_connect.php';
$conn = connectDB();

if (!isset($_GET['pet_id'])) {
    header("Location: ../admin/pet.php");
    exit;
}

$pet_id = $_GET['pet_id'];

$stmt = $conn->prepare("SELECT image FROM pet_tbl WHERE pet_id = ?");
$stmt->execute([$pet_id]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

if ($pet) {
    if ($pet['image'] && file_exists("../uploads/" . $pet['image'])) {
        unlink("../uploads/" . $pet['image']);
    }

    $stmt = $conn->prepare("DELETE FROM pet_tbl WHERE pet_id = ?");
    $stmt->execute([$pet_id]);
}

header("Location: ../admin/pet.php?status=deleted");
exit;
?>
