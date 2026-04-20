<?php

declare(strict_types=1);

// Force la route front pour reproduire exactement le rendu front.
$_GET['route'] = 'front';

require __DIR__ . '/index.php';
