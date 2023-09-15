<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bids</title>
    <link rel="stylesheet" href="../css/your_bid.css"> <!-- Add your CSS file here for styling -->
</head>
<body>
<nav class="navbar">
  <a href="home.php" class="logo"><img src="../resources/lg.png" alt="ArtVue Logo"></a>
  <ul class="nav-links">
    <li><a href="your_bid.php">Your Bids</a></li>
    <li><a href="about.php">About Us</a></li>
    <li><a href="contact.php">Contact</a></li>
    <li><a href="#">Exclusives</a></li>
  </ul>
</nav>
    <div class="container">
        <h1>Check Your Bids</h1>
        <form action="your_bid.php" method="post">
            <label for="user-name">Enter Your Name:</label>
            <input type="text" name="user-name" placeholder="Name under which bid is registered" required>
            <button type="submit" name="check-bids">Check Your Bids</button>
        </form>

        <?php
        // Include the database connection file
        include 'conn.php';

        // Check if the "Check Your Bids" button is clicked
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["check-bids"])) {
            // Get user input for their name
            $userName = $_POST["user-name"];

            // Query the database to fetch bid details based on the entered name
            $sql = "SELECT * FROM bid_details WHERE bidder_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $userName);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if bids are found for the entered name
            if ($result->num_rows > 0) {
                // Display bid details in a table
                echo "<h2>Your Bid Details</h2>";
                echo "<table border='1'>";
                echo "<tr><th>Art Name</th><th>Art Piece ID</th><th>Bid Amount</th><th>Bid Timestamp</th></tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['art_name'] . "</td>";
                    echo "<td>" . $row['art_piece_id'] . "</td>";
                    echo "<td>$" . $row['bid_amount'] . "</td>";
                    echo "<td>" . $row['bid_timestamp'] . "</td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                // No bids found for the entered name
                echo "<p>No bids found for the entered name: $userName</p>";
            }

            // Close database connection
            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
