<?php
session_start();
require '../db_connect.php';
$conn = connectDB();
// Fetch admin data (assuming admin is user_id = 1)
$stmt = $conn->prepare("SELECT * FROM user_tbl WHERE user_id = 1 LIMIT 1");
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    die("Admin account not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $middle_initial = strtoupper(trim($_POST['middle_initial']));
    $contact_number = trim($_POST['contact_number']);

    // Check if password fields are filled
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($new_password)) {

        // Password confirmation check
        if ($new_password !== $confirm_password) {
            $error = "Passwords do not match!";
        } else {
            // Hash new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $update = $conn->prepare("UPDATE user_tbl SET first_name=?, last_name=?, middle_initial=?, contact_number=?, password=? WHERE user_id=1");
            $update->execute([$first_name, $last_name, $middle_initial, $contact_number, $hashed_password]);

            $success = "Admin profile and password updated successfully!";
        }

    } else {
        // Update without changing password
        $update = $conn->prepare("UPDATE user_tbl SET first_name=?, last_name=?, middle_initial=?, contact_number=? WHERE user_id=1");
        $update->execute([$first_name, $last_name, $middle_initial, $contact_number]);

        $success = "Admin profile updated successfully!";
    }

    // Refresh admin data
    $stmt = $conn->prepare("SELECT * FROM user_tbl WHERE user_id = 1 LIMIT 1");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
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
                <h1 class="mt-4"><i class="fas fa-user-shield me-2"></i>Edit Admin Account</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Admin Profile</li>
                </ol>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if(isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <strong>Update Admin Information</strong>
                    </div>

                    <div class="card-body">
                        <form method="POST">

                            <div class="mb-3">
                                <label>Email (Not Editable)</label>
                                <input type="email" value="<?= htmlspecialchars($admin['email']) ?>" class="form-control" readonly>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label>First Name</label>
                                    <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($admin['first_name']) ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Last Name</label>
                                    <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($admin['last_name']) ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Middle Initial</label>
                                    <input type="text" name="middle_initial" maxlength="1" class="form-control" value="<?= htmlspecialchars($admin['middle_initial']) ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Contact Number</label>
                                <input type="text" name="contact_number" class="form-control" value="<?= htmlspecialchars($admin['contact_number']) ?>" required>
                            </div>

                            <hr>

                            <h5 class="text-primary">Change Password (Optional)</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="Leave empty to keep current password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Confirm New Password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password">
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Changes</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </main>

        <?php include 'includes/footer.php'; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
