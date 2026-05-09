<?php

function validate_empty(&$string, $field_name)
{
    if (empty($string)) {
        $string = "$field_name is required";
        return true;
    }
    return false;
}

function validate_email(&$email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match('/^[a-zA-Z0-9._%+-]+@example\.com$/', $email)) {
        $email = "Invalid email format";
        return true;
    }
    return false;
}

function validate_confirm_password(&$confirm_password, &$password)
{

    if ($confirm_password !== $password) {
        $confirm_password = "Passwords do not match";
        return true;
    }
    return false;
}
