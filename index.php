<?php
require('connect.php');
include('functions.php');


if (isset($_SESSION['alert_message'])) {
  $alert_message = $_SESSION['alert_message'];
  $alert_type = isset($_SESSION['alert_type']) ? $_SESSION['alert_type'] : 'success'; // Default to success if not set
  echo '<div class="alert alert-' . $alert_type . ' alert-dismissible fade show text-center alert-overlay" role="alert">' . $alert_message . '
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
  unset($_SESSION['alert_message']);
  unset($_SESSION['alert_type']);
}

// Debugging: Check if the database connection is successful
// if (!$db) {
//     die("Database connection failed.");
// }

$query = "SELECT character_id, name, description, image 
    FROM characters 
    ORDER BY character_id DESC";

// Prepare a PDO statement
$statement = $db->prepare($query);

// Execute the statement
$statement->execute();

// fetch the entire Database
$characters = $statement->fetchAll();

// Debugging: Check if rows are fetched
// if (empty($rows)) {
//     echo "No rows fetched from the database.";
// } else {
//     echo "<pre>";
//     print_r($rows);
//     echo "</pre>";
// }

include('header.php');
?>


<!-- CAROUSEL -->
<!-- Start of main content section -->
<div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="image/DisplayImage3.jpg" class="d-block w-100 carousel-image" alt="First slide image">
      <div class="container">
        <div class="carousel-caption text-start">
          <h1 class="text-outline">Discover this New World!</h1>
          <p class="opacity-75">Join our community to stay updated on the latest news,<br> 
          exclusive content, and special events for Fields of Mistria. <br>
          Sign up now and be the first to know!</p>
          <p><a class="btn btn-lg btn-primary" href="signup.php">Sign up today</a></p>
        </div>
      </div>
    </div>

    <div class="carousel-item">
      <img src="image/DisplayImage1.avif" class="d-block w-100 carousel-image" alt="Second slide image">
      <div class="container">
        <div class="carousel-caption d-flex flex-column align-items-center justify-content-start" style="top: 10%;">
          <h1 class="text-outline mb-4">Meet the Characters!</h1>
          <p><a class="btn btn-lg btn-primary" href="character.php">Learn more</a></p>
        </div>
      </div>
    </div>

    <div class="carousel-item">
      <img src="image/DisplayImage4.jpg" class="d-block w-100 carousel-image" alt="Third slide image">
      <div class="container">
        <div class="carousel-caption text-end">
          <h1 class="text-outline">Explore this Stunning World!</h1>
          <p class="opacity-75">Dive into our gallery to see stunning visuals and artwork <br>
          that bring you from the enchanting world of Fields of Mistria. <br>
          Discover the beauty and magic that awaits you.</p>
          <p><a class="btn btn-lg btn-primary" href="#">Browse gallery</a></p>
        </div>
      </div>
    </div>
  </div>

  <div class="carousel-indicators">
    <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>




<div class="container marketing text-center mt-5">
  <div class="row">
    <?php foreach ($characters as $character): ?>
        <div class="col-lg-4 mx-auto mt-5">
            <?php
            $image_path = 'uploads/' . basename($character['image']);
            ?>
            <?php if (file_exists($image_path)): ?>
                <img src="<?php echo $image_path; ?>" 
                     class="bd-placeholder-img rounded-circle character-image" 
                     width="250" 
                     height="250" 
                     alt="<?php echo htmlspecialchars_decode($character['name']); ?>">
            <?php else: ?>
                <img src="path/to/default/image.jpg" 
                     class="bd-placeholder-img rounded-circle character-image" 
                     width="250" 
                     height="250" 
                     alt="Default Image">
            <?php endif; ?>
              
            <h2 class="mt-3"><?php echo htmlspecialchars($character['name']); ?></h2>
            <p>
              <?php
              $max_length = 150; 
              $description = htmlspecialchars_decode($character['description']);
              ?>
              <?= strlen($description) > $max_length ? substr($description, 0, $max_length) . '...' : $description ?>
            </p>

            <p>
                <a class="btn btn-secondary" href="view_character.php">View details &raquo;</a>
            </p>
        </div><!-- /.col-lg-4 -->
    <?php endforeach; ?>
  </div><!-- /.row -->
</div><!-- /.container -->

    <!-- START THE FEATURETTES -->

    <hr class="featurette-divider mt-5">

    
</main>

<?php
include('footer.php');
?>