<?php
require('connect.php');
include('functions.php');
include('header.php');


$error_message = ''; // Variable to store error message
$success_message = ''; // Variable to store success message

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
        // Insert the new event into the database
        $query = "INSERT INTO events (character_id, event_name, description, heart_level) VALUES (?, ?, ?, ?)";
        $statement = $db->prepare($query);
        $statement->bindParam(1, $character_id, PDO::PARAM_INT);
        $statement->bindParam(2, $event_name, PDO::PARAM_STR);
        $statement->bindParam(3, $description, PDO::PARAM_STR);
        $statement->bindParam(4, $heart_level, PDO::PARAM_STR);

        if ($statement->execute()) {
            $success_message = 'Event created successfully!';
        } else {
            $error_message = 'Database error: Could not create event.';
        }

        $statement->closeCursor();
    }
}

// Fetch all characters for the dropdown
$query = "SELECT character_id, name FROM characters ORDER BY name ASC";
$statement = $db->prepare($query);
$statement->execute();
$characters = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4">Create New Event</h1>

            <?php if ($error_message): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <div class="alert alert-success text-center" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="create_event.php">
                <div class="mb-3">
                    <label for="character_id" class="form-label">Character</label>
                    <select id="character_id" name="character_id" class="form-control" required>
                        <option value="">Select Character</option>
                        <?php foreach ($characters as $character): ?>
                            <option value="<?php echo $character['character_id']; ?>"><?php echo htmlspecialchars($character['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="event_name" class="form-label">Event Name</label>
                    <input type="text" id="event_name" name="event_name" class="form-control" required />
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="heart_level" class="form-label">Heart Level</label>
                    <textarea id="heart_level" name="heart_level" class="form-control" rows="3" required></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary mt-4 py-2 px-5 fixed-size">
                        Create Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>