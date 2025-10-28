<?php
require_once '../functions.php';
header('Content-Type: application/json');

try {
    $pets = getAllPets();
    echo json_encode(['success' => true, 'pets' => $pets]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
