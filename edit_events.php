<?php
require('connect.php');
include('functions.php');
include('header.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Access denied. You must be logged in to edit events.";
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
    echo '<div class="alert alert-danger text-center" role="alert">Access denied. Only admin users can edit characters.</div>';
    header("refresh:3;url=view_character.php"); // Redirect after 3 seconds
    exit;
}




// Fetch the event details
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT * FROM events WHERE event_id = :event_id";
$statement = $db->prepare($query);
$statement->bindParam(':event_id', $event_id, PDO::PARAM_INT);
$statement->execute();
$event = $statement->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Event not found.";
    header("refresh:5;url=events.php"); // Redirect after 5 seconds
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $character_id = intval($_POST['character_id']);
    $event_name = htmlspecialchars(trim($_POST['event_name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $heart_level = htmlspecialchars(trim($_POST['heart_level']));

    // Validate input
    if (empty($character_id) || empty($event_name) || empty($description) || empty($heart_level)) {
        $error_message = 'All fields are required.';
    } else {
        // Update the event in the database
        $query = "UPDATE events SET character_id = ?, event_name = ?, description = ?, heart_level = ? WHERE event_id = ?";
        $statement = $db->prepare($query);
        $statement->bindParam(1, $character_id, PDO::PARAM_INT);
        $statement->bindParam(2, $event_name, PDO::PARAM_STR);
        $statement->bindParam(3, $description, PDO::PARAM_STR);
        $statement->bindParam(4, $heart_level, PDO::PARAM_STR);
        $statement->bindParam(5, $event_id, PDO::PARAM_INT);

        if ($statement->execute()) {
            $_SESSION['alert_message'] = "Event updated successfully";
            $_SESSION['alert_type'] = "success";
            header("Location: events.php");
            exit;
        } else {
            $error_message = 'Database error: Could not update event.';
        }
    }
}

// Fetch all characters for the dropdown
$query = "SELECT character_id, name FROM characters ORDER BY name ASC";
$statement = $db->prepare($query);
$statement->execute();
$characters = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <main>
        <div class="container py-4">
            <!-- Breadcrumb Begin -->
            <nav class="breadcrumb-nav py-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="categories.php">Categories</a></li>
                    <li class="breadcrumb-item"><a href="events.php">Events</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
            <!-- Breadcrumb End -->

            <hr class="featurette-divider mt-5 mb-3">

            <div class="container py-4">
                <h1 class="mb-4">Edit Event</h1>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="edit_events.php?id=<?php echo $event_id; ?>">
                    <div class="mb-3">
                        <label for="character_id" class="form-label">Character</label>
                        <select id="character_id" name="character_id" class="form-control" required>
                            <option value="">Select Character</option>
                            <?php foreach ($characters as $character): ?>
                                <option value="<?php echo $character['character_id']; ?>" <?php if ($character['character_id'] == $event['character_id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($character['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="event_name" class="form-label">Event Name</label>
                        <input type="text" id="event_name" name="event_name" class="form-control" value="<?php echo htmlspecialchars($event['event_name']); ?>" required />
                    </div>

                    <div class="mb-3">
                        <label for="heart_level" class="form-label">Heart Level</label>
                        <input type="text" id="heart_level" name="heart_level" class="form-control" value="<?php echo htmlspecialchars($event['heart_level']); ?>" required />
                    </div>

                    <div class="form-group mb-3">
                        <label for="description" class="mb-2">Description:</label>
                        <textarea class="form-control wysiwyg-editor" id="description" name="description" rows="3" ><?php echo htmlspecialchars_decode($event['description']); ?></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mt-4 py-2 px-5">Update Event</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <!-- Include the WYSIWYG editor initialization script -->
    <script src="javascript/initialize_WYSIWYG.js"></script>
</body>


<?php
include('footer.php');
?>