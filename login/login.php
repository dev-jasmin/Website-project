<?php
session_start();
require __DIR__ . '/../database/config.php';
require __DIR__ . '/../includes/validation.php';

$returnTo = $_POST['return_to'] ?? '/index.php';

if (!isset($_POST['log-in'])) {
    header('Location: ' . $returnTo);
    exit;
}

$result = validateLoginInput($_POST);
$errors = $result['errors'];

if (!empty($errors)) {
    $message = implode(' ', $errors);
    header('Location: ' . $returnTo . '?auth=login&status=error&message=' . urlencode($message));
    exit;
}

try {
    $pdo = getConnection();

    $sql = 'SELECT id, username, password FROM user WHERE username = :login OR email = :login';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':login', $result['data']['username_or_email']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($result['data']['password'], $user['password'])) {
        header('Location: ' . $returnTo . '?auth=login&status=error&message=' . urlencode('Incorrect username/email or password.'));
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    header('Location: ' . $returnTo);
    exit;
} catch (PDOException $e) {
    header('Location: ' . $returnTo . '?auth=login&status=error&message=' . urlencode('Login failed. Please try again.'));
    exit;
}
