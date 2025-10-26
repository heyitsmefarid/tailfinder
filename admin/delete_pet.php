<?php
require '../db_connect.php';

// Check if pet_id is provided
if (!isset($_GET['pet_id'])) {
    echo "Pet ID is missing. <a href='pets.php'>Go back to Pets List</a>";
    exit;
}

$pet_id = $_GET['pet_id'];

// Fetch pet info to delete its image
$stmt = $conn->prepare("SELECT image FROM pet_tbl WHERE pet_id = ?");
$stmt->execute([$pet_id]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    echo "Pet not found. <a href='pets.php'>Go back to Pets List</a>";
    exit;
}

// Delete image file if exists
if ($pet['image'] && file_exists('../uploads/' . $pet['image'])) {
    unlink('../uploads/' . $pet['image']);
}

// Delete pet record
$stmt = $conn->prepare("DELETE FROM pet_tbl WHERE pet_id = ?");
$stmt->execute([$pet_id]);

// Redirect back to pets.php
header("Location: pet.php");
exit;
?>
