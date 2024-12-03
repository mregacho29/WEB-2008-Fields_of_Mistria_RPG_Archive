<?php
// functions.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('secure')) {
    function secure() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['alert_message'] = "Please login first to view this page.";
            header('Location: login.php');
            die();
        }
    }
}

if (!function_exists('set_message')) {
    function set_message($message) {
        $_SESSION['message'] = $message;
    }
}

if (!function_exists('get_message')) {
    function get_message() {
        if (isset($_SESSION['message'])) {
            echo "<script type='text/javascript'> showToast('" . $_SESSION['message'] . "','top right' , 'success') </script>";
            unset($_SESSION['message']);
        }
    }
}