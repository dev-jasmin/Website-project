<?php
session_start();
require __DIR__ . '/../database/config.php';
require_once __DIR__ . '/../includes/current-user.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /website/index.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Delivery | Ember</title>
  <link rel="stylesheet" href="../styles/static-page.css" />
</head>
<body>
  <div class="static-page">
    <a class="back-link" href="/website/index.php">Back to Ember</a>
    <h1>Delivery</h1>
    <p>
    We deliver nationwide within the Philippines. Orders typically arrive within 3–5 business days.
    Orders over ₱500 qualify for free delivery, smaller orders include a flat ₱60 delivery fee.
    </p>
  </div>

</body>
</html>