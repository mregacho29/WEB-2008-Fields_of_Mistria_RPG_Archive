<?php

/*******w******** 
    
    Name: Ma Crizza Lynne Regacho
    Date: 2024-09-28
    Description: This is the delete php file for Assignment 3.

****************/

require('connect.php');
require('authenticate.php');
require('functions.php');

// Initialize confirmation variable.
$confirmation = false;

// Debugging: Check the POST data
echo "<pre>";
print_r($_POST);
echo "</pre>";

// DELETE: if delete command is received
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['character_id']) && $_POST['command'] == "Delete") {
    // Sanitize user input
    $character_id = filter_input(INPUT_POST, 'character_id', FILTER_VALIDATE_INT);

    // Check if character_id is valid
    if (!$character_id) {
        echo "Invalid character ID.";
        exit;
    }

    // Query to check if the user is an admin
    $user_id = $_SESSION['user_id'];
    $query = "SELECT role FROM users WHERE user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['role'] !== 'admin') {
        die("Access denied: user is not an admin.");
    }

    
    // Query to get the file path of the character
    $query = "SELECT image FROM characters WHERE character_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $character_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        die("Character not found.");
    }

    $image = $result['image'];

    // Debugging: Check if the file path is correct
    echo "File path: " . $image . "<br>";

    // Build the parameterized SQL query to delete the character
    $query = "DELETE FROM characters WHERE character_id = ? LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $character_id, PDO::PARAM_INT);


    // Execute the statement
    if ($stmt->execute()) {
        // Delete the file from the server
        if (file_exists($image)) {
            if (!unlink($image)) {
                die("Failed to delete file.");
            }
        } else {
            die("File not found: " . $image);
        }
        $confirmation = "Character and associated file deleted successfully.";
    } else {
        // Debugging: Check for SQL errors
        $errorInfo = $stmt->errorInfo();
        $confirmation = "Failed to delete character from database. SQL Error: " . $errorInfo[2];
    }
} else {
    echo "No POST data received.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Delete Character</title>
</head>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <?php if($confirmation): ?>
        <h2><?= $confirmation ?></h2>
    <?php else: ?>
        <h1>An error occurred while processing your request.</h1>
    <?php endif ?>
    <a href="index.php">Return Home</a>
</body>
</html>