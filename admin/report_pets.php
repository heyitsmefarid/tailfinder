<?php
require '../db_connect.php';
$conn = connectDB();

$stmt = $conn->query("
    SELECT 
        pet_name,
        type,
        breed,
        age,
        CASE WHEN gender = 1 THEN 'Male' WHEN gender = 2 THEN 'Female' ELSE 'Unknown' END AS gender,
        pet_status
    FROM pet_tbl
    ORDER BY pet_id ASC
");
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pets Report</title>
<link rel="stylesheet" href="reports.css">
</head>
<body>
<a href="index.php" class="back-btn">← Back to Dashboard</a>
<h2>🐾 Pets Report</h2>
<div class="report-container">
<table>
  <thead>
    <tr>
      <th>Pet Name</th>
      <th>Type</th>
      <th>Breed</th>
      <th>Age</th>
      <th>Gender</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($pets as $pet): ?>
    <tr>
      <td><?= htmlspecialchars($pet['pet_name']) ?></td>
      <td><?= htmlspecialchars($pet['type']) ?></td>
      <td><?= htmlspecialchars($pet['breed']) ?></td>
      <td><?= htmlspecialchars($pet['age']) ?></td>
      <td><?= htmlspecialchars($pet['gender']) ?></td>
      <td><?= htmlspecialchars($pet['pet_status']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
<p class="footer-note">Generated on <?= date("F d, Y") ?></p>
</body>
</html>
