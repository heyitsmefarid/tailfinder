<?php
session_start();
require '../db_connect.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login/index.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user's inquiries
$stmt = $conn->prepare("SELECT * FROM inquiry_tbl WHERE user_id=? ORDER BY date_sent DESC");
$stmt->execute([$user_id]);
$inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle new message submission
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_content'])){
    $content = trim($_POST['message_content']);
    if($content !== ''){
        $stmtInsert = $conn->prepare("INSERT INTO inquiry_tbl (content, admin_response, date_sent, user_id) VALUES (?, '', NOW(), ?)");
        $stmtInsert->execute([$content, $user_id]);
        header("Location: messages.php?sent=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Messages</title>
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
    <a href="adoption_request.php" class="sidebar-link"><i data-lucide="heart"></i> My Adoption Requests</a>
    <a href="browse.php" class="sidebar-link"><i data-lucide="paw-print"></i> Browse Pets</a>
    <a href="messages.php" class="sidebar-link active"><i data-lucide="message-circle"></i> Messages</a>
    <a href="user_profile.php" class="sidebar-link"><i data-lucide="user"></i>Profile</a>
  </nav>
  <a href="logout.php" class="sidebar-link logout-bottom"><i data-lucide="log-out"></i> Logout</a>

</aside>

<main class="content-area">
  <section class="dashboard-box">
    <h2 class="section-title">Messages to Admin</h2>

    <!-- NEW MESSAGE FORM -->
    <form id="messageForm" method="POST" class="mb-6 flex flex-col gap-3 animate-fade-in">
      <textarea name="message_content" id="message_content" rows="4" placeholder="Write your message to the admin..." 
                class="p-3 border rounded-lg shadow-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
      <button type="submit" class="self-end px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition transform hover:scale-105">Send Message</button>
    </form>

    <!-- MESSAGE LIST -->
    <div class="space-y-4">
      <?php if(empty($inquiries)): ?>
        <p class="text-gray-600">No messages yet. Send a message to the admin! 📨</p>
      <?php else: ?>
        <?php foreach($inquiries as $inq): ?>
        <div class="bg-white rounded-xl shadow-md p-4 animate-fade-in-up">
          <p class="text-gray-800 mb-2"><strong>You:</strong> <?= htmlspecialchars($inq['content']) ?></p>
          <p class="text-gray-500 text-sm mb-2"><em>Sent on: <?= htmlspecialchars($inq['date_sent']) ?></em></p>
          <?php if(!empty($inq['admin_response'])): ?>
            <div class="bg-blue-50 p-3 rounded-lg animate-fade-in">
              <p class="text-blue-700"><strong>Admin:</strong> <?= htmlspecialchars($inq['admin_response']) ?></p>
            </div>
          <?php else: ?>
            <div class="bg-gray-50 p-2 rounded-lg text-gray-400 text-sm">
              Awaiting admin response...
            </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>
</main>
</div>

<script src="messages.js"></script>
<script>lucide.createIcons();</script>
</body>
</html>
