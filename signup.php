<?php
// Start a PHP session to manage user login state
session_start();

// Include the database connection file
require_once 'config/db_connect.php';

// Include the email sending function
require_once 'config/email_sender.php';

$error = '';
$success = '';

// Check if the form was submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve and sanitize form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);

    // Validate if all required fields are filled
    if (empty($name) || empty($email) || empty($password) || empty($phone_number)) {
        $error = "All fields are required.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if the email already exists in the database
        $check_email_sql = "SELECT id FROM users WHERE email = ?";
        $stmt_check = $conn->prepare($check_email_sql);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "This email is already registered. Please log in.";
        } else {
            // Generate a random 6-digit OTP
            $otp_code = rand(100000, 999999);
            
            // Set OTP expiration time (e.g., 10 minutes from now)
            $otp_expires_at = date('Y-m-d H:i:s', time() + (24 * 60 * 60));

            // Hash the password for secure storage
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Use a prepared statement to prevent SQL injection and insert the new user
            // Set is_verified to 0 by default, and store the OTP and its expiration
            $insert_sql = "INSERT INTO users (name, email, password, phone_number, otp_code, otp_expires_at, is_verified) VALUES (?, ?, ?, ?, ?, ?, 0)";
            $stmt_insert = $conn->prepare($insert_sql);
            $stmt_insert->bind_param("ssssss", $name, $email, $hashed_password, $phone_number, $otp_code, $otp_expires_at);

            if ($stmt_insert->execute()) {
                // Prepare email content with the OTP
                $subject = "Your Pepito OTP for Verification";
                $body = "
                    <p>Hello $name,</p>
                    <p>Thank you for signing up for Pepito! Your One-Time Password (OTP) for verification is:</p>
                    <h3 style='font-size: 24px; font-weight: bold;'>$otp_code</h3>
                    <p>This code is valid for 10 minutes. Please do not share it with anyone.</p>
                    <p>Thanks,<br>The Pepito Team</p>
                ";

                // Send the verification email using our reusable function
               // NEW ASYNCHRONOUS EMAIL SENDING
                $async_url = "http://localhost/pepito/config/send_email_async.php?email=" . urlencode($email) . "&name=" . urlencode($name) . "&otp=" . urlencode($otp_code);

                // We use stream_context_create to make a non-blocking request.
                // This sends the request and immediately continues script execution.
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 1, // Set a very short timeout
                        'header'  => "Connection: Close\r\n"
                    ]
                ]);

                file_get_contents($async_url, false, $context);

                // Email sending is now handled in the background. The user will not wait for it.
                $success = "Registration successful! Please check your email to verify your account.";

                // Redirect to the OTP verification page
                $_SESSION['temp_email'] = $email;
                header("Location: verify.php");
                exit();

            } else {
                $error = "Registration failed. Please try again.";
            }

            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}

// Include the header partial from the template
include 'includes/auth.php';
include 'includes/header.php';
?>

<section class="login-hero-section section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-xl-5 col-lg-6 col-md-10 m-auto">
                <div class="login-data">
                    <form class="auth-form" method="POST" action="signup.php">
                        <h2>Sign up</h2>
                        <h5>
                            or
                            <a href="login.php"><span class="theme-color">login to your account</span></a>
                        </h5>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <div class="form-input">
                            <input type="text" class="form-control" name="name" placeholder="Enter your full name" required>
                            <i class="ri-user-3-line"></i>
                        </div>
                        <div class="form-input">
                            <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
                            <i class="ri-mail-line"></i>
                        </div>
                        <div class="form-input">
                            <input type="tel" class="form-control" name="phone_number" placeholder="Enter your number">
                            <i class="ri-phone-line"></i>
                        </div>
                        <div class="form-input">
                            <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                            <i class="ri-lock-password-line"></i>
                        </div>
                        <button type="submit" class="btn theme-btn submit-btn w-100 rounded-2">Create Account</button>
                        <p class="fw-normal content-color">
                            By creating an account, I accept the
                            <span class="fw-semibold">
                                Terms & Conditions & Privacy Policy</span>
                        </p>
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