<?php
require '../db_connect.php';
$conn = connectDB();

$stmt = $conn->query("
    SELECT 
        CONCAT(u.first_name, ' ', u.last_name) AS adopter_name,
        p.pet_name,
        p.type,
        a.adoption_status,
        a.application_date
    FROM adoption_request_tbl a
    JOIN user_tbl u ON a.user_id = u.user_id
    JOIN pet_tbl p ON a.pet_id = p.pet_id
    ORDER BY a.application_date DESC
");
$adoptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Adoptions Report</title>
<link rel="stylesheet" href="reports.css">
</head>
<body>
<a href="index.php" class="back-btn">← Back to Dashboard</a>
<h2>❤️ Adoptions Report</h2>
<div class="report-container">
<table>
  <thead>
    <tr>
      <th>Adopter Name</th>
      <th>Pet Name</th>
      <th>Type</th>
      <th>Status</th>
      <th>Application Date</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($adoptions as $a): ?>
    <tr>
      <td><?= htmlspecialchars($a['adopter_name']) ?></td>
      <td><?= htmlspecialchars($a['pet_name']) ?></td>
      <td><?= htmlspecialchars($a['type']) ?></td>
      <td><?= htmlspecialchars($a['adoption_status']) ?></td>
      <td><?= htmlspecialchars($a['application_date']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
<p class="footer-note">Generated on <?= date("F d, Y") ?></p>
</body>
</html>
