<?php
require '../db_connect.php'; // PDO connection

// Fetch all users
$stmt = $conn->prepare("SELECT * FROM user_tbl ORDER BY user_id ASC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    <h1 class="mt-4">Manage Users</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Users List</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-users me-1"></i>
                            All Users
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatablesSimple" class="table table-bordered table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Middle Initial</th>
                                            <th>Email</th>
                                            <th>Password</th>
                                            <th>Contact</th>
                                            <th>Street</th>
                                            <th>Barangay</th>
                                            <th>City</th>
                                            <th>Province</th>
                                            <th>Date Registered</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Middle Initial</th>
                                            <th>Email</th>
                                            <th>Password</th>
                                            <th>Contact</th>
                                            <th>Street</th>
                                            <th>Barangay</th>
                                            <th>City</th>
                                            <th>Province</th>
                                            <th>Date Registered</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php foreach($users as $row): ?>
                                        <tr>
                                            <td><?= $row['user_id'] ?></td>
                                            <td><?= htmlspecialchars($row['first_name']) ?></td>
                                            <td><?= htmlspecialchars($row['last_name']) ?></td>
                                            <td><?= htmlspecialchars($row['middle_initial']) ?></td>
                                            <td><?= htmlspecialchars($row['email']) ?></td>
                                            <td><?= htmlspecialchars($row['password']) ?></td>
                                            <td><?= htmlspecialchars($row['contact_number']) ?></td>
                                            <td><?= htmlspecialchars($row['street']) ?></td>
                                            <td><?= htmlspecialchars($row['barangay']) ?></td>
                                            <td><?= htmlspecialchars($row['city']) ?></td>
                                            <td><?= htmlspecialchars($row['province']) ?></td>
                                            <td><?= htmlspecialchars($row['date_registered']) ?></td>
                                            <td><?= htmlspecialchars($row['role']) ?></td>
                                            <td><?= ucfirst($row['status']) ?></td>
                                            <td>
                                                <?php if($row['user_id']==1): ?>
                                                    <a href="edit_admin.php?user_id=<?= $row['user_id'] ?>" class="btn btn-primary btn-sm">Edit Admin</a>

                                                <?php else: ?>
                                                    <span class="text-muted">Read-only</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
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
