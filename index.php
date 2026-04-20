<?php
// Keep a root front file for web servers that point to project root.
$controllerEntrypoint = __DIR__ . '/controllers/index.php';
if (!is_file($controllerEntrypoint)) {
	$controllerEntrypoint = __DIR__ . '/Controllers/index.php';
}

require_once $controllerEntrypoint;
