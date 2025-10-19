<?php
// YOURLS configuration file for Railway

// === Основной домен твоего YOURLS ===
// Используй публичный URL, который выдал Railway:
define('YOURLS_SITE', 'https://test-production-1e3f.up.railway.app');

// === Параметры базы данных ===
define('YOURLS_DB_USER', 'root');
define('YOURLS_DB_PASS', 'jIHoLbyEuHNKuaNlVUUbDhEawiBXThVq');
define('YOURLS_DB_NAME', 'railway');
define('YOURLS_DB_HOST', 'nozomi.proxy.rlwy.net');
define('YOURLS_DB_PORT', 37244);
define('YOURLS_DB_PREFIX', 'yourls_');

// === Общие настройки ===
define('YOURLS_HOURS_OFFSET', 0);
define('YOURLS_LANG', ''); // язык (пусто = английский)
define('YOURLS_UNIQUE_URLS', true);

// === Доступ в админку ===
$yourls_user_passwords = [
    'admin' => 'loli2013',
];

// === Безопасность ===
define('YOURLS_PRIVATE', true);
define('YOURLS_COOKIEKEY', 'aSuperRandomSecretKey123456789!@#');

// === Режим отладки ===
define('YOURLS_DEBUG', true);        // включаем для отладки
define('YOURLS_DEBUG_DISPLAY', true);
