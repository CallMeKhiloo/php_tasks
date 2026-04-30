<?php
echo "Thanks " . ($_POST['gender'] == 'male' ? 'Mr.' : 'Ms.') . " " . $_POST['first_name'] . " " . $_POST['last_name'];
echo "<br>";
echo "Please review your information: <br> ";
echo "Name:" . $_POST['first_name'] . " " . $_POST['last_name'] . "<br>";
echo "Address:" . $_POST['address'] . "<br>";
echo "Your Skills: <br>";
foreach ($_POST['skills'] as $skill){
    echo $skill . "<br>";
};
echo "Department:" . $_POST['department'] . "<br>";
?>