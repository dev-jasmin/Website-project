<?php
header('Content-Type: application/json');
require __DIR__ . '/../database/config.php';

$email = trim($_POST['email'] ?? '');

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_email']);
    exit;
}

$pdo = getConnection();

try {
    $stmt = $pdo->prepare('INSERT INTO newsletter_subscriber (email) VALUES (:email)');
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    // Duplicate email (already subscribed) — treat as success, no need to error
    echo json_encode(['success' => true, 'already_subscribed' => true]);
}