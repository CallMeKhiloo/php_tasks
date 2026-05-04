<?php
require 'utils/validators.php';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$room_no = $_POST['Room_No'] ?? '';
$ext = $_POST['Ext.'] ?? '';
$profile_picture = $_FILES['profile_picture'] ?? null;

var_dump($profile_picture);

if (validate_empty($email, 'Email') || validate_email($email)) {
    echo "<h1>$email</h1>";
    return;
}

if (validate_empty($confirm_password, 'Confirm Password') || validate_empty($password, 'Password') || validate_confirm_password($confirm_password, $password)) {
    echo "password error: passwords do not match or are empty";
    return;
}

move_uploaded_file($profile_picture['tmp_name'], 'uploads/' . $profile_picture['name']);

$file_handler = fopen('data/users.txt', 'a');

fwrite($file_handler, "Name: $name\n");
fwrite($file_handler, "Email: $email\n");
fwrite($file_handler, "Password: $password\n");
fwrite($file_handler, "Profile Picture: " . $profile_picture['name'] . "\n");

fclose($file_handler);

include 'views/login.html';
