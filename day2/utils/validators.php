<?php
function validate_string(&$string, $field_name)
{
    if (!validate_empty($string, $field_name)) {
        if (preg_match('/[0-9]/', $string)) {
            $string = "$field_name should not contain numbers";
        } elseif (preg_match('/[!@#$%^&*()_+{}|:<>?~`]/', $string)) {
            $string = "$field_name should not contain special characters";
        } elseif (preg_match('/\s/', $string)) {
            $string = strtok($string, ' ');
        }
    }
}

function validate_empty(&$string, $field_name)
{
    if (is_array($string) && count($string) === 0) {
        $string = "$field_name are required";
        return false;
    }
    if (empty($string)) {
        $string = "$field_name is required";
        return true;
    }
    return false;
}

function validate_email(&$email)
{
    if (!validate_empty($email, 'Email')) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = "Invalid email format";
        }
    }
}
