<?php
require('connect.php');
include('functions.php');
include('header.php');

// Display alert message if set
if (isset($_SESSION['alert_message'])) {
    $alert_message = $_SESSION['alert_message'];
    $alert_type = isset($_SESSION['alert_type']) ? $_SESSION['alert_type'] : 'success'; // Default to success if not set
    echo '<div class="alert alert-' . $alert_type . ' alert-dismissible fade show text-center alert-overlay" role="alert">' . $alert_message . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['alert_message']);
    unset($_SESSION['alert_type']);
}

// Fetch all characters from the database
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$order = isset($_GET['order']) ? $_GET['order'] : 'A-Z';

$order_by = 'name ASC';
if ($order === 'Z-A') {
    $order_by = 'name DESC';
} elseif ($order === 'Newest') {
    $order_by = 'created_at DESC';
} elseif ($order === 'Oldest') {
    $order_by = 'created_at ASC';
} elseif ($order === 'Updated Date') {
    $order_by = 'updated_at DESC';
} elseif ($order === 'Created Date') {
    $order_by = 'created_at ASC';
}

$query = "  SELECT character_id, 
            name, 
            description, 
            image, 
            created_at, 
            updated_at, 
            CONCAT('Created: ', TIMESTAMPDIFF(HOUR, created_at, NOW()), 
            ' hrs ', 
            TIMESTAMPDIFF(MINUTE, created_at, NOW()) % 60, ' mins ago') 
                AS created_at_time_ago, 
            CONCAT('Updated: ', TIMESTAMPDIFF(HOUR, updated_at, NOW()), 
            ' hrs ', 
            TIMESTAMPDIFF(MINUTE, updated_at, NOW()) % 60, ' mins ago') 
                AS updated_at_time_ago 
                FROM characters 
                WHERE name LIKE :search 
                ORDER BY $order_by";
$statement = $db->prepare($query);
$statement->bindParam(':search', $search, PDO::PARAM_STR);
$statement->execute();
$characters = $statement->fetchAll(PDO::FETCH_ASSOC);

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
            <nav class="breadcrumb-nav py-4" 
                 style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" 
                 aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Characters</li>
                </ol>
            </nav>
            <!-- Breadcrumb End -->

            <hr class="featurette-divider mt-2">

            <!-- Functions Search / Sort -->
            <div class="d-flex justify-content-between align-items-center mt-5">
                <div class="d-flex align-items-center">
                    <h1 class="featurette-heading fw-normal lh-1 me-3">Create New Character</h1>
                    <a class="btn btn-primary" href="create_character.php">Create</a>
                </div>

                <div class="d-flex align-items-center">
                    <form class="d-flex me-2" role="search" method="get" action="view_character.php">
                        <input class="form-control me-2" type="search" id="search" name="search" placeholder="Search" aria-label="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars_decode ($_GET['search']) : ''; ?>">
                        <button class="btn btn-outline-success" id="button" type="submit">Search</button>
                    </form>
                </div>

                <div class="dropdown sort-dropdown-menu" id="character-sort-dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Sort By
                    </button>
                    <ul class="dropdown-menu sort-dropdown" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item sort-option" href="#" onclick="sortCharacters('A-Z')">A-Z</a></li>
                        <li><a class="dropdown-item sort-option" href="#" onclick="sortCharacters('Z-A')">Z-A</a></li>
                        <li><a class="dropdown-item sort-option" href="#" onclick="sortCharacters('Newest')">Newest</a></li>
                        <li><a class="dropdown-item sort-option" href="#" onclick="sortCharacters('Oldest')">Oldest</a></li>
                        <li><a class="dropdown-item sort-option" href="#" onclick="sortCharacters('Created Date')">Created Date</a></li>
                        <li><a class="dropdown-item sort-option" href="#" onclick="sortCharacters('Updated Date')">Updated Date</a></li>
                    </ul>
                </div>
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
                    <div class="row justify-content-center row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" id="character-container">
                        <?php
                        // Get the character_id from the URL if set
                        if (isset($_GET['character_id'])) {
                            $character_id = intval($_GET['character_id']);
                            // Fetch and display the character details using $character_id
                            $character_query = "SELECT character_id, name, description, image, created_at, updated_at, 
                                                CONCAT('Created: ', TIMESTAMPDIFF(HOUR, created_at, NOW()), ' hrs ', 
                                                TIMESTAMPDIFF(MINUTE, created_at, NOW()) % 60, ' mins ago') AS created_at_time_ago, 
                                                CONCAT('Updated: ', TIMESTAMPDIFF(HOUR, updated_at, NOW()), ' hrs ', 
                                                TIMESTAMPDIFF(MINUTE, updated_at, NOW()) % 60, ' mins ago') AS updated_at_time_ago 
                                                FROM characters WHERE character_id = :character_id";
                            $character_statement = $db->prepare($character_query);
                            $character_statement->bindParam(':character_id', $character_id, PDO::PARAM_INT);
                            $character_statement->execute();
                            $character = $character_statement->fetch(PDO::FETCH_ASSOC);
                            if ($character) {
                                ?>
                                <div class="col character-box" data-created="<?php echo $character['created_at']; ?>">
                                    <div class="card shadow-sm">
                                        <img src="uploads/<?php echo htmlspecialchars_decode(basename($character['image'])); ?>" class="bd-placeholder-img card-img-top" width="100%" height="400" alt="<?php echo htmlspecialchars_decode($character['name']); ?>">
                                        <div class="card-body">
                                            <h5 class="character-name" style="color: red;"><?php echo htmlspecialchars_decode($character['name']); ?></h5>
                                            <p class="card-text"><?php echo htmlspecialchars_decode($character['description']); ?></p>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <div class="btn-group mr-5">
                                                    <a class="btn btn-sm btn-outline-secondary" href="edit_character.php?id=<?php echo $character['character_id']; ?>">Edit</a>
                                                    <form action="delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this character?');" style="display:inline;">
                                                        <input type="hidden" name="character_id" value="<?php echo $character['character_id']; ?>">
                                                        <input type="hidden" name="command" value="Delete">
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                                <small class="text-body-secondary"><?php echo $character['created_at_time_ago']; ?><br><?php echo $character['updated_at_time_ago']; ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                echo 'Character not found';
                            }
                        } else {
                            // Display characters for the current page
                            foreach ($current_characters as $character) {
                                ?>
                                <div class="col character-box" data-created="<?php echo $character['created_at']; ?>">
                                    <div class="card shadow-sm">
                                        <img src="uploads/<?php echo htmlspecialchars_decode(basename($character['image'])); ?>" class="bd-placeholder-img card-img-top" width="100%" height="400" alt="<?php echo htmlspecialchars_decode($character['name']); ?>">
                                        <div class="card-body">
                                            <h5 class="character-name" style="color: red;"><?php echo htmlspecialchars_decode($character['name']); ?></h5>
                                            <p class="card-text"><?php echo htmlspecialchars_decode($character['description']); ?></p>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <div class="btn-group mr-5">
                                                    <a class="btn btn-sm btn-outline-secondary" href="edit_character.php?id=<?php echo $character['character_id']; ?>">Edit</a>
                                                    <form action="delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this character?');" style="display:inline;">
                                                        <input type="hidden" name="character_id" value="<?php echo $character['character_id']; ?>">
                                                        <input type="hidden" name="command" value="Delete">
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                                <small class="text-body-secondary"><?php echo $character['created_at_time_ago']; ?><br><?php echo $character['updated_at_time_ago']; ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<?php
include('footer.php');
?>