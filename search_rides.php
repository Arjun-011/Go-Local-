<?php
echo "<p>PHP File Loaded Successfully</p>";

$conn = new mysqli("localhost", "root", "", "carpooling_db");
if ($conn->connect_error) {
    die("<p style='color: red;'>Connection failed: " . $conn->connect_error . "</p>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<p>Received data:</p>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Continue with your ride search logic here...
}
?>
<?php
$conn = new mysqli("localhost", "root", "", "carpooling_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debug: Confirm form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<p>Received data:</p>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $pickup = $_POST['pickup'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $date = $_POST['date'] ?? '';
    $passengers = $_POST['passengers'] ?? 1;

    if (empty($pickup) || empty($destination) || empty($date)) {
        echo "<p style='color: red;'>Please fill in all fields correctly.</p>";
        exit;
    }

    // SQL Query for filtering rides
    $stmt = $conn->prepare("
        SELECT * FROM rides
        WHERE destination = ?
        AND date = ?
        AND passengers >= ?
    ");
    $stmt->bind_param("ssi", $destination, $date, $passengers);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<h2>Available Rides</h2>";
        while ($row = $result->fetch_assoc()) {
            echo "
                <div class='ride-card'>
                    <div class='ride-info'>
                        <h3>" . htmlspecialchars($row['pickup']) . " → " . htmlspecialchars($row['destination']) . "</h3>
                        <p>Date: " . htmlspecialchars($row['date']) . "</p>
                        <p>Seats Available: " . htmlspecialchars($row['passengers']) . "</p>
                        <p>Price: ₹" . htmlspecialchars($row['price']) . "</p>
                    </div>
                    <button class='book-btn'>Book</button>
                </div>
            ";
        }
    } else {
        echo "<p>No rides available for your criteria.</p>";
    }
}
$conn->close();
?>
