<?php
require '../db_connect.php';
$conn = connectDB();

$stmt = $conn->query("
    SELECT 
        first_name, 
        last_name, 
        email, 
        contact_number, 
        street,
        barangay,
        city, 
        province, 
        role, 
        date_registered
    FROM user_tbl
    ORDER BY date_registered DESC
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Users Report</title>
<link rel="stylesheet" href="reports.css">
</head>
<body>
<a href="index.php" class="back-btn">← Back to Dashboard</a>
<h2>👥 Users Report</h2>
<div class="report-container">
<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
      <th>Contact</th>
      <th>Address</th>
      
      <th>Role</th>
      <th>Date Registered</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $u): ?>
    <tr>
      <td><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><?= htmlspecialchars($u['contact_number']) ?></td>
       <td><?= htmlspecialchars($u['street'] . ', ' . $u['barangay'] . ', ' . $u['city'] . ', ' . $u['province']) ?></td>
      <td><?= htmlspecialchars($u['role']) ?></td>
      <td><?= htmlspecialchars($u['date_registered']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
<p class="footer-note">Generated on <?= date("F d, Y") ?></p>
</body>
</html>
