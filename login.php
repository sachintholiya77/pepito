<?php
// Start a PHP session to manage user login state
session_start();

// Include the database connection file
require_once 'config/db_connect.php';

$error = '';
$success = '';

// Check if the form was submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve and sanitize form data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Use a prepared statement to prevent SQL injection when retrieving the user
    // We now select the 'is_verified' column as well
    $sql = "SELECT id, name, password, role, is_verified FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            
            // NEW CHECK: Verify if the user's account is verified
            if ($user['is_verified'] == 1) {
                // Password is correct AND account is verified, create a session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                
                $success = "Login successful. Redirecting...";

                // Redirect user based on their role
                if ($user['role'] == 'admin') {
                    header("refresh:2;url=admin/dashboard.php");
                } else {
                    header("refresh:2;url=index.php");
                }
            } else {
                // Account is not verified, show an error
                $error = "Your account is not verified. Please check your email for the verification link.";
            }
            
        } else {
            // Invalid password
            $error = "Invalid email or password.";
        }
    } else {
        // User not found
        $error = "Invalid email or password.";
    }

    $stmt->close();
}

// Include the header partial
include 'includes/auth.php';
include 'includes/header.php';
?>

<section class="login-hero-section section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-xl-5 col-lg-6 col-md-10 m-auto">
                <div class="login-data">
                    <form class="auth-form" method="POST" action="login.php">
                        <h2>Sign in</h2>
                        <h5>
                            or
                            <a href="signup.php"><span class="theme-color">create an account</span></a>
                        </h5>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <div class="form-input">
                            <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                            <i class="ri-mail-line"></i>
                        </div>
                        <div class="form-input">
                            <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                            <i class="ri-lock-password-line"></i>
                        </div>
                        <button type="submit" class="btn theme-btn submit-btn w-100 rounded-2">LOGIN</button>
                        <a href="forgot_password.php" class="forgot-link">Forgot password?</a>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
// Include the footer partial
include 'includes/footer.php';
?>