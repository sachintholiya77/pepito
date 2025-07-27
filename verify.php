<?php
// Start a PHP session
session_start();

// Include the database connection file
require_once 'config/db_connect.php';

// Include the email sending function
require_once 'config/email_sender.php';

$error = '';
$success = '';
$user_email = '';
$is_resend_request = false;

// Determine the user's email from various sources (URL, session, POST)
if (isset($_GET['email'])) {
    $user_email = htmlspecialchars($_GET['email']);
} else if (isset($_SESSION['temp_email'])) {
    $user_email = $_SESSION['temp_email'];
} else if (isset($_POST['email'])) {
    $user_email = htmlspecialchars($_POST['email']);
}

// Check if a resend request was made
if (isset($_GET['resend']) && $_GET['resend'] == 1 && !empty($user_email)) {
    $is_resend_request = true;

    // Generate a new 6-digit OTP
    $otp_code = rand(100000, 999999);
    
    // Set OTP expiration time (e.g., 10 minutes from now)
    $otp_expires_at = date('Y-m-d H:i:s', time() + (24 *60 * 60));

    // Update the user's record with the new OTP
    $update_sql = "UPDATE users SET otp_code = ?, otp_expires_at = ? WHERE email = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("sss", $otp_code, $otp_expires_at, $user_email);

    if ($stmt_update->execute()) {
        // Prepare email content with the new OTP
        $subject = "Your New Pepito OTP for Verification";
        $body = "
            <p>Hello,</p>
            <p>You requested a new OTP. Your new One-Time Password (OTP) for verification is:</p>
            <h3 style='font-size: 24px; font-weight: bold;'>$otp_code</h3>
            <p>This code is valid for 10 minutes. Please do not share it with anyone.</p>
            <p>Thanks,<br>The Pepito Team</p>
        ";
        
        // Send the new OTP via email
        if (send_verification_email($user_email, "User", $subject, $body)) {
            $success = "A new OTP has been sent to your email. Please check your inbox.";
        } else {
            $error = "Failed to resend OTP. Please try again.";
        }
    } else {
        $error = "Failed to generate new OTP. Please try again.";
    }
    $stmt_update->close();
}

// ==============================================
// Logic for Verifying Submitted OTP
// ==============================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve and sanitize the submitted OTP
    $otp_code = mysqli_real_escape_string($conn, $_POST['otp_code']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if the OTP field is empty
    if (empty($otp_code)) {
        $error = "Please enter the OTP code.";
    } else {
        // Use a prepared statement to find the user with the matching email and OTP, and check if the OTP has not expired
        $sql = "SELECT id FROM users WHERE email = ? AND otp_code = ? AND otp_expires_at >= NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $otp_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // OTP is correct and not expired, now verify the account
            $update_sql = "UPDATE users SET is_verified = 1, otp_code = NULL, otp_expires_at = NULL WHERE id = ?";
            $stmt_update = $conn->prepare($update_sql);
            $stmt_update->bind_param("i", $user['id']);

            if ($stmt_update->execute()) {
                $success = "Your account has been successfully verified! You can now log in.";
                // Clear the temporary email session variable
                unset($_SESSION['temp_email']);
                // Redirect to the login page after a short delay
                header("refresh:1;url=login.php");
            } else {
                $error = "Account verification failed. Please try again.";
            }
            $stmt_update->close();
        } else {
            $error = "Invalid or expired OTP. Please check your email and try again or request a new one.";
        }

        $stmt->close();
    }
}

// Include the header partial from the template
include 'includes/header.php';
?>

<section class="login-hero-section section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-xl-5 col-lg-6 col-md-10 m-auto">
                <div class="login-data">
                    <form class="auth-form" method="POST" action="verify.php">
                        <h2>OTP Verification</h2>
                        <h5>Please enter the OTP sent to your email.</h5>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($user_email); ?>">
                        <div class="form-input mt-4">
                            <input type="text" class="form-control" name="otp_code" placeholder="Enter OTP" required>
                            <i class="ri-lock-password-line"></i>
                        </div>
                        <button type="submit" class="btn theme-btn submit-btn w-100 rounded-2">VERIFY</button>
                        
                        <div class="text-center mt-3">
                            <a href="verify.php?email=<?php echo urlencode($user_email); ?>&resend=1" class="forgot-link">Resend OTP</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
// Include the footer partial from the template
include 'includes/footer.php';
?>