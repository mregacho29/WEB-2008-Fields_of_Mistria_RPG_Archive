<?php

/*******w******** 
    
    Name: Ma Crizza Lynne Regacho
    Date: November 9, 2024
    Description: Project Prep Challenge - File Upload

****************/

require 'C:\Program Files\Ampps\www\fieldsofmistriarpgarchive\php-image-resize-master\lib\ImageResize.php';
require 'C:\Program Files\Ampps\www\fieldsofmistriarpgarchive\php-image-resize-master\lib\ImageResizeException.php';

use \Gumlet\ImageResize;

    // file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
    // Default upload path is an 'uploads' sub-folder in the current folder.
    function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
        $current_folder = dirname(__FILE__);
        
        // Build an array of paths segment names to be joins using OS specific slashes.
        $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
        
        // The DIRECTORY_SEPARATOR constant is OS specific.
        return join(DIRECTORY_SEPARATOR, $path_segments);
    }
 
     // file_is_an_image() - Checks the mime-type & extension of the uploaded file for "image-ness".
     function file_is_valid($temporary_path, $new_path) {
        $allowed_mime_types = ['image/gif', 'image/jpeg', 'image/png', 'application/pdf'];
        $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png', 'pdf'];
        
        $actual_file_extension = pathinfo($new_path, PATHINFO_EXTENSION);
        $actual_mime_type = mime_content_type($temporary_path);
        
        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid = in_array($actual_mime_type, $allowed_mime_types);
        
        return $file_extension_is_valid && $mime_type_is_valid;
    }
     
    $image_upload_detected = isset($_FILES['file']) && ($_FILES['file']['error'] === 0);
    $upload_error_detected = isset($_FILES['file']) && ($_FILES['file']['error'] > 0);
    $invalid_file_detected = false;

    
    if ($image_upload_detected) {
        $file_filename = $_FILES['file']['name'];
        $temporary_file_path = $_FILES['file']['tmp_name'];
        $new_file_path = file_upload_path($file_filename);
        
        if (file_is_valid($temporary_file_path, $new_file_path)) {
            move_uploaded_file($temporary_file_path, $new_file_path);
            
            $file_extension = pathinfo($new_file_path, PATHINFO_EXTENSION);
            if ($file_extension !== 'pdf') {
                $image = new ImageResize($new_file_path);
                
                $image->resizeToWidth(400);
                $image->save(file_upload_path(pathinfo($file_filename, PATHINFO_FILENAME) . '_medium.' . $file_extension));
                
                $image->resizeToWidth(50);
                $image->save(file_upload_path(pathinfo($file_filename, PATHINFO_FILENAME) . '_thumbnail.' . $file_extension));
            }
        } else {
            $invalid_file_detected = true;
        }
    }
?>
