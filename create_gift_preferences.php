<?php
require('connect.php');
include('functions.php');
include('header.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Access denied. You must be logged in to create gift preferences.";
    header("refresh:5;url=view_character.php"); // Redirect after 5 seconds
    exit;
}

// Fetch the user's role from the database
$user_id = $_SESSION['user_id'];
$query = "  SELECT role 
            FROM users 
            WHERE user_id = :user_id";
$statement = $db->prepare($query);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Check if the user has an admin role
if ($user['role'] !== 'admin') {
    echo "Access denied. Only admin users can create gift preferences.";
    header("refresh:3;url=view_character.php"); // Redirect after 3 seconds
    exit;
}

$error_message = ''; // Variable to store error message
$success_message = ''; // Variable to store success message

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $character_id = intval($_POST['character_id']);
    $loved_gifts = htmlspecialchars(trim($_POST['loved_gifts']));
    $liked_gifts = htmlspecialchars(trim($_POST['liked_gifts']));
    $disliked_gifts = htmlspecialchars(trim($_POST['disliked_gifts']));
    $hated_gifts = htmlspecialchars(trim($_POST['hated_gifts']));
    $banned_gifts = htmlspecialchars(trim($_POST['banned_gifts']));

    // Validate input
    if (empty($character_id) || empty($loved_gifts) || empty($liked_gifts)) {
        $error_message = 'Character, Loved Gifts, and Liked Gifts fields are required.';
    } else {
        // Insert the new gift preference into the database
        $query = "INSERT INTO gift_preferences (character_id, loved_gifts, liked_gifts, disliked_gifts, hated_gifts, banned_gifts) VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $db->prepare($query);
        $statement->bindParam(1, $character_id, PDO::PARAM_INT);
        $statement->bindParam(2, $loved_gifts, PDO::PARAM_STR);
        $statement->bindParam(3, $liked_gifts, PDO::PARAM_STR);
        $statement->bindParam(4, $disliked_gifts, PDO::PARAM_STR);
        $statement->bindParam(5, $hated_gifts, PDO::PARAM_STR);
        $statement->bindParam(6, $banned_gifts, PDO::PARAM_STR);

        if ($statement->execute()) {
            $success_message = 'Gift preference created successfully!';
        } else {
            $error_message = 'Database error: Could not create gift preference.';
        }

        $statement->closeCursor();
    }
}

// Fetch all characters for the dropdown
$query = "SELECT character_id, name FROM characters ORDER BY name ASC";
$statement = $db->prepare($query);
$statement->execute();
$characters = $statement->fetchAll(PDO::FETCH_ASSOC);
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
                    <li class="breadcrumb-item"><a href="gift_preferences.php">Gift Preferences</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Gift Preferences</li>
                </ol>
            </nav>
            <!-- Breadcrumb End -->

            <hr class="featurette-divider mt-2">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <h1 class="mb-4">Create New Gift Preference</h1>

                        <?php if ($error_message): ?>
                            <div class="alert alert-danger text-center" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success_message): ?>
                            <div class="alert alert-success text-center" role="alert">
                                <?php echo $success_message; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="create_gift_preferences.php">
                            <div class="mb-3">
                                <label for="character_id" class="form-label">Character</label>
                                <select id="character_id" name="character_id" class="form-control" required>
                                    <option value="">Select Character</option>
                                    <?php foreach ($characters as $character): ?>
                                        <option value="<?php echo $character['character_id']; ?>"><?php echo htmlspecialchars($character['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                
                            <div class="form-group mb-3">
                                <label for="loved_gifts" class="mb-2">Loved Gifts</label>
                                <textarea class="form-control wysiwyg-editor" id="loved_gifts" name="loved_gifts" rows="3" ></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="liked_gifts" class="mb-2">Liked Gifts</label>
                                <textarea class="form-control wysiwyg-editor" id="liked_gifts" name="liked_gifts" rows="3" ></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="disliked_gifts" class="mb-2">Disliked Gifts</label>
                                <textarea class="form-control wysiwyg-editor" id="disliked_gifts" name="disliked_gifts" rows="3" ></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="hated_gifts" class="mb-2">Hated Gifts</label>
                                <textarea class="form-control wysiwyg-editor" id="hated_gifts" name="hated_gifts" rows="3" ></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="banned_gifts" class="mb-2">Banned Gifts</label>
                                <textarea class="form-control wysiwyg-editor" id="banned_gifts" name="banned_gifts" rows="3" ></textarea>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mt-4 py-2 px-5 fixed-size">
                                    Create Gift Preference
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="javascript/initialize_WYSIWYG.js"></script>
</body>

<?php
include('footer.php');
?>