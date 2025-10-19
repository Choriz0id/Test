<?php
// Основной URL твоего YOURLS
define('YOURLS_SITE', 'https://test-production-1e3f.up.railway.app');

// Параметры базы данных
define('YOURLS_DB_USER', 'root');
define('YOURLS_DB_PASS', 'jIHoLbyEuHNKuaNlVUUbDhEawiBXThVq');
define('YOURLS_DB_NAME', 'railway');
define('YOURLS_DB_HOST', 'nozomi.proxy.rlwy.net');
define('YOURLS_DB_PORT', 37244); // порт базы
define('YOURLS_DB_PREFIX', 'yourls_');

// Настройка админки
$yourls_user_passwords = [
    'admin' => 'loli2013',
];

// Безопасность
define('YOURLS_PRIVATE', true);
define('YOURLS_COOKIEKEY', 'aSuperRandomSecretKey123456789!@#');

// Режим отладки
define('YOURLS_DEBUG', true);
define('YOURLS_DEBUG_DISPLAY', true);