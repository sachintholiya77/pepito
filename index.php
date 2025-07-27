<?php
// Start a PHP session if one is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection file
require_once 'config/db_connect.php';

// Include the header partial
include 'includes/header.php';
?>

<section id="home" class="home-wrapper section-b-space overflow-hidden">
    <div class="background-effect">
        <div class="main-circle">
            <div class="main-circle circle-1">
                <div class="main-circle circle-2"></div>
            </div>
        </div>
    </div>
    <div class="container text-center position-relative">

        <?php
        // Check if the user is logged in (i.e., if the 'user_name' session variable is set)
        if (isset($_SESSION['user_name'])) {
            // Display a personalized greeting for the logged-in user
            echo '<h1>Hello, ' . htmlspecialchars($_SESSION['user_name']) . '!</h1>';
            echo '<h2>Welcome back to Pepito.</h2>';
        } else {
            // Display a generic greeting for guests
            echo '<h1>Hello, Guest!</h1>';
            echo '<h2>Discover restaurants that deliver near you</h2>';
        }
        ?>

        <div class="search-section">
            <form class="auth-form search-head">
                <div class="form-group">
                    <div class="form-input mb-0">
                        <input type="search" class="form-control search" id="inputusername"
                            placeholder="Search for your favorite dish">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
            </form>
            <a class="btn theme-btn mt-0" href="#" role="button">Search</a>
        </div>
    </div>
</section>

<?php
// Include the footer partial
include 'includes/footer.php';


?>