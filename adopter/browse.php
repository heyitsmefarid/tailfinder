<?php
require '../db_connect.php';

// Initialize filters
$search_query = $_GET['search'] ?? '';
$type_filter = $_GET['type'] ?? '';
$breed_filter = $_GET['breed'] ?? '';
$age_filter = $_GET['age'] ?? '';

// Fetch distinct types and breeds for dropdowns
$type_stmt = $conn->prepare("SELECT DISTINCT type FROM pet_tbl WHERE pet_status='Available'");
$type_stmt->execute();
$types = $type_stmt->fetchAll(PDO::FETCH_COLUMN);

$breed_stmt = $conn->prepare("SELECT DISTINCT breed FROM pet_tbl WHERE pet_status='Available'");
$breed_stmt->execute();
$breeds = $breed_stmt->fetchAll(PDO::FETCH_COLUMN);

// Build main query
$query = "SELECT * FROM pet_tbl WHERE pet_status='Available'";
$params = [];

if ($search_query) {
    $query .= " AND (pet_name LIKE ? OR breed LIKE ? OR type LIKE ? OR description LIKE ? OR age LIKE ?)";
    $like = "%$search_query%";
    array_push($params, $like, $like, $like, $like, $like);
}

if ($type_filter) {
    $query .= " AND type=?";
    $params[] = $type_filter;
}

if ($breed_filter) {
    $query .= " AND breed=?";
    $params[] = $breed_filter;
}

if ($age_filter) {
    $query .= " AND age=?";
    $params[] = $age_filter;
}

$query .= " ORDER BY pet_id DESC";

$stmt = $conn->prepare($query);
$stmt->execute($params);
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch logged-in user info
session_start();
$user = [];
if(isset($_SESSION['user_id'])){
    $stmtUser = $conn->prepare("SELECT * FROM user_tbl WHERE user_id = ?");
    $stmtUser->execute([$_SESSION['user_id']]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Browse Pets</title>
<link rel="stylesheet" href="browse.css">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 text-gray-800">

<!-- NAVBAR -->
<header class="navbar">
  <h1 class="logo text-white font-semibold text-lg">🐾 Pet Adoption Portal</h1>
</header>

<div class="main-container">

<!-- SIDEBAR -->
<aside class="sidebar">
  <nav>
    <a href="home.php" class="sidebar-link"><i data-lucide="home"></i> Dashboard</a>
    <a href="adoption_request.php" class="sidebar-link"><i data-lucide="heart"></i> My Adoption Requests</a>
    <a href="browse.php" class="sidebar-link active"><i data-lucide="paw-print"></i> Browse Pets</a>
    <a href="messages.php" class="sidebar-link"><i data-lucide="message-circle"></i> Messages</a>
    <a href="user_profile.php" class="sidebar-link"><i data-lucide="user"></i>Profile</a>
  </nav>
  <a href="../login/index.php" class="sidebar-link logout-bottom"><i data-lucide="log-out"></i> Logout</a>
</aside>

<!-- MAIN CONTENT -->
<main class="content-area">
  <section class="dashboard-box">
    <h2 class="section-title">Browse Pets for Adoption</h2>

    <!-- SEARCH FORM -->
    <form method="GET" class="flex gap-2 mb-3">
      <input type="text" name="search" placeholder="Search name, breed, type, age, description..." 
             value="<?= htmlspecialchars($search_query) ?>" 
             class="search-input p-2 rounded border flex-1">
      <button type="submit" class="px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600 text-sm">Search</button>
    </form>

    <!-- FILTER FORM -->
    <form method="GET" class="flex gap-2 mb-5 items-center">
      <select name="type" class="p-2 rounded border text-sm">
        <option value="">All Types</option>
        <?php
        $all_types = array_merge(['Others'], $types);
        foreach ($all_types as $type):
        ?>
          <option value="<?= htmlspecialchars($type) ?>" <?= $type_filter==$type?'selected':'' ?>><?= htmlspecialchars($type) ?></option>
        <?php endforeach; ?>
      </select>

      <select name="breed" class="p-2 rounded border text-sm">
        <option value="">All Breeds</option>
        <?php foreach ($breeds as $breed): ?>
          <option value="<?= htmlspecialchars($breed) ?>" <?= $breed_filter==$breed?'selected':'' ?>><?= htmlspecialchars($breed) ?></option>
        <?php endforeach; ?>
      </select>

      <input type="number" name="age" placeholder="Age" 
             value="<?= htmlspecialchars($age_filter) ?>" 
             class="p-2 rounded border text-sm" min="0">

      <button type="submit" class="px-3 py-1 rounded bg-green-500 text-white hover:bg-green-600 text-sm">Filter</button>
    </form>

    <div class="pet-grid">
      <?php foreach ($pets as $pet): ?>
      <div class="pet-card">
        <img src="../uploads/<?php echo htmlspecialchars($pet['image']); ?>" 
             alt="Pet Image" 
             class="pet-img cursor-pointer" 
             data-src="../uploads/<?php echo htmlspecialchars($pet['image']); ?>">

        <div class="pet-info">
          <h3><?php echo htmlspecialchars($pet['pet_name']); ?></h3>
          <p><strong>Type:</strong> <?php echo htmlspecialchars($pet['type']); ?></p>
          <p><strong>Breed:</strong> <?php echo htmlspecialchars($pet['breed']); ?></p>
          <p><strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?> y/old</p>
          <p><?php echo htmlspecialchars($pet['description']); ?></p>

          <button type="button" class="adopt-btn bg-blue-600 text-white px-3 py-1 rounded mt-2" 
                  data-pet='<?= json_encode($pet) ?>'>Adopt</button>
        </div>
      </div>
      <?php endforeach; ?>

      <?php if (empty($pets)): ?>
        <p>No pets available right now. Check back soon! 🐾</p>
      <?php endif; ?>
    </div>
  </section>
</main>
</div>

<!-- IMAGE MODAL -->
<div id="imgModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center hidden z-50">
  <span id="closeModal" class="absolute top-5 right-8 text-white text-3xl cursor-pointer">&times;</span>
  <span class="modal-nav prev">&#10094;</span>
  <img id="modalImg" src="" alt="Large Pet Image" class="max-h-[90%] max-w-[90%] rounded-lg shadow-lg" />
  <span class="modal-nav next">&#10095;</span>
</div>

<!-- ADOPTION MODAL -->
<div id="adoptModal" class="fixed inset-0 hidden items-center justify-center z-50 bg-black bg-opacity-60 backdrop-blur-sm">
    <div id="adoptModalContent" class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative transform -translate-y-12 scale-95 opacity-0 transition-all duration-300 ease-out">
        <span id="closeAdoptModal" class="absolute top-4 right-4 cursor-pointer text-gray-500 text-2xl hover:text-red-500">&times;</span>
        <h2 class="text-3xl font-bold text-center text-blue-600 mb-6">Confirm Adoption</h2>

        <div class="flex gap-4 mb-6 items-center">
            <img id="modalPetImg" class="w-36 h-36 object-cover rounded-lg shadow-lg border" src="" alt="Pet Image">
            <div class="space-y-1 text-gray-700">
                <p><strong>Name:</strong> <span id="modalPetName"></span></p>
                <p><strong>Type:</strong> <span id="modalPetType"></span></p>
                <p><strong>Breed:</strong> <span id="modalPetBreed"></span></p>
                <p><strong>Age:</strong> <span id="modalPetAge"></span> y/old</p>
            </div>
        </div>

        <hr class="border-gray-300 mb-6">

        <h3 class="font-semibold text-lg text-gray-700 mb-2">Your Information</h3>
        <div class="space-y-1 mb-6 text-gray-800">
            <p id="userFullName"><?= htmlspecialchars($user['first_name'] ?? '') ?> <?= htmlspecialchars($user['last_name'] ?? '') ?></p>
            <p id="userAddress"><?= htmlspecialchars($user['street'] ?? '') ?>, <?= htmlspecialchars($user['barangay'] ?? '') ?>, <?= htmlspecialchars($user['city'] ?? '') ?>, <?= htmlspecialchars($user['province'] ?? '') ?></p>
            <p id="userEmail"><?= htmlspecialchars($user['email'] ?? '') ?></p>
        </div>

        <form id="adoptForm" class="flex justify-end gap-3">
            <input type="hidden" name="pet_id" id="formPetId" value="">
            <button type="button" id="cancelAdoptBtn" class="px-4 py-2 rounded border hover:bg-gray-200 transition">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Confirm Adoption</button>
        </form>
    </div>
</div>

<script src="browse.js"></script>
</body>
</html>
