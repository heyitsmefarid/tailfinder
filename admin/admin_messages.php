<?php
session_start();
require '../db_connect.php';
$conn = connectDB();
// Handle admin response
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inquiry_id'], $_POST['admin_response'])){
    $inquiry_id = $_POST['inquiry_id'];
    $response = trim($_POST['admin_response']);

    if($response !== ''){
        $stmtUpdate = $conn->prepare("UPDATE inquiry_tbl SET admin_response=? WHERE inquiry_id=?");
        $stmtUpdate->execute([$response, $inquiry_id]);
        header("Location: admin_messages.php?responded=1");
        exit;
    }
}

// Fetch all inquiries with user info
$stmt = $conn->prepare("
    SELECT i.*, u.first_name, u.last_name, u.email
    FROM inquiry_tbl i
    JOIN user_tbl u ON i.user_id = u.user_id
    ORDER BY i.date_sent DESC
");
$stmt->execute();
$inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    <h1 class="mt-4">Manage Messages</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">User Messages</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-envelope me-1"></i>
                            All Messages
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatablesSimple" class="table table-bordered table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Message</th>
                                            <th>Sent On</th>
                                            <th>Admin Response</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Message</th>
                                            <th>Sent On</th>
                                            <th>Admin Response</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php foreach($inquiries as $inq): ?>
                                        <tr>
                                            <td><?= $inq['inquiry_id'] ?></td>
                                            <td><?= htmlspecialchars($inq['first_name'].' '.$inq['last_name']) ?></td>
                                            <td><?= htmlspecialchars($inq['email']) ?></td>
                                            <td><?= htmlspecialchars($inq['content']) ?></td>
                                            <td><?= htmlspecialchars($inq['date_sent']) ?></td>
                                            <td>
                                                <?php if(!empty($inq['admin_response'])): ?>
                                                    <?= htmlspecialchars($inq['admin_response']) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(empty($inq['admin_response'])): ?>
                                                <form method="POST" class="d-flex gap-2">
                                                    <input type="hidden" name="inquiry_id" value="<?= $inq['inquiry_id'] ?>">
                                                    <input type="text" name="admin_response" class="form-control form-control-sm" placeholder="Type reply..." required>
                                                    <button type="submit" class="btn btn-success btn-sm">Reply</button>
                                                </form>
                                                <?php else: ?>
                                                    <span class="text-muted">Replied</span>
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

    <!-- JS files -->
    <script src="admin_messages.js"></script>

</body>
</html>
