<?php
require('functions.php');


// Generate a random CAPTCHA code
$captcha_code = '';
$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
$characters_length = strlen($characters);
for ($i = 0; $i < 6; $i++) {
    $captcha_code .= $characters[rand(0, $characters_length - 1)];
}

// Store the CAPTCHA code in a session variable
$_SESSION['captcha_code'] = $captcha_code;

// Create a CAPTCHA image
$captcha_image = imagecreatetruecolor(150, 50);
$background_color = imagecolorallocate($captcha_image, 255, 255, 255);
$text_color = imagecolorallocate($captcha_image, 0, 0, 0);

// Fill the background
imagefilledrectangle($captcha_image, 0, 0, 150, 50, $background_color);

// Add the CAPTCHA text
imagestring($captcha_image, 5, 50, 15, $captcha_code, $text_color);

// Output the image
header('Content-Type: image/png');
imagepng($captcha_image);

// Free up memory
imagedestroy($captcha_image);
?>