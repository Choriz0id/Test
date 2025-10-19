<?php
// YOURLS configuration file

// === Основной домен твоего YOURLS ===
// Замени на адрес твоего проекта Railway:
define('YOURLS_SITE', 'https://railway.com/project/f5b4a20b-f63b-46e6-8f62-8b57c51b2123/service/3e93e8c1-dc42-4a67-9f21-0a1f9a0604b3/database?environmentId=e66e669b-a2dc-4719-b7f3-2aa63e8906ab');

// === Параметры базы данных ===
define('YOURLS_DB_USER', 'root');
define('YOURLS_DB_PASS', 'jIHoLbyEuHNKuaNlVUUbDhEawiBXThVq');
define('YOURLS_DB_NAME', 'railway');
define('YOURLS_DB_HOST', 'nozomi.proxy.rlwy.net:37244');
define('YOURLS_DB_PREFIX', 'yourls_');

// === Общие настройки ===
define('YOURLS_HOURS_OFFSET', 0);
define('YOURLS_LANG', ''); // язык (пусто = английский)
define('YOURLS_UNIQUE_URLS', true);

// === Доступ в админку ===
$yourls_user_passwords = [
    'admin' => 'loli2013', // логин и пароль
];

// === Безопасность ===
define('YOURLS_PRIVATE', true);
define('YOURLS_COOKIEKEY', 'aSuperRandomSecretKey123456789!@#');

// === Режим отладки ===
define('YOURLS_DEBUG', false);
