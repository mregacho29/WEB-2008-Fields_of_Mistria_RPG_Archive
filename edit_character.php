<?php
require('functions.php');
require('connect.php');
include('file_upload.php'); // Include the file where file_upload_path() is defined

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger text-center" role="alert">Access denied. You must be logged in to edit characters.</div>';
    header("refresh:3;url=view_character.php"); // Redirect after 3 seconds
    exit;
}
include('header.php');

// Fetch the user's role from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM users WHERE user_id = :user_id";
$statement = $db->prepare($query);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Check if the user has an admin role
if ($user['role'] !== 'admin') {
    echo '<div class="alert alert-danger text-center" role="alert">Access denied. Only admin users can edit characters.</div>';
    header("refresh:3;url=view_character.php"); // Redirect after 3 seconds
    exit;
}

// Check if the character ID is provided
if (isset($_GET['id'])) {
    $character_id = (int)$_GET['id'];

    // Fetch the character details from the database
    $query = "SELECT * FROM characters WHERE character_id = :character_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':character_id', $character_id, PDO::PARAM_INT);
    $statement->execute();
    $character = $statement->fetch(PDO::FETCH_ASSOC);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the updated character details from the form
        $name = $_POST['name'];
        $description = $_POST['description'];

        // Handle file upload
        $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
        $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);
        $invalid_file_detected = false;
        $image_path = $character['image']; // Default to existing image path

        if ($image_upload_detected) {
            $file_filename = $_FILES['image']['name'];
            $temporary_file_path = $_FILES['image']['tmp_name'];
            $new_file_path = file_upload_path($file_filename);

            if (file_is_valid($temporary_file_path, $new_file_path)) {
                if (!file_exists(dirname($new_file_path))) {
                    mkdir(dirname($new_file_path), 0777, true);
                }
                move_uploaded_file($temporary_file_path, $new_file_path);
                $image_path = 'uploads/' . basename($new_file_path); // Store relative path
            } else {
                $invalid_file_detected = true;
            }
        }

        if (!$invalid_file_detected) {
            // Prepare the SQL statement to update the character
            $query = "UPDATE characters SET name = :name, description = :description, image = :image, updated_at = NOW() WHERE character_id = :character_id";
            $statement = $db->prepare($query);
            $statement->bindParam(':name', $name, PDO::PARAM_STR);
            $statement->bindParam(':description', $description, PDO::PARAM_STR);
            $statement->bindParam(':image', $image_path, PDO::PARAM_STR);
            $statement->bindParam(':character_id', $character_id, PDO::PARAM_INT);

            // Execute the statement
            if ($statement->execute()) {
                // Redirect to the view_character.php page with a success message
                header("Location: view_character.php?message=Character updated successfully");
                exit;
            } else {
                // If there was an error, display an error message
                echo '<div class="alert alert-danger text-center" role="alert">Error updating character.</div>';
            }
        } else {
            echo '<div class="alert alert-danger text-center" role="alert">The uploaded file is not a valid file type. Only JPG, PNG, GIF images are allowed.</div>';
        }
    }
}
?>

<body>
    <main>
        <div class="container py-4">
            <!-- Breadcrumb Begin -->
            <nav class="breadcrumb-nav py-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="view_character.php">Characters</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
            <!-- Breadcrumb End -->

            <hr class="featurette-divider mt-5 mb-3">

            <div class="container mt-5">
                <h1 class="mb-4">Edit Character</h1>
                <form method="post" action="edit_character.php?id=<?php echo $character_id; ?>" enctype="multipart/form-data">
                    <div class="form-group mb-3 mt-5">
                        <label for="name" class="mb-2">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($character['name']); ?>" required>
                    </div>
                    <div class="form-group mb-3 mt-5">
                        <label for="description" class="mb-2">Description:</label>
                        <textarea class="form-control wysiwyg-editor" id="description" name="description" rows="3" required><?php echo htmlspecialchars($character['description']); ?></textarea>
                    </div>
                    <div class="form-group mb-3 mt-5">
                        <label for="image" class="mb-2">Image:</label>
                        <input type="file" class="form-control-file" id="image" name="image" accept=".jpg, .jpeg, .png, .gif">
                        <?php
                        $image_path = htmlspecialchars($character['image']);
                        if (file_exists($image_path)) {
                            echo '<img src="' . $image_path . '" alt="Character Image" class="img-thumbnail mt-2" width="150">';
                        } else {
                            echo '<p class="text-danger">Image not found.</p>';
                        }
                        ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Character</button>
                </form>
            </div>
        </div>
    </main>
</body>

<?php
include('footer.php');
?>