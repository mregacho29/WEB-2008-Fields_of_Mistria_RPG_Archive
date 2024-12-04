<?php
session_start(); // Ensure session is started
require('functions.php');
require('connect.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Access denied. You must be logged in to edit characters.";
    header("refresh:3;url=view_character.php"); // Redirect after 5 seconds
    exit;
}

// Fetch the user's role from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM users WHERE user_id = :user_id";
$statement = $db->prepare($query);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Check if the user has an admin role
if ($user['role'] !== 'admin') {
    echo "Access denied. Only admin users can edit characters.";
    header("refresh:5;url=view_character.php"); // Redirect after 5 seconds
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
        $image = $_POST['image'];




       
        // Prepare the SQL statement to update the character
        $query = "UPDATE characters SET name = :name, description = :description, image = :image, updated_at = NOW() WHERE character_id = :character_id";
        $statement = $db->prepare($query);
        $statement->bindParam(':name', $name, PDO::PARAM_STR);
        $statement->bindParam(':description', $description, PDO::PARAM_STR);
        $statement->bindParam(':image', $image, PDO::PARAM_STR);
        $statement->bindParam(':character_id', $character_id, PDO::PARAM_INT);
        

        // Execute the statement
        if ($statement->execute()) {
            // Redirect to the view_character.php page with a success message
            header("Location: view_character.php?message=Character updated successfully");
            exit;
        } else {
            // If there was an error, display an error message
            echo "Error updating character.";
        }
    }
} else {
    // If the character ID is not provided, display an error message
    echo "Invalid request.";
}
?>


<body>
    <h1>Edit Character</h1>
    <form method="post" action="edit_character.php?id=<?php echo $character_id; ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($character['name']); ?>" required>
        <br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($character['description']); ?></textarea>
        <br>
        <label for="image">Image:</label>
        <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($character['image']); ?>" required>
        <br>
        <button type="submit">Update Character</button>
    </form>
</body>
