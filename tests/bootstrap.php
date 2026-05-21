<?php

declare(strict_types=1);

/*
| Antes de cargar Laravel / Dotenv: forzar MySQL para tests (migraciones con SET).
| Ajusta DB_* aquí si tu XAMPP usa otro usuario o contraseña.
*/
foreach ([
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'pulsotron_testing',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '',
] as $key => $value) {
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
    putenv("{$key}={$value}");
}

require dirname(__DIR__).'/vendor/autoload.php';
