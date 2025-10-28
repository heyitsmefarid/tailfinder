<?php
require '../db_connect.php';
$conn = connectDB();

if (!isset($_GET['pet_id'])) {
  header("Location: ../admin/pet.php");
  exit;
}

$pet_id = $_GET['pet_id'];

$stmt = $conn->prepare("SELECT * FROM pet_tbl WHERE pet_id = ?");
$stmt->execute([$pet_id]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$pet) {
  die("Pet not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['pet_name'];
  $breed = $_POST['breed'];
  $age = $_POST['age'];
  $gender = $_POST['gender'];
  $type = $_POST['type'];
  $description = $_POST['description'];
  $status = $_POST['pet_status'];

  $img_name = $pet['image'];
  if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    if (in_array($ext, $allowed)) {

      if ($pet['image'] && file_exists("../uploads/" . $pet['image'])) {
        unlink("../uploads/" . $pet['image']);
      }
      $img_name = time() . '.' . $ext;
      move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $img_name);
    }
  }

  $stmt = $conn->prepare("UPDATE pet_tbl SET pet_name=?, breed=?, age=?, gender=?, type=?, image=?, description=?, pet_status=? WHERE pet_id=?");
  $stmt->execute([$name, $breed, $age, $gender, $type, $img_name, $description, $status, $pet_id]);

  header("Location: ../admin/pet.php?status=edited");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Edit Pet</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    @keyframes fadeSlideUp {
      0% {
        opacity: 0;
        transform: translateY(40px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .fade-in {
      animation: fadeSlideUp 0.7s ease-out;
    }
    body {
      background: linear-gradient(135deg, #c7d2fe, #f0f9ff, #dbeafe);
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-10">

  <div class="w-full max-w-2xl bg-white/90 backdrop-blur-md shadow-2xl rounded-3xl p-6 sm:p-8 fade-in border border-blue-100">

    <div class="text-center mb-6 sm:mb-8">
      <h1 class="text-3xl sm:text-4xl font-extrabold text-blue-700">🐾 Edit Pet Details</h1>
      <p class="text-gray-500 text-sm mt-1">Update this pet’s information below.</p>
    </div>

    <form method="POST" enctype="multipart/form-data" class="space-y-5 sm:space-y-6">
      <div>
        <label class="block font-medium text-gray-700 mb-1">Pet Name</label>
        <input type="text" name="pet_name" value="<?= htmlspecialchars($pet['pet_name']) ?>"
          class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none transition-all duration-200 hover:shadow-md" required>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block font-medium text-gray-700 mb-1">Breed</label>
          <input type="text" name="breed" value="<?= htmlspecialchars($pet['breed']) ?>"
            class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none hover:shadow-md transition-all duration-200" required>
        </div>

        <div>
          <label class="block font-medium text-gray-700 mb-1">Age</label>
          <input type="text" name="age" value="<?= htmlspecialchars($pet['age']) ?>"
            class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none hover:shadow-md transition-all duration-200" required>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block font-medium text-gray-700 mb-1">Gender</label>
          <select name="gender"
            class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none hover:shadow-md transition-all duration-200" required>
            <option value="1" <?= $pet['gender'] == 1 ? 'selected' : '' ?>>Male</option>
            <option value="2" <?= $pet['gender'] == 2 ? 'selected' : '' ?>>Female</option>
          </select>
        </div>

        <div>
          <label class="block font-medium text-gray-700 mb-1">Type</label>
          <select name="type"
            class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none hover:shadow-md transition-all duration-200" required>
            <option value="Dog" <?= $pet['type'] == 'Dog' ? 'selected' : '' ?>>Dog</option>
            <option value="Cat" <?= $pet['type'] == 'Cat' ? 'selected' : '' ?>>Cat</option>
            <option value="Others" <?= $pet['type'] == 'Others' ? 'selected' : '' ?>>Others</option>
          </select>
        </div>
      </div>

      <div>
        <label class="block font-medium text-gray-700 mb-1">Status</label>
        <select name="pet_status"
          class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none hover:shadow-md transition-all duration-200" required>
          <option value="Available" <?= $pet['pet_status'] == 'Available' ? 'selected' : '' ?>>Available</option>
          <option value="Not Available" <?= $pet['pet_status'] == 'Not Available' ? 'selected' : '' ?>>Not Available</option>
          <option value="Adopted" <?= $pet['pet_status'] == 'Adopted' ? 'selected' : '' ?>>Adopted</option>
        </select>
      </div>

      <div>
        <label class="block font-medium text-gray-700 mb-1">Image</label>
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
          <img src="../uploads/<?= htmlspecialchars($pet['image']) ?>" alt="Pet Image"
            class="w-24 h-24 object-cover rounded-lg shadow border border-gray-200 transition-transform duration-300 hover:scale-105">
          <input type="file" name="image"
            class="flex-1 border border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none hover:shadow-md transition-all duration-200">
        </div>
      </div>

      <div>
        <label class="block font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" rows="3"
          class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none hover:shadow-md transition-all duration-200"><?= htmlspecialchars($pet['description']) ?></textarea>
      </div>

      <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
        <a href="../admin/pet.php"
          class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 hover:scale-105 transition-all duration-200 font-medium text-center">Cancel</a>
        <button type="submit"
          class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 hover:scale-105 transition-all duration-200">Update Pet</button>
      </div>
    </form>
  </div>

  <script>

  document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get("status");
    if (status === "edited") {
      Swal.fire({
        icon: "info",
        title: "Pet Updated Successfully!",
        text: "The pet information has been updated.",
        showConfirmButton: false,
        timer: 2000
      }).then(() => {
        window.history.replaceState(null, null, window.location.pathname);
      });
    }
  });
  </script>

</body>
</html>
