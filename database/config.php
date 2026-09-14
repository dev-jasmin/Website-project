<?php

function getConnection() {
    $host = 'localhost';
    $dbname = 'ember_db';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $user,
            $pass
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    die('Something went wrong. Please try again later.');
    }
}   

