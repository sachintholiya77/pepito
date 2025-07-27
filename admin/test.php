<?php
// 1. Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "pepito";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Define user ID (or other identifier)
$user_id = 1; // change as needed

// 3. Prepare SQL query to fetch email and profile_pic
$sql = "SELECT  profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

// Check if the prepare() failed
if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

// 4. Bind parameters and execute
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// 5. Fetch result
if ($row = $result->fetch_assoc()) {
    $profile_pic = $row['profile_picture'];

    // Display email

    // Display profile picture (assuming profile_pic is the image path)
    if (!empty($profile_pic)) {
        echo "<img src='../" . $profile_pic . "' alt='Profile Picture' style='max-width: 200px; height: auto;'>";
    } else {
        echo "No profile picture available.";
    }
} else {
    echo "No user found.";
}

// 6. Close connections
$stmt->close();
$conn->close();
?>
