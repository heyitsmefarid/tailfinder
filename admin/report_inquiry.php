<?php
require '../db_connect.php';
$conn = connectDB();

$stmt = $conn->query("
    SELECT 
        CONCAT(u.first_name, ' ', u.last_name) AS user_name,
        i.content,
        i.admin_response,
        i.date_sent
    FROM inquiry_tbl i
    JOIN user_tbl u ON i.user_id = u.user_id
    ORDER BY i.date_sent DESC
");
$inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Inquiry Report</title>
<link rel="stylesheet" href="reports.css">
</head>
<body>
<a href="index.php" class="back-btn">← Back to Dashboard</a>
<h2>💬 Inquiry Report</h2>
<div class="report-container">
<table>
  <thead>
    <tr>
      <th>User</th>
      <th>Inquiry</th>
      <th>Admin Response</th>
      <th>Date Sent</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($inquiries as $i): ?>
    <tr>
      <td><?= htmlspecialchars($i['user_name']) ?></td>
      <td><?= htmlspecialchars($i['content']) ?></td>
      <td><?= htmlspecialchars($i['admin_response']) ?></td>
      <td><?= htmlspecialchars($i['date_sent']) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
<p class="footer-note">Generated on <?= date("F d, Y") ?></p>
</body>
</html>
