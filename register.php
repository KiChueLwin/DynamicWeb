<?php
session_start();
include 'database/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Get plain password first

    // Basic input validation for null or empty fields
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(array("status" => "error", "message" => "All fields are required"));
        exit(); // Stop further execution
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        echo json_encode(array("status" => "error", "message" => "Email ID already exists"));
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['user'] = $username; // Store user session
            echo json_encode(array("status" => "success", "redirect" => "welcome.php")); // Send redirect instruction
        } else {
            echo json_encode(array("status" => "error", "message" => "Error: " . $stmt->error));
        }

        $stmt->close();
    }
    $checkEmailStmt->close();
    $conn->close();
}
?>