<?php
// Include database connection
include 'db_connection.php';

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seedId = $_POST['seedId'];
    $title = $_POST['title'];
    $category = $_POST['category'];
    $color = $_POST['color'];
    $costPerKg = $_POST['costPerKg'];
    
    // Check if seedId already exists
    $checkSql = "SELECT id FROM seeds WHERE seedId = '" . $conn->real_escape_string($seedId) . "'";
    $result = $conn->query($checkSql);
    
    if ($result->num_rows > 0) {
        header("Location: index.php?message=Seed ID already exists&success=0");
        exit();
    }
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO seeds (seedId, title, category, color, costPerKg) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $seedId, $title, $category, $color, $costPerKg);
    
    // Execute the statement
    if ($stmt->execute()) {
        header("Location: index.php?message=Seed record added successfully&success=1");
    } else {
        header("Location: index.php?message=Error: " . $stmt->error . "&success=0");
    }
    
    $stmt->close();
}

$conn->close();
?>