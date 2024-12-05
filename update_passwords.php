<?php
require('connect.php');
require('functions.php');

// Fetch all users
$stm = $db->prepare('SELECT user_id, password FROM users');
$stm->execute();
$users = $stm->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    // Hash the existing plain text password
    $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);

    // Update the user's password in the database
    $update_stm = $db->prepare('UPDATE users SET password = ? WHERE user_id = ?');
    $update_stm->bindParam(1, $hashed_password, PDO::PARAM_STR);
    $update_stm->bindParam(2, $user['user_id'], PDO::PARAM_INT);
    $update_stm->execute();
}

echo 'Passwords updated successfully.';
?>