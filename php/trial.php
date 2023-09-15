<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Bid</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            text-align: center;
            margin-top: 50px;
            max-width: 600px;
            margin: 0 auto;
        }

        .container h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: red;
        }

        .container label {
            display: block;
            margin-bottom: 10px;
            color: red;
        }

        .container input[type="text"],
        .container input[type="number"],
        .container input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .container button {
            background-color: #008CBA;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .container button:hover {
            background-color: #00567c;
        }

        .art-details {
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }

        .art-details img {
            max-width: 500px;
            max-height: 500px;
        }

        .art-details h1 {
            color: red;
        }

        .art-details p {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();
        include 'conn.php'; // Include the database connection file

        // Check if the "Want to Bid" button is clicked on home.php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["want-to-bid"]) && $_POST["want-to-bid"] == "yes") {
            // Show a form to enter the art name
            ?>
            <h1>Submit Bid</h1>
            <form action="submit_bid.php" method="post">
                <label for="art-name">Enter Art Name:</label>
                <input type="text" name="art-name" required>
                <button type="submit">Submit</button>
            </form>
            <?php
        } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["art-name"])) {
            // Get user input for art name
            $artName = $_POST["art-name"];

            // Query the database to fetch art details based on the entered art name
            $sql = "SELECT id, art_name, description, image_url, previous_bid FROM art_pieces WHERE art_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $artName);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if a match is found
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Art name matched, save it in the session
                $_SESSION["art-name"] = $row["art_name"];

                // Display art details
                echo "<div class='art-details'>";
                echo "<img src='" . $row['image_url'] . "' alt='Art Image'>";
                echo "<h1>Art Details</h1>";
                echo "<p>Title: " . $row['art_name'] . "</p>";
                echo "<p>Description: " . $row['description'] . "</p>";
                echo "<p>Previous Bid: $" . $row['previous_bid'] . "</p>";
                echo "</div>";

                // Check if user details are already saved in the session
                if (!isset($_SESSION["bidder-name"]) || !isset($_SESSION["bidder-age"]) || !isset($_SESSION["bidder-email"])) {
                    // User details not saved, display the form to enter personal details
                    ?>
                    <h1>Bid Form</h1>
                    <form action="process_bid.php" method="post">
                        <label for="bidder-name">Name:</label>
                        <input type="text" name="bidder-name" required>
                        <label for="bidder-age">Age:</label>
                        <input type="number" name="bidder-age" required>
                        <label for="bidder-email">Email:</label>
                        <input type="email" name="bidder-email" required>
                        <button type="submit">Submit Bid</button>
                    </form>
                    <?php
                } else {
                    // User details already saved, display a welcome message
                    $bidderName = $_SESSION["bidder-name"];
                    echo "<h1>Welcome, $bidderName!</h1>";
                    // ...
                }
            } else {
                // Art name did not match, display a message
                echo "Art name not found. Please enter a valid art name.";
            }

            // Close database connection
            $stmt->close();
            $conn->close();
        } else {
            // Redirect to home.php if accessed without proper context
            header("Location: home.php");
            exit;
        }
        ?>
    </div>
</body>
</html>
