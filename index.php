<?php
// Root index.php - routes to router.php for proper handling
error_log("ROOT INDEX.PHP: Routing request to router.php - " . ($_SERVER['REQUEST_URI'] ?? 'no URI'));
require_once __DIR__ . '/router.php';
?>