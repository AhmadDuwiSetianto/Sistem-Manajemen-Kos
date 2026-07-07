<?php

// 1. Paksa PHP menampilkan error mentah ke layar (Ultimate Debug)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Paksa SEMUA cache dan folder render pindah ke /tmp (satu-satunya folder yg terbuka di Vercel)
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('VIEW_COMPILED_PATH=/tmp');

// 3. Matikan segala fitur yang butuh file system
putenv('CACHE_STORE=array');
putenv('SESSION_DRIVER=cookie');
putenv('LOG_CHANNEL=stderr');

// Forward request ke standard Laravel public/index.php
require __DIR__ . '/../public/index.php';