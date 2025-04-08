<?php
$conn = new mysqli("localhost", "root", "", "carpooling_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Signup Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['new_username'];
    $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $campus = $_POST['college'];  // Capture selected SRM campus

    $stmt = $conn->prepare("INSERT INTO users (username, password, campus) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $campus);

    if ($stmt->execute()) {
        echo "<script>
            alert('Signup successful! Redirecting to homepage...');
            window.location.href = 'homepage.html';
        </script>";
    } else {
        echo "<script>
            alert('Error during signup. Please try again.');
            window.history.back();
        </script>";
    }
}

$conn->close();
?>
