<?php
require '../db_connect.php';

// Get pet ID from query
if (!isset($_GET['pet_id'])) {
    header("Location: pet.php");
    exit;
}

$pet_id = $_GET['pet_id'];

// Fetch pet data
$stmt = $conn->prepare("SELECT * FROM pet_tbl WHERE pet_id = ?");
$stmt->execute([$pet_id]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    die("Pet not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_name = $_POST['pet_name'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $type = $_POST['type']; // Added type
    $description = $_POST['description'];
    $status = $_POST['pet_status'];

    // Handle image upload
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if(in_array($ext, $allowed)){
            $img_name = time() . "." . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $img_name);
        } else {
            $img_name = $pet['image']; // keep existing if invalid
        }
    } else {
        $img_name = $pet['image']; // keep existing
    }

    // Update pet including type
    $stmt = $conn->prepare("UPDATE pet_tbl SET pet_name=?, breed=?, age=?, gender=?, type=?, image=?, description=?, pet_status=? WHERE pet_id=?");
    $stmt->execute([$pet_name, $breed, $age, $gender, $type, $img_name, $description, $status, $pet_id]);

    header("Location: pet.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>
<body class="sb-nav-fixed">

<?php include 'includes/nav.php'; ?>
<div id="layoutSidenav">
    <?php include 'includes/sidebar.php'; ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 mt-4">
                <h1 class="mb-4"><i class="fas fa-paw me-2"></i>Edit Pet</h1>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Pet Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Pet Name</label>
                                    <input type="text" name="pet_name" class="form-control" value="<?= htmlspecialchars($pet['pet_name']) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Breed</label>
                                    <input type="text" name="breed" class="form-control" value="<?= htmlspecialchars($pet['breed']) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Age</label>
                                    <input type="text" name="age" class="form-control" value="<?= htmlspecialchars($pet['age']) ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="1" <?= $pet['gender']==1?'selected':'' ?>>Male</option>
                                        <option value="2" <?= $pet['gender']==2?'selected':'' ?>>Female</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Type</label>
                                    <select name="type" class="form-select" required>
                                        <option value="Dog" <?= $pet['type']=='Dog'?'selected':'' ?>>Dog</option>
                                        <option value="Cat" <?= $pet['type']=='Cat'?'selected':'' ?>>Cat</option>
                                        <option value="Others" <?= $pet['type']=='Others'?'selected':'' ?>>Others</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status</label>
                                    <select name="pet_status" class="form-select" required>
                                        <option value="Available" <?= $pet['pet_status']=='Available'?'selected':'' ?>>Available</option>
                                        <option value="Not Available" <?= $pet['pet_status']=='Not Available'?'selected':'' ?>>Not Available</option>
                                        <option value="Adopted" <?= $pet['pet_status']=='Adopted'?'selected':'' ?>>Adopted</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                                    <img id="imgPreview" src="../uploads/<?= $pet['image'] ?>" alt="Image Preview" class="img-thumbnail mt-2" style="width:100px;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($pet['description']) ?></textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="pet.php" class="btn btn-secondary me-2"><i class="fas fa-times me-1"></i>Cancel</a>
                                <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Update Pet</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <?php include 'includes/footer.php'; ?>
    </div>
</div>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('imgPreview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
