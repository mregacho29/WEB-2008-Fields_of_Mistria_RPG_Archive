<?php

/*******w******** 
    
    Name: Ma Crizza Lynne Regacho
    Date: 2024-09-28
    Description: This is the delete php file for Assignment 3.

****************/

require('connect.php');
require('authenticate.php');
require('functions.php');

// DELETE: if delete command is received
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['character_id']) && $_POST['command'] == "Delete") {
    // Sanitize user input
    $character_id = filter_input(INPUT_POST, 'character_id', FILTER_VALIDATE_INT);

    // Check if character_id is valid
    if (!$character_id) {
        $_SESSION['alert_message'] = "Invalid character ID.";
        $_SESSION['alert_type'] = "danger";
        header("Location: view_character.php");
        exit;
    }

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['alert_message'] = "Access denied: user not logged in.";
        $_SESSION['alert_type'] = "danger";
        header("Location: view_character.php");
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
        $_SESSION['alert_message'] = "Access denied: user is not an admin.";
        $_SESSION['alert_type'] = "danger";
        header("Location: view_character.php");
        exit;
    }

    // Query to get the file path of the character
    $query = "SELECT image FROM characters WHERE character_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $character_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        $_SESSION['alert_message'] = "Character not found.";
        $_SESSION['alert_type'] = "danger";
        header("Location: view_character.php");
        exit;
    }

    $image = $result['image'];

    // Build the parameterized SQL query to delete the character
    $query = "DELETE FROM characters WHERE character_id = ? LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $character_id, PDO::PARAM_INT);

    // Execute the statement
    if ($stmt->execute()) {
        // Delete the file from the server
        if (file_exists($image)) {
            if (!unlink($image)) {
                $_SESSION['alert_message'] = "Failed to delete file.";
                $_SESSION['alert_type'] = "danger";
                header("Location: view_character.php");
                exit;
            }
        } else {
            $_SESSION['alert_message'] = "File not found: " . $image;
            $_SESSION['alert_type'] = "danger";
            header("Location: view_character.php");
            exit;
        }
        $_SESSION['alert_message'] = "Character and associated file deleted successfully.";
        $_SESSION['alert_type'] = "success";
    } else {
        // Debugging: Check for SQL errors
        $errorInfo = $stmt->errorInfo();
        $_SESSION['alert_message'] = "Failed to delete character from database. SQL Error: " . $errorInfo[2];
        $_SESSION['alert_type'] = "danger";
    }
    header("Location: view_character.php");
    exit;
} else {
    $_SESSION['alert_message'] = "No POST data received.";
    $_SESSION['alert_type'] = "danger";
    header("Location: view_character.php");
    exit;
}
?>
