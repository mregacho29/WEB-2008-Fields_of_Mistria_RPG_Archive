<?php
define('DB_DSN', 'mysql:host=localhost;dbname=fields_of_mistria;charset=utf8');
define('DB_USER', 'MRegacho');
define('DB_PASS', 'Crizza29!');

try {
    $db = new PDO(DB_DSN, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error: " . $e->getMessage();
    die();
}
?>