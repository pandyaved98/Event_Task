<?php
require_once('connect.php'); // Include your database connection file
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

// Check if the request method is POST

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

// Retrieve user data from the POST request
$name = $_POST['name'];
$email = $_POST['email'];
$profilePhoto = $_POST['profilePhoto'];

// Check if the user already exists in the database
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Returning user: Fetch token and log in
    $row = $result->fetch_assoc();
    $userId = $row['id'];
    // You may return a success message or token here
} else {
    // New user: Insert user data into the database
    $sql_insert = "INSERT INTO users (name, email, profile_photo) 
                   VALUES ('$name', '$email', '$profilePhoto')";

    if ($conn->query($sql_insert) === TRUE) {
        // User created successfully
        $userId = $conn->insert_id;
        // You may return a success message or token here
    } else {
        // Error creating user
        echo json_encode(['error' => 'Error creating user']);
    }
}

$conn->close(); // Close the database connection
?>
