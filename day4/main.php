<?php
require 'utils/validators.php';
require 'utils/config.php';
require 'controllers/db_controller.php';


$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$room_no = $_POST['Room_No'] ?? '';
$ext = $_POST['Ext.'] ?? '';
$profile_picture = $_FILES['profile_picture'] ?? null;

if (validate_empty($email, 'Email') || validate_email($email)) {
    echo "<h1>$email</h1>";
    return;
}

if (validate_empty($confirm_password, 'Confirm Password') || validate_empty($password, 'Password') || validate_confirm_password($confirm_password, $password)) {
    echo "password error: passwords do not match or are empty";
    return;
}

move_uploaded_file($profile_picture['tmp_name'], 'uploads/' . $profile_picture['name']);


$pdo = connect_db();

if (create_users_table($pdo)) {
    echo "Users table created successfully.";
} else {
    echo "Failed to create users table.";
}

if (insert_user($pdo, $name, $email, $password, $room_no, $ext, $profile_picture['name'])) {
    echo "User inserted successfully.";
} else {
    echo "Failed to insert user.";
}

header('Location: views/users_table.php');