<?php
session_start();
session_unset();
session_destroy();

// Redirect to login page (change if your login page is inside a folder)
header("Location: ../login/index.php");
exit();
