<?php
require('connect.php');
include('functions.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Access denied. You must be logged in to delete events.";
    header("refresh:5;url=events.php"); // Redirect after 5 seconds
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
    echo "Access denied. Only admin users can delete events.";
    header("refresh:3;url=events.php"); // Redirect after 3 seconds
    exit;
}

// Fetch the event ID
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Delete the event from the database
$query = "DELETE FROM events WHERE event_id = :event_id";
$statement = $db->prepare($query);
$statement->bindParam(':event_id', $event_id, PDO::PARAM_INT);

if ($statement->execute()) {
    $_SESSION['alert_message'] = "Event deleted successfully";
    $_SESSION['alert_type'] = "success";
} else {
    $_SESSION['alert_message'] = "Error: Could not delete event.";
    $_SESSION['alert_type'] = "danger";
}

header("Location: events.php");
exit;
?>