<?php
if (isset($_SESSION['alert_message'])) {
  $alert_message = $_SESSION['alert_message'];
  $alert_type = isset($_SESSION['alert_type']) ? $_SESSION['alert_type'] : 'success'; // Default to success if not set
  echo '<div class="alert alert-' . $alert_type . ' alert-dismissible fade show text-center alert-overlay" role="alert">' . $alert_message . '
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
  unset($_SESSION['alert_message']);
  unset($_SESSION['alert_type']);
}

// Determine the current page and the number of comments per page
$comments_per_page = 2;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = max(0, ($current_page - 1) * $comments_per_page); // Ensure offset is not negative

// Fetch the total number of comments
$total_comments_query = "SELECT COUNT(*) FROM comments";
$total_comments_statement = $db->prepare($total_comments_query);
$total_comments_statement->execute();
$total_comments = $total_comments_statement->fetchColumn();

// Calculate the total number of pages
$total_pages = ceil($total_comments / $comments_per_page);

// Fetch comments for the current page from the database
$query = "SELECT comments.comment_id, comments.user_id, comments.content, comments.created_at, comments.likes, users.username
          FROM comments 
          JOIN users ON comments.user_id = users.user_id
          ORDER BY comments.created_at DESC
          LIMIT :offset, :comments_per_page";
$statement = $db->prepare($query);
$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
$statement->bindParam(':comments_per_page', $comments_per_page, PDO::PARAM_INT);
$statement->execute();

// Fetch all comments
$comments = $statement->fetchAll();
?>

<header class="pb-3 mb-4 border-bottom d-flex justify-content-between align-items-center mt-5">
    <a href="/" class="d-flex align-items-center text-body-emphasis text-decoration-none">
    <img src="image/categorieslogo3.gif" width="80" height="32" class="me-2" alt="Logo">
        <span class="fs-4">Comments Section</span>
    </a>
</header>
<section id="comments-section">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-12 col-lg-10 col-xl-8">

                <!-- DIV Card Starts -->
                <div class="card w-100">
                    <div class="card-body">
                    <?php foreach ($comments as $comment): ?>
                        <div class="d-flex flex-start align-items-center mt-4">
                            <img class="rounded-circle shadow-1-strong me-3"
                                src="image/dp.png" alt="avatar" width="60"
                                height="60" />
                            <div class="text-start flex-grow-1">
                                <h6 class="fw-bold text-primary mb-1"><?php echo htmlspecialchars($comment['username']); ?></h6>
                                <p class="text-muted small mb-0">
                                    <?php echo htmlspecialchars($comment['created_at']); ?>
                                </p>
                            </div>

                            <!-- EDIT AND DELETE BUTTON -->
                            <div class="d-flex flex-row-reverse">
                                <a href="delete_comment.php?comment_id=<?php echo $comment['comment_id']; ?>" class="btn btn-danger btn-sm me-2">Delete</a>
                                <a href="edit_comment.php?comment_id=<?php echo $comment['comment_id']; ?>" class="btn btn-sm btn-outline-secondary btn-sm">Edit</a>
                            </div>
                            
                        </div>

                        <p class="text-start mt-3 mb-4 ms-2">
                            <?php echo htmlspecialchars($comment['content']); ?>
                        </p>

                        <!-- LIKE BUTTON -->
                        <div class="d-flex align-items-center border-bottom pb-5">
                            <form method="POST" action="index.php#comments-section" class="d-inline">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                <button type="submit" name="like" class="btn btn-outline-primary btn-sm mt-2">Like</button>
                            </form>
                            <span class="ms-2"><?php echo htmlspecialchars($comment['likes']); ?> people liked this</span>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
                <!-- DIV Card End -->

                <!-- DP Starts -->
                <div class="card-footer py-3 border-0">
                    <div class="d-flex flex-start w-100">
                    <img class="rounded-circle shadow-1-strong me-3"
                        src="image/dp.png" alt="avatar" width="40"
                        height="40" />
        
                    <!-- Comment Section Starts Here -->
                    <form method="POST" action="index.php#comments-section" class="w-100">
                        <div class="form-floating w-100">
                            <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" name="comment" style="height: 100px"></textarea>
                            <label for="floatingTextarea2">Comments</label>
                        </div>
                        <div class="float-end mt-2 pt-1">
                            <button type="submit" class="btn btn-primary btn-sm">Post comment</button>
                        </div>
                    </form>
                    <!-- Comment Section Ends Here -->
                </div>
                <!-- DP Ends -->

                <!-- Pagination Links -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($current_page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>#comments-section" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>#comments-section"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($current_page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>#comments-section" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <!-- Pagination Links End -->
            </div>
        </div>
    </div>
</section>