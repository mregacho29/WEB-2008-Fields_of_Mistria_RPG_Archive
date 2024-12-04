<?php
require('functions.php');
require('connect.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Access denied. You must be logged in to delete characters.";
    header("refresh:5;url=view_character.php"); // Redirect after 5 seconds
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
    echo "Access denied. Only admin users can delete characters.";
    header("refresh:3;url=view_character.php"); // Redirect after 5 seconds
    exit;
}

// Check if the character ID is provided
if (isset($_GET['id'])) {
    $character_id = (int)$_GET['id'];

    // Prepare the SQL statement to delete the character
    $query = "DELETE FROM characters WHERE character_id = :character_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':character_id', $character_id, PDO::PARAM_INT);

    // Execute the statement
    if ($statement->execute()) {
        // Redirect to the view_character.php page with a success message
        header("Location: view_character.php?message=Character deleted successfully");
        exit;
    } else {
        // If there was an error, display an error message
        echo "Error deleting character.";
    }
} else {
    // If the character ID is not provided, display an error message
    echo "Invalid request.";
}
?>