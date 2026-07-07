<?php

// Memaksa Laravel menulis file cache ke direktori sementara (/tmp) yang writable di Vercel
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('VIEW_COMPILED_PATH=/tmp');

// Mengubah session dan cache agar tidak menggunakan file system
putenv('CACHE_STORE=array');
putenv('SESSION_DRIVER=cookie');

// Forward request ke standard Laravel public/index.php
require __DIR__ . '/../public/index.php';