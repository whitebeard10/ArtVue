<?php
include 'conn.php'; // Include the database connection file
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="../resources/favicon.png" />
  <link rel="stylesheet" href="../css/trial.css">
  <title>ArtVue</title>
</head>
<body>

<nav class="navbar">
  <a href="home.php" class="logo"><img src="../resources/lg.png" alt="ArtVue Logo"></a>
  <ul class="nav-links">
    <li><a href="#">Your Bids</a></li>
    <li><a href="about.php">About Us</a></li>
    <li><a href="contact.php">Contact</a></li>
    <li><a href="#">Exclusives</a></li>
  </ul>
</nav>

<section class="art-gallery">
  <?php
    // Fetch art data from database
    $sql = "SELECT id, art_name, description, image_url, previous_bid FROM art_pieces";
    $result = $conn->query($sql);
    $counter = 0; // Initialize a counter
  
    while ($row = $result->fetch_assoc()) {
      // Extract art details
      $id = $row["id"];
      $artName = $row["art_name"];
      $description = $row["description"];
      $imageURL = $row["image_url"];
      $previousBid = $row["previous_bid"];
  ?>

  <?php
    // Start a new row for every 3 art pieces
    if ($counter % 3 === 0) {
      echo '<div class="art-row">';
    }
  ?>
  
  <div class="art-item">
    <!-- Art Image -->
    <img src="<?php echo $imageURL; ?>" alt="Art Piece <?php echo $id; ?>">
    
    <!-- Art Details -->
    <p class="art-name"><?php echo $artName; ?></p>
    
    <!-- Bid Options -->
    <div class="bid-options">
      <p class="previous-bid">Previous Bid: $<?php echo $previousBid; ?></p>
      <form class="new-bid-form" action="submit_bid.php" method="post">
        <label for="new-bid">Place New Bid:</label>
        <input type="number" name="new-bid" id="new-bid" step="0.01" required>
        <button type="submit">Submit Bid</button>
      </form>
    </div>
  </div>

  <?php
    $counter++;
    // Close the row div for every 3 art pieces
    if ($counter % 3 === 0) {
      echo '</div>';
    }
  ?>
  
  <?php
    }
    // Close any open row div if the number of art pieces is not a multiple of 3
    if ($counter % 3 !== 0) {
      echo '</div>';
    }
    // Close the PHP loop
  ?>

</section>

<!-- ... Your JavaScript and closing body/html tags ... -->
</body>
</html>
