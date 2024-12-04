<?php
require('connect.php');
include('functions.php');
include('header.php');

// Fetch all characters from the database
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$query = "SELECT character_id, name, description, image, TIMESTAMPDIFF(MINUTE, created_at, NOW()) AS minutes_ago FROM characters WHERE name LIKE :search ORDER BY character_id DESC";
$statement = $db->prepare($query);
$statement->bindParam(':search', $search, PDO::PARAM_STR);
$statement->execute();
$characters = $statement->fetchAll();

// Pagination logic
$characters_per_page = 6;
$total_characters = count($characters);
$total_pages = ceil($total_characters / $characters_per_page);

// Get the current page from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, min($total_pages, $current_page));

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $characters_per_page;

// Fetch the characters for the current page
$current_characters = array_slice($characters, $offset, $characters_per_page);
?>

<body>
    <main>
        <div class="container py-4">
            <!-- Breadcrumb Begin -->
            <nav class="breadcrumb-nav py-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Characters</li>
                </ol>
            </nav>
    <!-- Breadcrumb End -->

    <hr class="featurette-divider mt-2">


    <div class="d-flex justify-content-between align-items-center mt-5">
        <div class="d-flex align-items-center">
            <h1 class="featurette-heading fw-normal lh-1 me-3">New Character</h1>
            <a class="btn btn-primary" href="create_character.php">Create New Character</a>
        </div>
        <form class="d-flex" role="search" method="get" action="view_character.php">
            <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>

    

    <!-- Pagination Begin -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center mt-4">
            <li class="page-item <?php if ($current_page <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" tabindex="-1" aria-disabled="true">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php if ($i == $current_page) echo 'active'; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
            <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>">Next</a>
            </li>
        </ul>
    </nav>
    <!-- Pagination End -->

<div class="album py-5 bg-body-tertiary">
    <div class="container">
        <div class="row justify-content-center row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php foreach ($current_characters as $character): ?>
            <div class="col">
                <div class="card shadow-sm">
                    <img src="uploads/<?php echo filter_var(basename($character['image']), FILTER_SANITIZE_SPECIAL_CHARS); ?>" class="bd-placeholder-img card-img-top" width="100%" height="400" alt="<?php echo filter_var($character['name'], FILTER_SANITIZE_SPECIAL_CHARS); ?>">
                    <div class="card-body">
                        <h5 class="character-name" style="color: red;"><?php echo filter_var($character['name'], FILTER_SANITIZE_SPECIAL_CHARS); ?></h5>
                        <p class="card-text"><?php echo filter_var($character['description'], FILTER_SANITIZE_SPECIAL_CHARS); ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <a class="btn btn-sm btn-danger" href="delete_character.php?id=<?php echo $character['character_id']; ?>" onclick="return confirm('Are you sure you want to delete this character?');">Delete</a>
                                <a class="btn btn-sm btn-outline-secondary" href="edit_character.php?id=<?php echo $character['character_id']; ?>">Edit</a>
                            </div>
                            <small class="text-body-secondary"><?php echo $character['minutes_ago']; ?> mins ago</small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
   
    </body>
</main>