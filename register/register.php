<?php
session_start();
require __DIR__ . '/../database/config.php';
require __DIR__ . '/../includes/validation.php';

// Where to send the user back to (so the modal reopens on the same page)
$returnTo = $_POST['return_to'] ?? '/index.php';

if (!isset($_POST['sign-up'])) {
    header('Location: ' . $returnTo);
    exit;
}

$result = validateRegisterInput($_POST);
$errors = $result['errors'];

if (!empty($errors)) {
    $message = implode(' ', $errors);
    header('Location: ' . $returnTo . '?auth=signup&status=error&message=' . urlencode($message));
    exit;
}

try {
    $pdo = getConnection();

    // Make sure username/email isn't already taken
    $check = $pdo->prepare('SELECT id FROM user WHERE username = :username OR email = :email');
    $check->bindValue(':username', $result['data']['username']);
    $check->bindValue(':email', $result['data']['email']);
    $check->execute();

    if ($check->fetch()) {
        header('Location: ' . $returnTo . '?auth=signup&status=error&message=' . urlencode('Username or email already taken.'));
        exit;
    }

    $hashedPassword = password_hash($result['data']['password'], PASSWORD_DEFAULT);

    $sql = 'INSERT INTO user (username, email, password) VALUES (:username, :email, :password)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $result['data']['username']);
    $stmt->bindValue(':email', $result['data']['email']);
    $stmt->bindValue(':password', $hashedPassword);
    $stmt->execute();

    // Log the new user in immediately
    $_SESSION['user_id'] = $pdo->lastInsertId();
    $_SESSION['username'] = $result['data']['username'];

    session_regenerate_id(true);

    header('Location: ' . $returnTo);
    exit;
} catch (PDOException $e) {
    header('Location: ' . $returnTo . '?auth=signup&status=error&message=' . urlencode('Registration failed. Please try again.'));
    exit;
}
