<?php
session_start();

// If confirmed, destroy session then go to login
if (isset($_GET['confirm']) && $_GET['confirm'] === "yes") {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// Get previous page (where logout was clicked)
$goBack = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Logging Out...</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
Swal.fire({
    title: 'Are you sure?',
    text: "You are about to log out.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, Logout',
    cancelButtonText: 'Cancel'
}).then((result) => {
    if (result.isConfirmed) {
        // User confirmed → logout
        window.location.href = "logout.php?confirm=yes";
    } else {
        // User canceled → return to exact previous page
        window.location.href = "<?php echo $goBack; ?>";
    }
});
</script>

</body>
</html>
