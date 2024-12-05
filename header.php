<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fields of Mistria RPG Archive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap">
    <!-- Link to custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- TinyMCE script -->
    <script src="https://cdn.tiny.cloud/1/ex9htxor5oal96pp9g5cyom3p4ycoxb0ebkt22fq4x6d361z/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
      <a class="navbar-brand" href="index.php">
        <img
          src="image/Fields_of_Mistria_Title.webp"
          height="45"
          width="150"
          alt="Fields of Mistria Logo"
          loading="lazy"
        />
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav mx-auto text-center">
          <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'index.php') ? 'active' : '' ?>" aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ($current_page == 'news.php') ? 'active' : '' ?>" href="news.php">News</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle <?= ($current_page == 'categories.php') ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Categories
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="categories.php">Categories</a></li>
              <li><a class="dropdown-item" href="view_character.php">Characters</a></li>
              <li><a class="dropdown-item" href="another_category.php">Events</a></li>
              <li><a class="dropdown-item" href="something_else.php">Gift Preferences</a></li>
            </ul>
          </li>
        </ul>

      <!-- Right links -->
      <div class="d-flex align-items-center justify-content-center">
        <?php if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']): ?>
          <a href="login.php" class="btn btn-outline-success me-2">Login</a>
          <a href="signup.php" class="btn btn-primary">Sign up</a>
        <?php else: ?>
          <a href="logout.php" class="btn btn-outline-danger me-2">Logout</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<<<<<<< HEAD
=======




>>>>>>> 46b5c06 (Reinitialize repository, fix delete.php)
</body>
</html>