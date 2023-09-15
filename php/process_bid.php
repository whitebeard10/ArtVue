<?php
session_start();
include 'conn.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["bidder-name"]) && isset($_POST["bidder-age"]) && isset($_POST["bidder-email"]) && isset($_POST["bid-amount"])) {
    // Get user input
    $bidderName = $_POST["bidder-name"];
    $bidderAge = $_POST["bidder-age"];
    $bidderEmail = $_POST["bidder-email"];
    $bidAmount = $_POST["bid-amount"];

    // Get the art name from the session
    $artName = $_SESSION["art-name"];

    // Retrieve the last bid amount for the art piece
    $sql = "SELECT previous_bid FROM art_pieces WHERE art_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $artName);
    $stmt->execute();
    $stmt->bind_result($lastBidAmount);
    $stmt->fetch();
    $stmt->close();

    // Check if the new bid is greater than the last bid
    if ($bidAmount > $lastBidAmount) {
        // Update the art_pieces table with the new bid amount
        $sqlUpdate = "UPDATE art_pieces SET previous_bid = ? WHERE art_name = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ds", $bidAmount, $artName);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        // Insert the bid details into the bid_details table
        $artPieceId = getArtPieceId($artName);
        if ($artPieceId !== null) {
            $sqlInsert = "INSERT INTO bid_details (bidder_name, bidder_age, bidder_email, art_name, bid_timestamp, art_piece_id, bid_amount) VALUES (?, ?, ?, ?, NOW(), ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("sisidi", $bidderName, $bidderAge, $bidderEmail, $artName, $artPieceId, $bidAmount);
            $stmtInsert->execute();
            $stmtInsert->close();
            
            // Redirect to home.php after successful bid submission
            header("Location: home.php");
            exit;
        } else {
            echo "Error: Art piece ID not found.";
        }
    } else {
        echo '<script>alert("Your bid amount must be higher than the previous bid."); history.back();</script>';
    }

    // Close the database connection
    $conn->close();
} else {
    // Redirect to home.php if accessed without proper context
    header("Location: home.php");
    exit;
}

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
        $stmt->close();

        return $artPieceId;
    } else {
        echo "Error fetching art piece ID: " . $stmt->error;
        return null;
    }
}

?>
