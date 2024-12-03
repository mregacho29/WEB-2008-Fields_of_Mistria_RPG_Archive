<?php
// create_character.php

require_once('connect.php');
require_once('functions.php');
require_once('file_upload.php'); // Include the file upload functionality

secure(); // Ensure the user is logged in

include('header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image_path = '';

    if ($image_upload_detected) {
        $file_filename = $_FILES['file']['name'];
        $temporary_file_path = $_FILES['file']['tmp_name'];
        $new_file_path = file_upload_path($file_filename);

        if (file_is_valid($temporary_file_path, $new_file_path)) {
            move_uploaded_file($temporary_file_path, $new_file_path);
            $image_path = $new_file_path;
        } else {
            $invalid_file_detected = true;
        }
    }

    $query = "INSERT INTO characters (name, description, image) VALUES (:name, :description, :image)";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':image', $image);
    $statement->execute();

    set_message("Character created successfully!");
    header('Location: index.php');
    exit;
}
?>

<form action="file_upload.php" method="post" enctype="multipart/form-data">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" required>
    
    <label for="description">Description</label>
    <textarea id="description" name="description" required></textarea>
    
    <label for="image">Image</label>
    <input type="file" id="image" name="file">
    
    <button type="submit">Create Character</button>
</form>
    
    <?php if ($upload_error_detected): ?>
        <p>Error Number: <?= $_FILES['file']['error'] ?></p>
    <?php elseif ($invalid_file_detected): ?>
        <p>The uploaded file is not a valid file type. Only JPG, PNG, GIF images, and PDF documents are allowed.</p>
    <?php elseif ($image_upload_detected): ?>
        <p>Client-Side Filename: <?= $_FILES['file']['name'] ?></p>
        <p>Apparent Mime Type: <?= $_FILES['file']['type'] ?></p>
        <p>Size in Bytes: <?= $_FILES['file']['size'] ?></p>
        <p>Temporary Path: <?= $_FILES['file']['tmp_name'] ?></p>
    <?php endif ?>



<?php include('footer.php'); ?>