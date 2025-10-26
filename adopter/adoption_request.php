<?php
require '../db_connect.php';
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login/index.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user's adoption requests
$stmt = $conn->prepare("
    SELECT r.*, p.pet_name, p.type, p.breed, p.age, p.image
    FROM adoption_request_tbl r
    JOIN pet_tbl p ON r.pet_id = p.pet_id
    WHERE r.user_id = ?
    ORDER BY r.application_date DESC
");
$stmt->execute([$user_id]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Adoption Requests</title>
<link rel="stylesheet" href="browse.css">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<header class="navbar">
  <h1 class="logo text-white font-semibold text-lg">🐾 Pet Adoption Portal</h1>
</header>

<div class="main-container">

<aside class="sidebar">
  <nav>
    <a href="home.php" class="sidebar-link"><i data-lucide="home"></i> Dashboard</a>
    <a href="requests.php" class="sidebar-link active"><i data-lucide="heart"></i> My Adoption Requests</a>
    <a href="browse.php" class="sidebar-link"><i data-lucide="paw-print"></i> Browse Pets</a>
    <a href="messages.php" class="sidebar-link"><i data-lucide="message-circle"></i> Messages</a>
    <a href="user_profile.php" class="sidebar-link"><i data-lucide="user"></i>Profile</a>
  </nav>
  <a href="../login/index.php" class="sidebar-link logout-bottom"><i data-lucide="log-out"></i> Logout</a>
</aside>

<main class="content-area">
  <section class="dashboard-box">
    <h2 class="section-title">My Adoption Requests</h2>

    <?php if(empty($requests)): ?>
      <p>You have no adoption requests yet. Browse pets to adopt! 🐾</p>
    <?php else: ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
        <?php foreach($requests as $req): ?>
        <div class="bg-white shadow-md rounded-lg p-4 flex flex-col items-center">
          <img src="../uploads/<?= htmlspecialchars($req['image']) ?>" alt="Pet Image" class="w-32 h-32 object-cover rounded-lg mb-3">
          <h3 class="font-bold text-lg"><?= htmlspecialchars($req['pet_name']) ?></h3>
          <p><strong>Type:</strong> <?= htmlspecialchars($req['type']) ?></p>
          <p><strong>Breed:</strong> <?= htmlspecialchars($req['breed']) ?></p>
          <p><strong>Age:</strong> <?= htmlspecialchars($req['age']) ?> y/old</p>
          <p><strong>Status:</strong> 
            <span class="<?= $req['adoption_status']=='Pending' ? 'text-yellow-500' : ($req['adoption_status']=='Approved' ? 'text-green-500' : 'text-red-500') ?>">
              <?= htmlspecialchars($req['adoption_status']) ?>
            </span>
          </p>
          <p><strong>Applied on:</strong> <?= htmlspecialchars($req['application_date']) ?></p>

          <?php if($req['adoption_status']=='Pending'): ?>
            <form class="mt-3 cancel-form" method="POST" action="cancel_request.php">
              <input type="hidden" name="request_id" value="<?= $req['request_id'] ?>">
              <button type="button" class="cancel-btn px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">Cancel Request</button>
            </form>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</main>
</div>

<script src="request.js"></script>
<script>lucide.createIcons();</script>
</body>
</html>
