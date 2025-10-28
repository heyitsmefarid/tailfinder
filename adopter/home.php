<?php
session_start();
include '../db_connect.php';
$conn = connectDB();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT first_name FROM user_tbl WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_name = $user ? $user['first_name'] : 'User';

$total_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM adoption_request_tbl WHERE user_id = ?");
$total_stmt->execute([$user_id]);
$total = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'];

$pending_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM adoption_request_tbl WHERE user_id = ? AND adoption_status = 'Pending'");
$pending_stmt->execute([$user_id]);
$pending = $pending_stmt->fetch(PDO::FETCH_ASSOC)['total'];

$approved_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM adoption_request_tbl WHERE user_id = ? AND adoption_status = 'Approved'");
$approved_stmt->execute([$user_id]);
$approved = $approved_stmt->fetch(PDO::FETCH_ASSOC)['total'];

$rejected_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM adoption_request_tbl WHERE user_id = ? AND adoption_status = 'Rejected'");
$rejected_stmt->execute([$user_id]);
$rejected = $rejected_stmt->fetch(PDO::FETCH_ASSOC)['total'];

$pets_stmt = $conn->prepare("SELECT * FROM pet_tbl WHERE pet_status = 'Available' ORDER BY RAND() LIMIT 10");
$pets_stmt->execute();
$pets = $pets_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Dashboard | Pet Adoption</title>

  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <style>
    body {
      background: linear-gradient(to bottom right, #e0f2fe, #f0f9ff);
      font-family: 'Poppins', sans-serif;
      color: #0f172a;
      overflow-x: hidden;
    }

    header.navbar {
      background: linear-gradient(90deg, #1e40af, #2563eb);
      color: #fff;
      padding: 16px 28px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 8px 20px rgba(30, 64, 175, 0.25);
      position: sticky;
      top: 0;
      z-index: 50;
      animation: fadeDown 0.6s ease-in-out;
    }

    @keyframes fadeDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* --- Sidebar --- */
    .sidebar {
      width: 260px;
      background: linear-gradient(180deg, #1e3a8a, #1d4ed8);
      color: #fff;
      padding: 20px 14px;
      box-shadow: 2px 0 16px rgba(30, 58, 138, 0.2);
      flex-shrink: 0;
      opacity: 0.95;
    }

    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      border-radius: 10px;
      color: #f1f5f9;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.2s ease;
      font-size: 0.95rem;
    }

    .sidebar-link:hover {
      background: rgba(255, 255, 255, 0.15);
      transform: translateX(6px);
    }

    .sidebar-link.active {
      background: rgba(255, 255, 255, 0.25);
      font-weight: 600;
    }

    .logout-bottom {
      margin-top: 1rem;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: #fee2e2;
      font-weight: 600;
    }

    .main-container {
      display: flex;
      min-height: calc(100vh - 64px);
    }

    .content-area {
      flex: 1;
      padding: 40px;
      animation: fadeIn 1s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
.dashboard-card {
  background: linear-gradient(135deg, #d7e7f7ff 0%, #a1c7eeff 100%);
  border-radius: 18px;
  box-shadow: 0 6px 20px rgba(30, 41, 59, 0.08);
  padding: 24px 20px;
  text-align: center;
  position: relative;
  overflow: hidden;
  transition: all 0.35s ease;
  isolation: isolate; 
}

.dashboard-card::before {
  content: "";
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at top left, rgba(59,130,246,0.15), transparent 70%);
  opacity: 0;
  transition: opacity 0.4s ease;
  z-index: -1;
}

.dashboard-card:hover::before {
  opacity: 1;
}

.dashboard-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 16px 35px rgba(30, 41, 59, 0.18);
}

.dashboard-card i {
  display: inline-block;
  background: linear-gradient(135deg, #3b82f6, #1e40af);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  transition: transform 0.35s ease, filter 0.35s ease;
}

.dashboard-card:hover i {
  transform: scale(1.25) rotate(8deg);
  filter: drop-shadow(0 3px 6px rgba(59,130,246,0.4));
}

.dashboard-card h3 {
  font-weight: 600;
  margin-top: 8px;
  color: #1e3a8a;
}

.dashboard-card p {
  font-size: 1.6rem;
  margin-top: 4px;
  font-weight: 700;
}

.dashboard-card:hover p {
  color: #2563eb;
}
.dashboard-card i {
  background: linear-gradient(135deg, var(--accent), #1e40af);
}
.dashboard-card:hover i {
  filter: drop-shadow(0 3px 6px var(--accent));
}
.dashboard-card:hover p {
  color: var(--accent);
}


    /* Carousel */
    .carousel {
      display: flex;
      overflow: hidden;
      position: relative;
      margin-top: 1rem;
    }

    .carousel-track {
      display: flex;
      animation: scrollInfinite 60s linear infinite;
    }

    .carousel-track:hover {
      animation-play-state: paused;
    }

    @keyframes scrollInfinite {
      0% {
        transform: translateX(0);
      }

      100% {
        transform: translateX(-50%);
      }
    }

    .carousel article {
      background: white;
      border-radius: 14px;
      box-shadow: 0 6px 24px rgba(2, 6, 23, 0.06);
      min-width: 220px;
      margin-right: 20px;
      text-align: center;
      flex: 0 0 auto;
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .carousel article:hover {
      transform: scale(1.05);
      box-shadow: 0 10px 40px rgba(30, 58, 138, 0.15);
    }

    .carousel article img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-top-left-radius: 14px;
      border-top-right-radius: 14px;
    }
  </style>
</head>

<body>
  <header class="navbar">
    <h1 class="logo">🐾 Pet Adoption Portal</h1>
    <p class="text-sm font-medium">Hi, <?= htmlspecialchars($user_name) ?> 👋</p>
  </header>

  <div class="main-container">
    <aside class="sidebar">
      <nav>
        <a href="home.php" class="sidebar-link active"><i data-lucide="home"></i> Dashboard</a>
        <a href="adoption_request.php" class="sidebar-link"><i data-lucide="heart"></i> My Requests</a>
        <a href="browse.php" class="sidebar-link"><i data-lucide="paw-print"></i> Browse Pets</a>
        <a href="messages.php" class="sidebar-link"><i data-lucide="message-circle"></i> Messages</a>
        <a href="user_profile.php" class="sidebar-link"><i data-lucide="user"></i> Profile</a>
      </nav>
      <a href="logout.php" class="sidebar-link logout-bottom"><i data-lucide="log-out"></i> Logout</a>
    </aside>

    <main class="content-area">
      <section class="welcome-box mb-6">
        <h2 class="text-3xl font-bold text-blue-700">Welcome back, <?= htmlspecialchars($user_name) ?>!</h2>
        <p class="text-gray-500 mt-1">Here’s your adoption summary and some adorable pets you might love ❤️</p>
      </section>

      <!-- 🟦 Summary cards (2 by 2 layout) -->
      <!-- 🟦 Summary cards (2 by 2 layout) -->
      <!-- 🟦 Summary cards (2 by 2 layout) -->
      <section class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10 max-w-4xl">
        <div class="dashboard-card">
          <i data-lucide="folder" class="mx-auto text-blue-800 w-6 h-6 mb-1"></i>
          <h3 class="font-semibold text-lg">Total Requests</h3>
          <p class="text-2xl font-bold text-blue-600"><?= $total ?></p>
        </div>

        <div class="dashboard-card">
          <i data-lucide="clock" class="mx-auto text-yellow-600 w-6 h-6 mb-1"></i>
          <h3 class="font-semibold text-lg">Pending</h3>
          <p class="text-2xl font-bold text-yellow-500"><?= $pending ?></p>
        </div>

        <div class="dashboard-card">
          <i data-lucide="check-circle" class="mx-auto text-green-600 w-6 h-6 mb-1"></i>
          <h3 class="font-semibold text-lg">Approved</h3>
          <p class="text-2xl font-bold text-green-600"><?= $approved ?></p>
        </div>

        <div class="dashboard-card">
          <i data-lucide="x-circle" class="mx-auto text-red-600 w-6 h-6 mb-1"></i>
          <h3 class="font-semibold text-lg">Rejected</h3>
          <p class="text-2xl font-bold text-red-600"><?= $rejected ?></p>
        </div>
      </section>


      <!-- 🐾 Carousel Section -->
      <section>
        <h2 class="text-2xl font-bold text-blue-700 mb-3">🐕 Recommended Pets for You</h2>
        <div class="carousel">
          <div class="carousel-track">
            <?php foreach ($pets as $pet): ?>
              <article>
                <img src="../uploads/<?= htmlspecialchars($pet['image']) ?>" alt="<?= htmlspecialchars($pet['pet_name']) ?>">
                <h2><?= htmlspecialchars($pet['pet_name']) ?></h2>
                <p><strong>Breed:</strong> <?= htmlspecialchars($pet['breed']) ?></p>
                <p><strong>Age:</strong> <?= htmlspecialchars($pet['age']) ?></p>
                <p><strong>Type:</strong> <?= htmlspecialchars($pet['type']) ?></p>
              </article>
            <?php endforeach; ?>
            <?php foreach ($pets as $pet): ?>
              <article>
                <img src="../uploads/<?= htmlspecialchars($pet['image']) ?>" alt="<?= htmlspecialchars($pet['pet_name']) ?>">
                <h2><?= htmlspecialchars($pet['pet_name']) ?></h2>
                <p><strong>Breed:</strong> <?= htmlspecialchars($pet['breed']) ?></p>
                <p><strong>Age:</strong> <?= htmlspecialchars($pet['age']) ?></p>
                <p><strong>Type:</strong> <?= htmlspecialchars($pet['type']) ?></p>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      if (window.lucide) lucide.createIcons();
    });
  </script>

</body>

</html>