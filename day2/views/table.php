<?php
$file_path = __DIR__ . '/../data/customer.txt';
$file_handler = fopen($file_path, 'r');
if ($file_handler) {
    $customer_data = [];
    while (!feof($file_handler)) {
        $line = fgets($file_handler);
        $customer_data[] = $line;
    }
    echo "<table class='table table-bordered'>";
    echo "<tr><th>First Name</th><td>{$customer_data[0]}</td></tr>";
    echo "<tr><th>Last Name</th><td>{$customer_data[1]}</td></tr>";
    echo "<tr><th>Address</th><td>{$customer_data[2]}</td></tr>";
    echo "<tr><th>Country</th><td>{$customer_data[3]}</td></tr>";
    echo "<tr><th>Gender</th><td>{$customer_data[4]}</td></tr>";
    echo "<tr><th>Skills</th><td>{$customer_data[5]}</td></tr>";
    echo "<tr><th>Email</th><td>{$customer_data[6]}</td></tr>";
    echo "<tr><th>Password</th><td>{$customer_data[7]}</td></tr>";
    echo "<tr><th>Department</th><td>{$customer_data[8]}</td></tr>";
    echo "</table>";
    fclose($file_handler);
}
