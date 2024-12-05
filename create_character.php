<?php
include('authenticate.php');
include('connect.php');
require('file_upload.php');
include('header.php');


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Access denied. You must be logged in to create characters.";
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
    echo "Access denied. Only admin users can create characters.";
    header("refresh:3;url=view_character.php"); // Redirect after 3 seconds
    exit;
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];


    // Handle file upload
    $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
    $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);
    $invalid_file_detected = false;
    $image_path = '';

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
        $sql = "INSERT INTO characters (name, image, description, created_at) 
                VALUES (?, ?, ?, NOW())";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $image_path);
        $stmt->bindParam(3, $description);

        if ($stmt->execute()) {
            $_SESSION['alert_message'] = "New character added successfully";
            $_SESSION['alert_type'] = "success";
        } else {
            $_SESSION['alert_message'] = "Error: " . $stmt->errorInfo()[2];
            $_SESSION['alert_type'] = "danger";
        }
    } else {
        $_SESSION['alert_message'] = "The uploaded file is not a valid file type. Only JPG, PNG, GIF images are allowed.";
        $_SESSION['alert_type'] = "danger";
    }
    header("Location: create_character.php");
    exit;

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
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
            <!-- Breadcrumb End -->

            <hr class="featurette-divider mt-2">

            <div class="container mt-5">
                <h1 class="mb-4">Add New Character</h1>
                <?php if (isset($_SESSION['alert_message'])): ?>
                    <div class="alert alert-<?= $_SESSION['alert_type'] ?> text-center" role="alert">
                        <?= $_SESSION['alert_message'] ?>
                    </div>
                    <?php unset($_SESSION['alert_message']); unset($_SESSION['alert_type']); ?>
                <?php endif; ?>
                <form method="POST" action="create_character.php" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-3 mt-5">
                        <label for="image" class="mb-2">Image:</label>
                        <input type="file" class="form-control-file" id="image" name="image" accept=".jpg, .jpeg, .png, .gif">
                        <?php
                        if (isset($character['image'])) {
                            $image_path = htmlspecialchars($character['image']);
                            if (file_exists($image_path)) {
                                echo '<img src="' . $image_path . '" alt="Character Image" class="img-thumbnail mt-2" width="150">';
                            } else {
                                echo '<p class="text-danger">Image not found.</p>';
                            }
                        }
                        ?>
                    </div>
                    <div class="form-group mb-3 mt-5">
                        <label for="description" class="mb-2">Description:</label>
                        <textarea class="form-control wysiwyg-editor" id="description" name="description" rows="3" ></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Character</button>
                </form>
            </div>
        </div>
    </main>
    <script src="javascript/initialize_WYSIWYG.js"></script>

</body>

<?php
include('footer.php');
?>