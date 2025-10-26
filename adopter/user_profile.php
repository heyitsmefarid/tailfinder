<?php
session_start();
require '../db_connect.php';

$error = '';
$success = '';
$user = [];

// Step 1: Handle verification form (email + password)
if (isset($_POST['verify'])) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $conn->prepare("SELECT * FROM user_tbl WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Credentials are correct, store user_id in session
        $_SESSION['user_id'] = $user['user_id'];
    } else {
        $error = "Invalid email or password!";
        $user = [];
    }
}

// Step 2: If user_id is in session, fetch their data
if (isset($_SESSION['user_id']) && empty($_POST['verify'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM user_tbl WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
}

// Step 3: Handle profile update form
if (isset($_POST['update']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $middle_initial = $_POST['middle_initial'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';
    $street = $_POST['street'] ?? '';
    $barangay = $_POST['barangay'] ?? '';
    $city = $_POST['city'] ?? '';
    $province = $_POST['province'] ?? '';
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (!empty($new_password)) {
        if ($new_password !== $confirm_password) {
            $error = "Passwords do not match!";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE user_tbl SET first_name=?, last_name=?, middle_initial=?, contact_number=?, street=?, barangay=?, city=?, province=?, password=? WHERE user_id=?");
            $stmt->execute([$first_name, $last_name, $middle_initial, $contact_number, $street, $barangay, $city, $province, $hashed_password, $user_id]);
            $success = "Profile and password updated successfully!";
        }
    } else {
        $stmt = $conn->prepare("UPDATE user_tbl SET first_name=?, last_name=?, middle_initial=?, contact_number=?, street=?, barangay=?, city=?, province=? WHERE user_id=?");
        $stmt->execute([$first_name, $last_name, $middle_initial, $contact_number, $street, $barangay, $city, $province, $user_id]);
        $success = "Profile updated successfully!";
    }

    // Refresh data
    $stmt = $conn->prepare("SELECT * FROM user_tbl WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Profile</title>
<link rel="stylesheet" href="browse.css">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-100 text-gray-800">

<header class="navbar bg-blue-600 p-4 flex justify-between items-center">
  <h1 class="text-white font-semibold text-lg">🐾 Pet Adoption Portal</h1>
  <div class="flex items-center space-x-4">
  </div>
</header>

<div class="main-container">
  <aside class="sidebar">
  <nav>
    <a href="home.php" class="sidebar-link"><i data-lucide="home"></i> Dashboard</a>
        <a href="adoption_request.php" class="sidebar-link"><i data-lucide="heart"></i> My Adoption Requests</a>
        <a href="browse.php" class="sidebar-link"><i data-lucide="paw-print"></i> Browse Pets</a>
        <a href="messages.php" class="sidebar-link"><i data-lucide="message-circle"></i> Messages</a>
        <a href="user_profile.php" class="sidebar-link active" ><i data-lucide="user"></i> Profile</a>
        <a href="#" class="sidebar-link"> </a>
    <a href="#" class="sidebar-link"> </a>
    <a href="#" class="sidebar-link"></a>
           <a href="#" class="sidebar-link"> </a>
    <a href="#" class="sidebar-link"> </a>
    <a href="#" class="sidebar-link"></a>
  </nav>       <a href="#" class="sidebar-link"> </a>
    <a href="#" class="sidebar-link"> </a>
    <a href="#" class="sidebar-link"></a>
    <a href="#" class="sidebar-link"> </a>
    <a href="#" class="sidebar-link"></a>
           <a href="#" class="sidebar-link"> </a>

  </nav>
  </nav>
 <a href="logout.php" class="sidebar-link logout-bottom"><i data-lucide="log-out"></i> Logout</a>

</aside>

  <main class="flex-1 p-6">
    <h2 class="text-2xl font-bold mb-4">My Profile</h2>

    <?php if($error): ?>
        <div class="bg-red-200 text-red-800 p-3 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>
    <?php if($success): ?>
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4"><?= $success ?></div>
    <?php endif; ?>

    <?php if(empty($user)): ?>
      <!-- Verification form -->
      <div class="bg-white p-6 rounded shadow w-full max-w-md mx-auto">
        <form method="POST">
          <h3 class="text-lg font-semibold mb-4 text-blue-600">Verify Your Account</h3>
          <div class="mb-4">
            <label>Email</label>
            <input type="email" name="email" class="w-full border border-gray-300 rounded p-2" required>
          </div>
          <div class="mb-4">
            <label>Password</label>
            <input type="password" name="password" class="w-full border border-gray-300 rounded p-2" required>
          </div>
          <div class="text-right">
            <button type="submit" name="verify" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Verify</button>
          </div>
        </form>
      </div>
    <?php else: ?>
      <!-- Profile update form -->
      <div class="bg-white p-6 rounded shadow w-full max-w-2xl">
        <form method="POST">
          <input type="hidden" name="update" value="1">
          <div class="mb-4">
            <label>Email (Not Editable)</label>
            <input type="email" value="<?= htmlspecialchars($user['email']); ?>" class="w-full border border-gray-300 rounded p-2 bg-gray-100" readonly>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
              <label>First Name</label>
              <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>" class="w-full border border-gray-300 rounded p-2" required>
            </div>
            <div>
              <label>Last Name</label>
              <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']); ?>" class="w-full border border-gray-300 rounded p-2" required>
            </div>
            <div>
              <label>Middle Initial</label>
              <input type="text" name="middle_initial" value="<?= htmlspecialchars($user['middle_initial']); ?>" maxlength="1" class="w-full border border-gray-300 rounded p-2">
            </div>
          </div>

          <div class="mb-4">
            <label>Contact Number</label>
            <input type="text" name="contact_number" value="<?= htmlspecialchars($user['contact_number']); ?>" class="w-full border border-gray-300 rounded p-2" required>
          </div>

          <div class="mb-4">
            <label>Street</label>
            <input type="text" name="street" value="<?= htmlspecialchars($user['street']); ?>" class="w-full border border-gray-300 rounded p-2" required>
          </div>

          <div class="mb-4">
            <label>Barangay</label>
            <input type="text" name="barangay" value="<?= htmlspecialchars($user['barangay']); ?>" class="w-full border border-gray-300 rounded p-2" required>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label>City</label>
              <input type="text" name="city" value="<?= htmlspecialchars($user['city']); ?>" class="w-full border border-gray-300 rounded p-2" required>
            </div>
            <div>
              <label>Province</label>
              <input type="text" name="province" value="<?= htmlspecialchars($user['province']); ?>" class="w-full border border-gray-300 rounded p-2" required>
            </div>
          </div>

          <hr class="my-4">
          <h3 class="text-lg font-semibold mb-2 text-blue-600">Change Password (Optional)</h3>
          <div class="mb-4">
            <label>New Password</label>
            <input type="password" name="new_password" class="w-full border border-gray-300 rounded p-2" placeholder="Leave empty to keep current password">
          </div>
          <div class="mb-4">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" class="w-full border border-gray-300 rounded p-2">
          </div>

          <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">
              <i data-lucide="save" class="inline mr-1"></i> Save Changes
            </button>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </main>
</div>

<script>
lucide.createIcons();
</script>
</body>
</html>
