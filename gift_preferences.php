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

// Fetch all gift preferences from the database
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$order = isset($_GET['order']) ? $_GET['order'] : 'A-Z';

$order_by = 'c.name ASC'; // Default to sorting by character's name A-Z
if ($order === 'Z-A') {
    $order_by = 'c.name DESC'; // Sort by character's name Z-A
} elseif ($order === 'Newest') {
    $order_by = 'gp.created_at DESC';
} elseif ($order === 'Oldest') {
    $order_by = 'gp.created_at ASC';
} elseif ($order === 'Updated Date') {
    $order_by = 'gp.updated_at DESC';
} elseif ($order === 'Created Date') {
    $order_by = 'gp.created_at ASC';
}

// Fetch all gift preferences from the database
$query = "SELECT gp.preference_id, gp.character_id, gp.loved_gifts, gp.liked_gifts, gp.disliked_gifts, gp.hated_gifts, gp.banned_gifts, c.name AS character_name 
          FROM gift_preferences gp 
          JOIN characters c ON gp.character_id = c.character_id 
          WHERE c.name LIKE :search
          OR gp.loved_gifts LIKE :search
          OR gp.liked_gifts LIKE :search
          OR gp.disliked_gifts LIKE :search
          OR gp.hated_gifts LIKE :search
          OR gp.banned_gifts LIKE :search
          ORDER BY $order_by";
$statement = $db->prepare($query);
$statement->bindParam(':search', $search, PDO::PARAM_STR);
$statement->execute();
$gift_preferences = $statement->fetchAll(PDO::FETCH_ASSOC);

// Pagination logic
$preferences_per_page = 1;
$total_preferences = count($gift_preferences);
$total_pages = ceil($total_preferences / $preferences_per_page);

// Get the current page from the URL, default to 1 if not set
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, min($total_pages, $current_page));

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $preferences_per_page;

// Fetch the gift preferences for the current page
$current_preferences = array_slice($gift_preferences, $offset, $preferences_per_page);
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
                    <li class="breadcrumb-item"><a href="categories.php">Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Gift Preferences</li>
                </ol>
            </nav>
            <!-- Breadcrumb End -->

            <hr class="featurette-divider mt-2">

            <!-- Functions Search / Sort -->    
            <div class="d-flex justify-content-between align-items-center mt-5">
                <div class="d-flex align-items-center">
                    <h1 class="featurette-heading fw-normal lh-1 me-3">Create New Gift Preference</h1>
                    <a class="btn btn-primary" href="create_gift_preferences.php">Create</a>
                </div>

                <div class="d-flex align-items-center">
                    <form class="d-flex me-2" role="search" method="get" action="gift_preferences.php">
                        <input class="form-control me-2" type="search" id="search" name="search" placeholder="Search" aria-label="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button class="btn btn-outline-success" id="button" type="submit">Search</button>
                    </form>
                </div>

                <div class="dropdown sort-dropdown-menu" id="preference-sort-dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Sort By
                    </button>
                    <ul class="dropdown-menu sort-dropdown" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item sort-option" href="?order=A-Z">A-Z</a></li>
                        <li><a class="dropdown-item sort-option" href="?order=Z-A">Z-A</a></li>
                        <li><a class="dropdown-item sort-option" href="?order=Newest">Newest</a></li>
                        <li><a class="dropdown-item sort-option" href="?order=Oldest">Oldest</a></li>
                        <li><a class="dropdown-item sort-option" href="?order=Created Date">Created Date</a></li>
                        <li><a class="dropdown-item sort-option" href="?order=Updated Date">Updated Date</a></li>
                    </ul>
                </div>
            </div>

            <!-- Pagination Begin -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center mt-4">
                    <li class="page-item <?php if ($current_page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo $current_page - 1; ?>&search=<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>&order=<?php echo $order; ?>" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if ($i == $current_page) echo 'active'; ?>"><a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>&order=<?php echo $order; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                    <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo $current_page + 1; ?>&search=<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>&order=<?php echo $order; ?>">Next</a>
                    </li>
                </ul>
            </nav>
            <!-- Pagination End -->

            <!-- Table Begin -->
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Character</th>
                                <th>Loved Gifts</th>
                                <th>Liked Gifts</th>
                                <th>Disliked Gifts</th>
                                <th>Hated Gifts</th>
                                <th>Banned Gifts</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($current_preferences as $preference): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($preference['character_name']); ?></td>
                                    <td><?php echo htmlspecialchars_decode($preference['loved_gifts']); ?></td>
                                    <td><?php echo htmlspecialchars_decode($preference['liked_gifts']); ?></td>
                                    <td><?php echo htmlspecialchars_decode($preference['disliked_gifts']); ?></td>
                                    <td><?php echo htmlspecialchars_decode($preference['hated_gifts']); ?></td>
                                    <td><?php echo htmlspecialchars_decode($preference['banned_gifts']); ?></td>
                                    <td>
                                        <a href="edit_gift_preferences.php?id=<?php echo $preference['preference_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="delete_gift_preferences.php?id=<?php echo $preference['preference_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this gift preference?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Table End -->
        </div>
    </main>
    <!-- Include the WYSIWYG editor initialization script -->
    <script src="javascript/initialize_WYSIWYG.js"></script>
</body>

<?php
include('footer.php');
?>