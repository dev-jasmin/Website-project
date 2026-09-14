<?php
header('Content-Type: application/json');
require __DIR__ . '/../database/config.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'invalid_input']);
    exit;
}

$allowedSubjects = ['scent', 'order', 'press', 'other'];
if (!in_array($subject, $allowedSubjects, true)) {
    $subject = 'other';
}

try {
    $pdo = getConnection();

    $stmt = $pdo->prepare(
        'INSERT INTO contact_message (name, email, subject, message)
         VALUES (:name, :email, :subject, :message)'
    );
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':subject', $subject);
    $stmt->bindValue(':message', $message);
    $stmt->execute();
} catch (PDOException $e) {
    error_log('Contact message insert failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'database_error']);
    exit;
}

echo json_encode(['success' => true]);