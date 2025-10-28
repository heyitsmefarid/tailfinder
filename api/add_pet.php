<?php
require '../db_connect.php';
$conn = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['pet_name'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $status = 'Available';


    $img_name = "default.png";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($ext, $allowed)) {
            $img_name = time() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $img_name);
        }
    }

    $stmt = $conn->prepare("INSERT INTO pet_tbl (pet_name, breed, age, gender, type, image, description, pet_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $breed, $age, $gender, $type, $img_name, $description, $status]);

   header("Location: ../admin/pet.php?status=added"); exit;

    exit;
}
?>
