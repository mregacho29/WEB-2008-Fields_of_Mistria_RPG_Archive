<?php
require('connect.php');
include('functions.php');

if (isset($_SESSION['alert_message'])) {
  echo '<div class="alert alert-success text-center" role="alert">' . $_SESSION['alert_message'] . '</div>';
  unset($_SESSION['alert_message']);
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
<main>
  <div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="image/DisplayImage3.jpg" class="d-block w-100" alt="First slide">
        <div class="container">
          <div class="carousel-caption text-start">
            <h1>Discover this New World!</h1>
            <p class="opacity-75">Join our community to stay updated on the latest news,<br> 
            exclusive content, and special events for Fields of Mistria. <br>
            Sign up now and be the first to know!</p>
            <p><a class="btn btn-lg btn-primary" href="signup.php">Sign up today</a></p>
          </div>
        </div>
      </div>

      <div class="carousel-item">
        <img src="image/DisplayImage1.avif" 
            class="d-block w-100" 
            alt="Second slide">
            <div class="container">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-start" style="top: 10%;">
                    <h1 class="mb-4">Meet the Characters!</h1>
                    <p><a class="btn btn-lg btn-primary" href="character.php">Learn more</a></p>
                </div>
            </div>
        </div>


      <div class="carousel-item">
        <img src="image/DisplayImage4.jpg" class="d-block w-100" alt="Third slide">
        <div class="container">
          <div class="carousel-caption text-end">
            <h1>Explore this Stunning World!</h1>
            <p class="opacity-75">Dive into our gallery to see stunning visuals and artwork from the enchanting world of Fields of Mistria. <br>
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
    <!-- Three columns of text below the carousel -->
    <div class="row">
      <?php foreach ($characters as $character): ?>
        <div class="col-lg-4 mx-auto mt-5">
          <img src="<?php echo filter_var($character['image'], FILTER_SANITIZE_SPECIAL_CHARS); ?>" class="bd-placeholder-img rounded-circle" width="250" height="250" alt="<?php echo filter_var($character['name'], FILTER_SANITIZE_SPECIAL_CHARS); ?>">
          <h2 class="fw-normal mt-5"><?php echo filter_var($character['name'], FILTER_SANITIZE_SPECIAL_CHARS); ?></h2>
          <p><?php echo filter_var($character['description'], FILTER_SANITIZE_SPECIAL_CHARS); ?></p>
          <p><a class="btn btn-secondary" href="#">View details &raquo;</a></p>
        </div><!-- /.col-lg-4 -->
      <?php endforeach; ?>

        <div class="col-lg-4 mx-auto mt-5">
            <svg class="bd-placeholder-img rounded-circle" width="250" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-color)"/></svg>
            <h2 class="fw-normal mt-2">Heading</h2>
            <p>Another exciting bit of representative placeholder content. This time, we've moved on to the second column.</p>
            <p><a class="btn btn-secondary" href="#">View details &raquo;</a></p>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-4 mx-auto mt-5">
            <svg class="bd-placeholder-img rounded-circle" width="250" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-color)"/></svg>
            <h2 class="fw-normal mt-2">Heading</h2>
            <p>And lastly this, the third column of representative placeholder content.</p>
            <p><a class="btn btn-secondary" href="#">View details &raquo;</a></p>
        </div><!-- /.col-lg-4 -->

        <div class="col-lg-4 mx-auto mt-5">
            <svg class="bd-placeholder-img rounded-circle" width="250" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-color)"/></svg>
            <h2 class="fw-normal mt-2">Heading</h2>
            <p>Some representative placeholder content for the three columns of text below the carousel. This is the first column.</p>
            <p><a class="btn btn-secondary" href="#">View details &raquo;</a></p>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-4 mx-auto mt-5">
            <svg class="bd-placeholder-img rounded-circle" width="250" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-color)"/></svg>
            <h2 class="fw-normal mt-2">Heading</h2>
            <p>Another exciting bit of representative placeholder content. This time, we've moved on to the second column.</p>
            <p><a class="btn btn-secondary" href="#">View details &raquo;</a></p>
        </div><!-- /.col-lg-4 -->
        <div class="col-lg-4 mx-auto mt-5">
            <svg class="bd-placeholder-img rounded-circle" width="250" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-color)"/></svg>
            <h2 class="fw-normal mt-2">Heading</h2>
            <p>And lastly this, the third column of representative placeholder content.</p>
            <p><a class="btn btn-secondary" href="#">View details &raquo;</a></p>
        </div><!-- /.col-lg-4 -->

       

    </div><!-- /.row -->

    <!-- START THE FEATURETTES -->

    <hr class="featurette-divider mt-5">

    <div class="row featurette mt-5">
        <div class="col-md-7 mx-auto">
            <h2 class="featurette-heading fw-normal lh-1">First featurette heading. <span class="text-body-secondary">It’ll blow your mind.</span></h2>
            <p class="lead">Some great placeholder content for the first featurette here. Imagine some exciting prose here.</p>
        </div>
        <div class="col-md-5 mx-auto ">
            <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-bg)"/><text x="50%" y="50%" fill="var(--bs-secondary-color)" dy=".3em">500x500</text></svg>
        </div>
    </div>

    <hr class="featurette-divider">

    <div class="row featurette">
        <div class="col-md-7 order-md-2 mx-auto">
            <h2 class="featurette-heading fw-normal lh-1">Oh yeah, it’s that good. <span class="text-body-secondary">See for yourself.</span></h2>
            <p class="lead">Another featurette? Of course. More placeholder content here to give you an idea of how this layout would work with some actual real-world content in place.</p>
        </div>
        <div class="col-md-5 order-md-1 mx-auto">
            <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-bg)"/><text x="50%" y="50%" fill="var(--bs-secondary-color)" dy=".3em">500x500</text></svg>
        </div>
    </div>

    <hr class="featurette-divider">

    <!-- /END THE FEATURETTES -->

</div><!-- /.container -->


</main>

<?php
include('footer.php');
?>