<?php
// If user is already logged in (either admin or regular user), redirect
if (isset($_SESSION['user_id'])) {
    // You can redirect based on role
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit();
}
