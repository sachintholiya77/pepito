<?php
// Start a PHP session if one is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// We assume $_SESSION['admin_id'] and $conn are available from including files.
$profile_image_src = ''; // Initialize empty

if (isset($_SESSION['user_id'])) {
    // Fetch the 'profile_picture' path for the logged-in admin
    $sql = "SELECT profile_picture FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        // Construct the full relative path from the current partial file to the image
        // 'admin/partials/admin_navbar.php' needs to go up two levels ('../../')
        // to reach the project root, then down into 'uploads/profile_pictures/'
        // The value from the 'profile_picture' column should be like 'uploads/profile_pictures/filename.jpg'
        $profile_image_src = 'uploads/users/profile/' . htmlspecialchars($user_data['profile_picture']);
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pepito">
    <meta name="keywords" content="Pepito">
    <meta name="author" content="Pepito">
    <link rel="icon" href="assets/images/logo/favicon.png" type="image/x-icon">
    <title>Pepito - Online Food Ordering</title>
    <link rel="apple-touch-icon" href="assets/images/logo/favicon.png">
    <meta name="theme-color" content="#ff8d2f">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Pepito">
    <meta name="msapplication-TileImage" content="assets/images/logo/favicon.png">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" type="text/css" id="rtl-link" href="assets/css/vendors/bootstrap.css">

    <link rel="stylesheet" type="text/css" href="assets/css/vendors/swiper-bundle.min.css">

    <link rel="stylesheet" type="text/css" href="assets/css/vendors/aos.css">

    <link rel="stylesheet" type="text/css" href="assets/css/vendors/remixicon.css">

    <link rel="stylesheet" id="change-link" type="text/css" href="assets/css/style.css">
</head>

<body class="position-relative noice-background">

    <header class="header-light">
        <div class="custom-container">
            <nav class="navbar navbar-expand-lg p-0">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#offcanvasNavbar">
                    <span class="navbar-toggler-icon">
                        <i class="ri-menu-line"></i>
                    </span>
                </button>
                <a href="index.php">
                    <img class="img-fluid logo" src="assets/images/svg/logo2.svg" alt="logo">
                </a>
            <div class="nav-option order-md-2">
    <?php
    // Check if a user is logged in by verifying if the 'user_id' session variable is set
    if (isset($_SESSION['user_id'])) {
    ?>
        <div class="dropdown-button">
            <div class="cart-button">
                <span>5</span>
                <i class="ri-shopping-cart-line cart-bag"></i>
            </div>
            <div class="onhover-box">
                </div>
        </div>
        <div class="profile-part dropdown-button order-md-2">
            <img class="img-fluid profile-pic" src="<?php echo $profile_image_src; ?>" alt="profile">
            <div>
                <h6 class="fw-normal">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h6>
                <h5 class="fw-medium">My Account</h5>
            </div>
            <div class="onhover-box onhover-sm">
                <ul class="menu-list">
                    <li>
                        <a class="dropdown-item" href="profile.php">Profile</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="my_orders.php">My orders</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="saved_address.php">Saved Address</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="saved-card.html">Saved cards</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="setting.html">Settings</a>
                    </li>
                </ul>
                <div class="bottom-btn">
                    <a href="logout.php" class="theme-color fw-medium d-flex"><i
                            class="ri-login-box-line me-2"></i>Logout</a>
                </div>
            </div>
        </div>

    <?php
    } else {
    ?>
        <div class="profile-part d-flex">
            <a href="login.php" class="btn btn-sm theme-btn-primary me-2">Sign In</a>
            <a href="signup.php" class="btn btn-sm theme-btn-secondary">Sign Up</a>
        </div>
    <?php
    }
    ?>
</div>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                        <button class="navbar-toggler btn-close" id="offcanvas-close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-center flex-grow-1">
                            <li class="nav-item">
                                <a class="nav-link active" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="menu-grid.php">Menu</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="contact.html">Contact</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="about.html">About Us</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>