<?php

$regexRules = [
    'fullName' => '/^[A-Za-z]+([ \'-][A-Za-z]+)*$/',
    'username' => '/^[A-Za-z][A-Za-z0-9_]{3,15}$/',
    'email'    => '/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',
    'password' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/',
    'phone'    => '/^[6-9]\d{9}$/',
    'age'      => '/^(?:[1-9]|[1-9]\d|1[01]\d|120)$/',
    'city'     => '/^(?=.*[A-Za-z])[A-Za-z ]{2,40}$/',
    'htmlTag'  => '/<[^>]*>/'
];

function validateField($fieldName, $value) {
    global $regexRules;
    
    if ($fieldName === 'fullName') {
        if (strlen($value) < 2 || strlen($value) > 50) return false;
        return preg_match($regexRules['fullName'], $value) === 1;
    }
    if ($fieldName === 'username') return preg_match($regexRules['username'], $value) === 1;
    if ($fieldName === 'email') return preg_match($regexRules['email'], $value) === 1;
    if ($fieldName === 'password') return preg_match($regexRules['password'], $value) === 1;
    if ($fieldName === 'phone') return preg_match($regexRules['phone'], $value) === 1;
    if ($fieldName === 'age') return preg_match($regexRules['age'], $value) === 1;
    if ($fieldName === 'city') return preg_match($regexRules['city'], $value) === 1;
    if ($fieldName === 'comment') {
        if (strlen($value) < 1 || strlen($value) > 500) return false;
        return preg_match($regexRules['htmlTag'], $value) !== 1;
    }
    return false;
}
?>
