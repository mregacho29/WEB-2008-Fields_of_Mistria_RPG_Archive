<?php
require('connect.php');
include('functions.php');
include('header.php');

// Fetch all characters from the database
$query = "SELECT * FROM characters ORDER BY character_id DESC";
$statement = $db->prepare($query);
$statement->execute();
$characters = $statement->fetchAll();
?>


<body>
    <main>
        <div class="container py-4">
            <!-- Breadcrumb Begin -->
            <nav class="breadcrumb-nav py-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Categories</li>
                </ol>
            </nav>
            <!-- Breadcrumb End -->

            <header class="pb-3 mb-4 border-bottom d-flex justify-content-between align-items-center">
                <a href="/" class="d-flex align-items-center text-body-emphasis text-decoration-none">
                <img src="image/categorieslogo.gif" width="40" height="32" class="me-2" alt="Logo">
                    <span class="fs-4">Categories Overview</span>
                </a>
                <div class="d-flex align-items-center">
                    <p class="mb-0 me-2">Sort by:</p>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            A-Z
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#" onclick="document.getElementById('dropdownMenuButton').innerText = 'A-Z'">A-Z</a></li>
                            <li><a class="dropdown-item" href="#" onclick="document.getElementById('dropdownMenuButton').innerText = 'Z-A'">Z-A</a></li>
                            <li><a class="dropdown-item" href="#" onclick="document.getElementById('dropdownMenuButton').innerText = 'Newest'">Newest</a></li>
                            <li><a class="dropdown-item" href="#" onclick="document.getElementById('dropdownMenuButton').innerText = 'Oldest'">Oldest</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="p-5 mb-4 rounded-3" style="background-image: url('image/charactercategories.gif'); background-size: cover; background-repeat: no-repeat;">
                <div class="container-fluid py-5">
                    <h1 class="display-5 fw-bold">Character Background</h1>
                    <p class="col-md-8 fs-4">Discover the unique stories, skills, and traits of characters in Fields of Mistria. Explore their histories, motivations, and roles in this town.</p>
                    <a class="btn btn-primary btn-lg" href="view_character.php" role="button">Learn More</a>
                </div>
            </div>

            <div class="row align-items-md-stretch">
                <div class="col-md-6">
                    <div class="h-100 p-5 text-bg-dark rounded-3">
                        <h2>Events</h2>
                        <p>Explore key events that shape the lives of characters in Fields of Mistria. Discover pivotal moments and their impacts on the story</p>
                        <button class="btn btn-outline-light" type="button">Learn More</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="h-100 p-5 bg-body-tertiary border rounded-3">
                        <h2>Gift Preferences</h2>
                        <p>Learn about the favorite gifts of characters in Fields of Mistria. Discover what items they love and how to build better relationships with them.</p>
                        <button class="btn btn-outline-secondary" type="button">Learn More</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<?php
include('footer.php');
?>