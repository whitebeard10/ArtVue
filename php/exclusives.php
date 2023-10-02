<?php
include '../conn.php'; // Include the database connection file using relative path
?>

<!DOCTYPE html>
<html>
<head>
    <title>Exclusives</title>
</head>
<body>
    <h1>Exclusive Art Pieces</h1>

    <?php
    // Fetch exclusive art pieces from the database
    $sqlFile = '../SQL/art_pieces.sql';
    $sql = file_get_contents($sqlFile); // Read SQL file content

    if ($conn->multi_query($sql)) {
        do {
            if ($result = $conn->store_result()) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div>";
                    echo "<h2>" . $row["art_name"] . "</h2>";
                    echo "<p>" . $row["description"] . "</p>";
                    echo "<img src='" . $row["image_url"] . "' alt='" . $row["art_name"] . "'>";
                    echo "<p>Previous Bid: $" . $row["previous_bid"] . "</p>";
                    echo "<form action='place_bid.php' method='post'>";
                    echo "<input type='hidden' name='art_id' value='" . $row["id"] . "'>";
                    echo "<label for='bid_amount'>Place a Bid:</label>";
                    echo "<input type='number' name='bid_amount' step='0.01' required>";
                    echo "<button type='submit'>Submit Bid</button>";
                    echo "</form>";
                    echo "</div>";
                }
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
    } else {
        echo "No exclusive art pieces found.";
    }
    ?>

</body>
</html>

