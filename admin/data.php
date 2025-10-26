<?php
header('Content-Type: application/json');
require '../db_connect.php'; // ✅ connects to the same DB as adoption_request.php

// 🟩 Summary Counts
$summary = [
    'totalPets'     => (int) $conn->query("SELECT COUNT(*) FROM pet_tbl")->fetchColumn(),
    'totalAdopters' => (int) $conn->query("SELECT COUNT(*) FROM user_tbl WHERE role = 'Adopter'")->fetchColumn(),
    'pending'       => (int) $conn->query("SELECT COUNT(*) FROM adoption_request_tbl WHERE adoption_status = 'Pending'")->fetchColumn(),
    'approved'      => (int) $conn->query("SELECT COUNT(*) FROM adoption_request_tbl WHERE adoption_status = 'Approved'")->fetchColumn(),
    'dogs'          => (int) $conn->query("SELECT COUNT(*) FROM pet_tbl WHERE type = 'Dog'")->fetchColumn(),
    'cats'          => (int) $conn->query("SELECT COUNT(*) FROM pet_tbl WHERE type = 'Cat'")->fetchColumn(),
    'others'        => (int) $conn->query("SELECT COUNT(*) FROM pet_tbl WHERE type NOT IN ('Dog','Cat')")->fetchColumn(),
];

// 🟦 Bar Chart — Pets Adopted Per Month
$barStmt = $conn->query("
    SELECT MONTH(application_date) AS month, COUNT(*) AS total
    FROM adoption_request_tbl
    WHERE adoption_status = 'Approved'
    GROUP BY MONTH(application_date)
    ORDER BY MONTH(application_date)
");
$bar = $barStmt->fetchAll(PDO::FETCH_ASSOC);

// 🟧 Pie Chart — Pet Status Distribution
$pieStmt = $conn->query("
    SELECT pet_status AS status, COUNT(*) AS total
    FROM pet_tbl
    GROUP BY pet_status
");
$pie = $pieStmt->fetchAll(PDO::FETCH_ASSOC);

// 🟨 Line Chart — Adoption Requests Over Time
$lineStmt = $conn->query("
    SELECT DATE(application_date) AS date, COUNT(*) AS total
    FROM adoption_request_tbl
    GROUP BY DATE(application_date)
    ORDER BY DATE(application_date)
");
$line = $lineStmt->fetchAll(PDO::FETCH_ASSOC);

// 🟪 Table — Recent Activities
$tableStmt = $conn->query("
    SELECT p.pet_name, p.type, p.pet_status, u.first_name, u.last_name, a.application_date
    FROM adoption_request_tbl a
    JOIN pet_tbl p ON a.pet_id = p.pet_id
    JOIN user_tbl u ON a.user_id = u.user_id
    ORDER BY a.application_date DESC
    LIMIT 10
");
$table = $tableStmt->fetchAll(PDO::FETCH_ASSOC);

// Return as JSON
echo json_encode([
    'summary' => $summary,
    'bar'     => $bar,
    'pie'     => $pie,
    'line'    => $line,
    'table'   => $table
]);
?>
