<?php
/**
 * YOURLS Configuration
 */

define('YOURLS_SITE', 'https://test-production-1e3f.up.railway.app');

// База данных
define('YOURLS_DB_USER', 'root');
define('YOURLS_DB_PASS', 'jIHoLbyEuHNKuaNlVUUbDhEawiBXThVq');
define('YOURLS_DB_NAME', 'railway');
define('YOURLS_DB_HOST', 'nozomi.proxy.rlwy.net:37244');
define('YOURLS_DB_PREFIX', 'yourls_');

// Разрешаем админку без HTTPS
define('YOURLS_ALLOW_INSECURE_ADMIN', true);

// Приватный режим (только авторизованные)
define('YOURLS_PRIVATE', true);

// Пользователи
$yourls_user_passwords = [
    'admin' => 'loli2013',
];

$yourls_reserved_URL = array(
    'admin', 'login', 'logout', 'signup', 'user'
);

// Логи для отладки
define('YOURLS_DEBUG', true);

// **Optional settings**
// define( 'YOURLS_LANG', 'en' ); // язык админки
// define( 'YOURLS_HOURS_OFFSET', 0 ); // часовой пояс

// Не меняем ниже — системные пути
