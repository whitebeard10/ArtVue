<?php
session_start();
include 'conn.php'; // Include the database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $bidderName = $_POST["bidder-name"];
    $bidderAge = $_POST["bidder-age"];
    $bidderEmail = $_POST["bidder-email"];
    $artName = $_SESSION["art-name"]; // Retrieve art name from the session
    $bidAmount = $_POST["bid-amount"];

    // Insert the bid details into the bid_details table
    $sql = "INSERT INTO bid_details (bidder_name, bidder_age, bidder_email, art_name, art_piece_id, bid_amount)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissid", $bidderName, $bidderAge, $bidderEmail, $artName, $artPieceId, $bidAmount);

    // You'll need to determine the art piece ID based on the selected art name; retrieve it from your art_pieces table.
    $artPieceId = getArtPieceId($artName);

    if ($stmt->execute()) {
        // Redirect to home.php
        header("Location: home.php");
        exit();
    } else {
        echo "Error placing bid: " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect to home.php if accessed without proper context
    header("Location: home.php");
    exit;
}

// Function to get the art piece ID based on the art name (You need to implement this)
// Function to get the art piece ID based on the art name
function getArtPieceId($artName) {
    include 'conn.php'; // Include the database connection file

    // Prepare a SQL statement to select the art piece ID based on the art name
    $sql = "SELECT id FROM art_pieces WHERE art_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $artName);

    if ($stmt->execute()) {
        $stmt->bind_result($artPieceId);
        $stmt->fetch();

        // Close the statement and the database connection
        $stmt->close();
        $conn->close();

        return $artPieceId;
    } else {
        echo "Error fetching art piece ID: " . $stmt->error;
        // You can handle the error here, e.g., return an error code or handle it as needed.
        return null;
    }
}

?>
