<?php
require '../db_connect.php';

// Fetch all pets
$stmt = $conn->prepare("SELECT * FROM pet_tbl ORDER BY pet_id ASC");
$stmt->execute();
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $pet_id = $_POST['pet_id'];
    $new_status = $_POST['pet_status'];
    $stmt = $conn->prepare("UPDATE pet_tbl SET pet_status=? WHERE pet_id=?");
    $stmt->execute([$new_status, $pet_id]);
    header("Location: pet.php");
    exit;
}

// Handle Add Pet
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_pet'])) {
    $pet_name = $_POST['pet_name'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $description = $_POST['description'];
    $type = $_POST['type']; // new

    // Handle image upload
    $image_name = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $upload_dir = '../uploads/';
    move_uploaded_file($tmp_name, $upload_dir.$image_name);

    // Default status: Available
    $status = 'Available';

    $stmt = $conn->prepare("INSERT INTO pet_tbl (pet_name, breed, age, gender, image, description, pet_status, type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$pet_name, $breed, $age, $gender, $image_name, $description, $status, $type]);

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
                <h1 class="mt-4"><i class="fas fa-paw me-2"></i>Manage Pets</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Pets List</li>
                </ol>

                <!-- Add Pet Button -->
                <div class="mb-3">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPetModal">
                        <i class="fas fa-plus me-1"></i>Add New Pet
                    </button>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-paw me-1"></i>All Pets
                    </div>
                    <div class="card-body table-responsive">
                        <table id="datatablesSimple" class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Breed</th>
                                    <th>Type</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Image</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pets as $pet): ?>
                                <tr>
                                    <td><?= $pet['pet_id'] ?></td>
                                    <td><?= htmlspecialchars($pet['pet_name']) ?></td>
                                    <td><?= htmlspecialchars($pet['breed']) ?></td>
                                    <td><?= htmlspecialchars($pet['type']) ?></td>
                                    <td><?= htmlspecialchars($pet['age']) ?></td>
                                    <td><?= $pet['gender']==1?'Male':'Female' ?></td>
                                    <td><img src="../uploads/<?= $pet['image'] ?>" style="width:60px;" class="img-thumbnail"></td>
                                    <td><?= htmlspecialchars($pet['description']) ?></td>
                                    <td>
                                        <span class="badge <?= $pet['pet_status']=='Available'?'bg-success':($pet['pet_status']=='Adopted'?'bg-primary':'bg-secondary') ?>">
                                            <?= $pet['pet_status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="edit_pet.php?pet_id=<?= $pet['pet_id'] ?>" class="btn btn-primary btn-sm mb-1"><i class="fas fa-edit"></i> Edit</a>
                                        <button class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#statusModal<?= $pet['pet_id'] ?>"><i class="fas fa-exchange-alt"></i> Change Status</button>
                                        <!-- Delete Button triggers modal -->
                                        <button class="btn btn-danger btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $pet['pet_id'] ?>">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>

                                <!-- Status Modal -->
                                <div class="modal fade" id="statusModal<?= $pet['pet_id'] ?>" tabindex="-1" aria-labelledby="statusModalLabel<?= $pet['pet_id'] ?>" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <form method="POST">
                                        <input type="hidden" name="pet_id" value="<?= $pet['pet_id'] ?>">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="statusModalLabel<?= $pet['pet_id'] ?>">Change Status for <?= htmlspecialchars($pet['pet_name']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Select Status</label>
                                                <select name="pet_status" class="form-select" required>
                                                    <option value="Available" <?= $pet['pet_status']=='Available'?'selected':'' ?>>Available</option>
                                                    <option value="Not Available" <?= $pet['pet_status']=='Not Available'?'selected':'' ?>>Not Available</option>
                                                    <option value="Adopted" <?= $pet['pet_status']=='Adopted'?'selected':'' ?>>Adopted</option>
                                                </select>
                                            </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                                          </div>
                                        </div>
                                    </form>
                                  </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal<?= $pet['pet_id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $pet['pet_id'] ?>" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="deleteModalLabel<?= $pet['pet_id'] ?>">Delete <?= htmlspecialchars($pet['pet_name']) ?>?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                        Are you sure you want to delete this pet? This action cannot be undone.
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <a href="delete_pet.php?pet_id=<?= $pet['pet_id'] ?>" class="btn btn-danger">Yes, Delete</a>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>

        <?php include 'includes/footer.php'; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    $('#datatablesSimple').DataTable();
});
</script>

<!-- Add Pet Modal -->
<div class="modal fade" id="addPetModal" tabindex="-1" aria-labelledby="addPetModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="addPetModalLabel"><i class="fas fa-plus me-1"></i>Add New Pet</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
                <label>Pet Name</label>
                <input type="text" name="pet_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Breed</label>
                <input type="text" name="breed" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Type</label>
                <select name="type" class="form-select" required>
                    <option value="Dog">Dog</option>
                    <option value="Cat">Cat</option>
                    <option value="Others">Others</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Age</label>
                <input type="text" name="age" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="1">Male</option>
                    <option value="2">Female</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Image</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" name="add_pet" class="btn btn-success"><i class="fas fa-save me-1"></i>Add Pet</button>
          </div>
        </div>
    </form>
  </div>
</div>

</body>
</html>
