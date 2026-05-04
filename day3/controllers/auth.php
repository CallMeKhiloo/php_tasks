<?php
require '../utils/validators.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (validate_empty($email, 'Email') || validate_empty($password, 'Password')) {
    echo "<h1>email or password is invalid</h1>";
    return;
}

if (validate_email($email)) {
    echo "<h1>$email</h1>";
    return;
}

$file_handler = fopen('../data/users.txt', 'r');
$found = false;
$profile_picture = '';

while (!feof($file_handler)) {
    $line = fgets($file_handler);
    if (str_contains($line, "Email: $email")) {
        $found = true;
        $password_line = fgets($file_handler);
        if (str_contains($password_line, "Password: $password")) {
            echo "<h1>Login successful</h1>";
            $profile_picture_line = fgets($file_handler);
            $profile_picture = str_replace("Profile Picture: ", "", $profile_picture_line);
            echo "<img src='../uploads/$profile_picture' alt='Profile Picture' />";
        } else {
            echo "<h1>Invalid password</h1>";
        }
        break;
    }
}

if (!$found) {
    echo "<h1>User not found</h1>";
}
fclose($file_handler);

session_start();
$_SESSION['email'] = $email;
$_SESSION['profile_picture'] = $profile_picture;

echo "<h2>welcome $_SESSION[email]</h2>";
