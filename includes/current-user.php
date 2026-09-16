<?php
function refreshCurrentUserSession($pdo) {
    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    $stmt = $pdo->prepare('SELECT id FROM user WHERE id = :id');
    $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->fetch()) {
        return true;
    }

    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
    return false;
}

function getCurrentUserAvatar($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT profile_picture FROM user WHERE id = :id');
    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return !empty($row['profile_picture']) ? $row['profile_picture'] : null;
}

function isCurrentUserAdmin($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT is_admin FROM user WHERE id = :id');
    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row && (int) $row['is_admin'] === 1;
}