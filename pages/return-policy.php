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
  <title>Return Policy | Ember</title>
  <link rel="stylesheet" href="../styles/static-page.css" />
</head>
<body>
  <div class="static-page">
    <a class="back-link" href="/website/index.php">Back to Ember</a>
    <h1>Return Policy</h1>
    <p>
      Due to the nature of fragrance products, we accept returns only on unopened, unused items within 7 days of delivery. Opened or used items cannot be returned for hygiene reasons.
    </p>
  </div>

</body>
</html>