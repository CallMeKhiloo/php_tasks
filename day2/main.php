<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$file_path = __DIR__ . '/data/customer.txt';
var_dump($file_path, file_exists($file_path), is_writable($file_path), substr(sprintf('%o', fileperms($file_path)), -4));
include 'utils/validators.php';

$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$address = $_POST['address'] ?? '';
$country = $_POST['country'] ?? '';
$gender = $_POST['gender'] ?? '';
if (isset($_POST['skills'])) {
    $skills = $_POST['skills'];
} else {
    $skills = [];
}
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$department = $_POST['department'] ?? '';

validate_string($first_name, 'First Name');
validate_string($last_name, 'Last Name');
validate_empty($address, 'Address');
validate_empty($country, 'Country');
validate_empty($gender, 'Gender');
validate_email($email);
validate_empty($password, 'Password');
validate_empty($skills, 'Skills');
validate_empty($department, 'Department');

$file_path = 'data/customer.txt';
$file_handler = fopen($file_path, 'a');

if ($file_handler) {
    fwrite($file_handler, "$first_name\n");
    fwrite($file_handler, "$last_name\n");
    fwrite($file_handler, "$address\n");
    fwrite($file_handler, "$country\n");
    fwrite($file_handler, "$gender\n");
    foreach ($skills as $skill) {
        fwrite($file_handler, "$skill, ");
    }
    fwrite($file_handler, "\n");
    fwrite($file_handler, "$email\n");
    fwrite($file_handler, "$password\n");
    fwrite($file_handler, "$department\n");
} else {
    echo "Unable to open file!";
}

fclose($file_handler);

include 'views/table.php';
