<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Dashboard | Pet Adoption</title>
  <link rel="stylesheet" href="home.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-gray-100 text-gray-800">

  <!-- NAVBAR -->
  <header class="navbar">
    <h1 class="logo">🐾 Pet Adoption Portal</h1>
    <div class="nav-right">

      <div class="relative">
        

        <!-- NOTIFICATION DROPDOWN -->
        <div id="notifDropdown" class="notif-dropdown hidden">
          <p class="notif-title">Notifications</p>
          <ul>
            <li>New message from shelter</li>
            <li>Your adoption request is being reviewed</li>
            <li>3 new pets available</li>
          </ul>
        </div>
      </div>

    </div>
  </header>

  <div class="main-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
      <nav>
        <a href="home.html" class="sidebar-link active"><i data-lucide="home"></i> Dashboard</a>
        <a href="adoption_request.php" class="sidebar-link"><i data-lucide="heart"></i> My Adoption Requests</a>
        <a href="browse.php" class="sidebar-link"><i data-lucide="paw-print"></i> Browse Pets</a>
        <a href="messages.php" class="sidebar-link"><i data-lucide="message-circle"></i> Messages</a>
        <a href="user_profile.php" class="sidebar-link"><i data-lucide="user"></i> Profile</a>
      </nav>

      <a href="logout.php" class="sidebar-link logout-bottom"><i data-lucide="log-out"></i> Logout</a>

      </a>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content-area">
      <section class="dashboard-box">
        <h2 class="section-title">Dashboard Overview</h2>
        <p class="section-desc">Your adoption journey at a glance:</p>

        <div class="card-grid">
          <div class="dashboard-card">
            <i data-lucide="heart" class="icon"></i>
            <h3>Pending Requests</h3>
            <p>2 requests awaiting approval</p>
          </div>

          <div class="dashboard-card">
            <i data-lucide="paw-print" class="icon"></i>
            <h3>Adopted Pets</h3>
            <p>3 successful adoptions</p>
          </div>

          <div class="dashboard-card">
            <i data-lucide="message-circle" class="icon"></i>
            <h3>Unread Messages</h3>
            <p>4 new messages</p>
          </div>
        </div>
      </section>
    </main>

  </div>

  <script src="home.js"></script>
</body>
</html>
