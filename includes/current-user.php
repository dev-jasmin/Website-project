<?php
function getCurrentUserAvatar($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT profile_picture FROM user WHERE id = :id');
    $stmt->bindValue(':id', $userId);
    $stmt->execute();
    $row = $stmt->fetch();
    return !empty($row['profile_picture']) ? $row['profile_picture'] : null;
}

function isCurrentUserAdmin($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT is_admin FROM user WHERE id = :id');
    $stmt->bindValue(':id', $userId);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row && (int) $row['is_admin'] === 1;
}