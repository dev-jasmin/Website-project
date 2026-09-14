<?php
session_start();
$_SESSION = [];
session_destroy();
header('Location: /website/index.php');
exit;