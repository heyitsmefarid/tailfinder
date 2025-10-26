<?php
require '../db_connect.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['pet_name'];
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
            $img_name = "default.png"; // fallback if invalid type
        }
    } else {
        $img_name = "default.png";
    }

    // Insert pet including type
    $stmt = $conn->prepare("INSERT INTO pet_tbl (pet_name, breed, age, gender, type, image, description, pet_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $breed, $age, $gender, $type, $img_name, $description, $status]);

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
                <h1 class="mb-4"><i class="fas fa-paw me-2"></i>Add New Pet</h1>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Pet Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Pet Name</label>
                                    <input type="text" name="pet_name" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Breed</label>
                                    <input type="text" name="breed" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Age</label>
                                    <input type="text" name="age" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">--Select--</option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Type</label>
                                    <select name="type" class="form-select" required>
                                        <option value="">--Select Type--</option>
                                        <option value="Dog">Dog</option>
                                        <option value="Cat">Cat</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status</label>
                                    <select name="pet_status" class="form-select" required>
                                        <option value="Available">Available</option>
                                        <option value="Not Available">Not Available</option>
                                        <option value="Adopted">Adopted</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                                    <img id="imgPreview" src="#" alt="Image Preview" class="img-thumbnail mt-2" style="display:none; width:100px;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="4" required></textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="pet.php" class="btn btn-secondary me-2"><i class="fas fa-times me-1"></i>Cancel</a>
                                <button type="submit" class="btn btn-success"><i class="fas fa-plus me-1"></i>Add Pet</button>
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
