<?php
// We only need to send the email from this script.
// No need for session_start() or database connection.
require_once 'email_sender.php';

// Get the user data from the query string
// The data is passed from signup.php via a non-blocking request
if (isset($_GET['email']) && isset($_GET['name']) && isset($_GET['otp'])) {
    $email = urldecode($_GET['email']);
    $name = urldecode($_GET['name']);
    $otp = urldecode($_GET['otp']);

    // Prepare email content with the OTP
    $subject = "Your Pepito OTP for Verification";
    $body = "
        <p>Hello $name,</p>
        <p>Your One-Time Password (OTP) for verification is:</p>
        <h3 style='font-size: 24px; font-weight: bold;'>$otp</h3>
        <p>This code is valid for 10 minutes. Please do not share it with anyone.</p>
        <p>Thanks,<br>The Pepito Team</p>
    ";

    // Send the verification email using our reusable function
    send_verification_email($email, $name, $subject, $body);

    // This script does not need to output anything or redirect.
}
?>