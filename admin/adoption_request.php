<?php
require '../db_connect.php';
session_start();


// Handle Approve/Reject from form submission
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])){
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];
    $new_status = $action === 'approve' ? 'Approved' : 'Rejected';

    // 1️⃣ Update adoption request status
    $stmt = $conn->prepare("UPDATE adoption_request_tbl SET adoption_status=? WHERE request_id=?");
    $stmt->execute([$new_status, $request_id]);

    // 2️⃣ If approved, also mark the pet as Adopted
    if($action === 'approve'){
        // Get the pet_id from this request
        $stmtPet = $conn->prepare("SELECT pet_id FROM adoption_request_tbl WHERE request_id=?");
        $stmtPet->execute([$request_id]);
        $pet = $stmtPet->fetch(PDO::FETCH_ASSOC);

        if($pet){
            $stmtUpdatePet = $conn->prepare("UPDATE pet_tbl SET pet_status='Adopted' WHERE pet_id=?");
            $stmtUpdatePet->execute([$pet['pet_id']]);
        }
    }
}


// Fetch all adoption requests with user and pet info
$stmt = $conn->prepare("
    SELECT r.*, 
           u.first_name, u.last_name, u.middle_initial, u.email, u.contact_number, u.street, u.barangay, u.city, u.province, u.date_registered, u.role AS user_role, u.status AS user_status,
           p.pet_name, p.type AS pet_type, p.breed, p.age AS pet_age, p.image AS pet_image, p.pet_status
    FROM adoption_request_tbl r
    JOIN user_tbl u ON r.user_id = u.user_id
    JOIN pet_tbl p ON r.pet_id = p.pet_id
    ORDER BY r.application_date DESC
");
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'includes/head.php'; ?>

<body class="sb-nav-fixed">

    <!-- Navbar -->
    <?php include 'includes/nav.php'; ?>

    <div id="layoutSidenav">

        <!-- Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- Page Content -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Manage Adoption Requests</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Adoption Requests</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-heart me-1"></i>
                            All Adoption Requests
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatablesSimple" class="table table-bordered table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Pet</th>
                                            <th>Type</th>
                                            <th>Breed</th>
                                            <th>Age</th>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Status</th>
                                            <th>Applied On</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Pet</th>
                                            <th>Type</th>
                                            <th>Breed</th>
                                            <th>Age</th>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Status</th>
                                            <th>Applied On</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php foreach($requests as $req): ?>
                                        <tr>
                                            <td><?= $req['request_id'] ?></td>
                                            <td>
                                                <?= htmlspecialchars($req['pet_name']) ?><br>
                                                <img src="../uploads/<?= htmlspecialchars($req['pet_image']) ?>" alt="Pet Image" width="60">
                                            </td>
                                            <td><?= htmlspecialchars($req['pet_type']) ?></td>
                                            <td><?= htmlspecialchars($req['breed']) ?></td>
                                            <td><?= htmlspecialchars($req['pet_age']) ?></td>
                                            <td><?= htmlspecialchars($req['first_name'].' '.$req['middle_initial'].' '.$req['last_name']) ?></td>
                                            <td><?= htmlspecialchars($req['email']) ?></td>
                                            <td><?= htmlspecialchars($req['contact_number']) ?></td>
                                            <td>
                                                <span class="<?= $req['adoption_status']=='Pending' ? 'text-warning' : ($req['adoption_status']=='Approved' ? 'text-success' : 'text-danger') ?>">
                                                    <?= htmlspecialchars($req['adoption_status']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($req['application_date']) ?></td>
                                            <td>
                                                <?php if($req['adoption_status']=='Pending'): ?>
                                                <form method="POST" style="display:inline-block">
                                                    <input type="hidden" name="request_id" value="<?= $req['request_id'] ?>">
                                                    <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                                </form>
                                                <form method="POST" style="display:inline-block">
                                                    <input type="hidden" name="request_id" value="<?= $req['request_id'] ?>">
                                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                                                </form>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

            <!-- Footer -->
            <?php include 'includes/footer.php'; ?>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('#datatablesSimple').DataTable();
    });
    </script>

</body>
</html>
