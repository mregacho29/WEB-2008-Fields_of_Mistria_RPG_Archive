<?php
require('connect.php');
include('functions.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Access denied. You must be logged in to delete comments.";
    header("refresh:5;url=index.php#comments-section"); // Redirect after 5 seconds
    exit;
}

// Fetch the user's role from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM users WHERE user_id = :user_id";
$statement = $db->prepare($query);
$statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Fetch the comment ID
$comment_id = isset($_GET['comment_id']) ? intval($_GET['comment_id']) : 0;

// Check if the user is an admin or the author of the comment
$query = "SELECT user_id FROM comments WHERE comment_id = :comment_id";
$statement = $db->prepare($query);
$statement->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
$statement->execute();
$comment = $statement->fetch(PDO::FETCH_ASSOC);

if ($user['role'] !== 'admin' && $comment['user_id'] !== $user_id) {
    echo "Access denied. Only admin users or the comment author can delete comments.";
    header("refresh:3;url=index.php#comments-section"); // Redirect after 3 seconds
    exit;
}

// Delete the comment from the database
$query = "DELETE FROM comments WHERE comment_id = :comment_id";
$statement = $db->prepare($query);
$statement->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);

if ($statement->execute()) {
    $_SESSION['alert_message'] = "Comment deleted successfully";
    $_SESSION['alert_type'] = "success";
} else {
    $_SESSION['alert_message'] = "Error: Could not delete comment.";
    $_SESSION['alert_type'] = "danger";
}

header("Location: index.php#comments-section");
exit;
?>