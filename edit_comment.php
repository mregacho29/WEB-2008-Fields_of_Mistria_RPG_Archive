<?php
require('functions.php');
require('connect.php');
include('header.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['alert_message'] = "Access denied. You must be logged in to edit comments.";
    $_SESSION['alert_type'] = "danger";
    header("Location: index.php");
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
$query = "SELECT user_id, content FROM comments WHERE comment_id = :comment_id";
$statement = $db->prepare($query);
$statement->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
$statement->execute();
$comment = $statement->fetch(PDO::FETCH_ASSOC);

if ($user['role'] !== 'admin' && $comment['user_id'] !== $user_id) {
    echo '<div class="alert alert-danger text-center" role="alert">Access denied. Only admin users or the comment author can edit comments.</div>';
    header("refresh:3;url=index.php#comments-section"); // Redirect after 3 seconds
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $content = $_POST['content'];

    // Prepare the SQL statement to update the comment
    $query = "UPDATE comments SET content = :content WHERE comment_id = :comment_id";
    $statement = $db->prepare($query);
    $statement->bindParam(':content', $content, PDO::PARAM_STR);
    $statement->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);

    // Execute the statement
    if ($statement->execute()) {
        $_SESSION['alert_message'] = 'Comment updated successfully!';
        $_SESSION['alert_type'] = 'success';
        header('Location: index.php#comments-section');
        exit();
    } else {
        echo '<div class="alert alert-danger text-center" role="alert">Error updating comment.</div>';
    }
}
?>




<body>
    <div class="container py-4">
        <h2>Edit Comment</h2>
        <form method="POST" action="edit_comment.php?comment_id=<?php echo htmlspecialchars($comment_id); ?>" class="w-100">
            <div class="form-floating w-100">
                <textarea class="form-control" placeholder="Edit your comment here" id="floatingTextarea2" name="content" style="height: 100px" required><?php echo htmlspecialchars($comment['content']); ?></textarea>
                <label for="floatingTextarea2">Edit Comment</label>
            </div>
            <div class="float-end mt-2 pt-1">
                <button type="submit" class="btn btn-primary btn-sm">Update Comment</button>
            </div>
        </form>
    </div>
</body>

<?php
include('footer.php');
?>