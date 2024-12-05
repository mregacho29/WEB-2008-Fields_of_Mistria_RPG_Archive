<?php
require('connect.php');
include('functions.php');
include('header.php');

// Fetch all events from the database
$query = "SELECT e.event_id, e.character_id, e.event_name, e.description, e.heart_level, c.name AS character_name 
          FROM events e 
          JOIN characters c ON e.character_id = c.character_id 
          ORDER BY e.event_id DESC";
$statement = $db->prepare($query);
$statement->execute();
$events = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4">Existing Events</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Event ID</th>
                        <th>Character</th>
                        <th>Event Name</th>
                        <th>Description</th>
                        <th>Heart Level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo $event['event_id']; ?></td>
                            <td><?php echo htmlspecialchars($event['character_name']); ?></td>
                            <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                            <td><?php echo htmlspecialchars($event['description']); ?></td>
                            <td><?php echo htmlspecialchars($event['heart_level']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>