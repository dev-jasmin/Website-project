<?php

function validateRegisterInput($post) {
    $errors = [];

    $username = trim($post['username'] ?? '');
    $email = trim($post['email'] ?? '');
    $password = $post['password'] ?? '';
    $confirmPassword = $post['confirm_password'] ?? '';

    if ($username === '') {
        $errors[] = 'Username is required.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'A valid email is required.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    return [
        'errors' => $errors,
        'data' => [
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ],
    ];
}

function validateLoginInput($post) {
    $errors = [];

    $usernameOrEmail = trim($post['username_or_email'] ?? '');
    $password = $post['password'] ?? '';

    if ($usernameOrEmail === '') {
        $errors[] = 'Username or email is required.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    return [
        'errors' => $errors,
        'data' => [
            'username_or_email' => $usernameOrEmail,
            'password' => $password,
        ],
    ];
}
