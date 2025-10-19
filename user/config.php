<?php
/**
 * YOURLS Configuration
 */

// **MySQL settings** - берём из твоей строки подключения
define( 'YOURLS_DB_USER', 'root' );
define( 'YOURLS_DB_PASS', 'jIHoLbyEuHNKuaNlVUUbDhEawiBXThVq' );
define( 'YOURLS_DB_NAME', 'railway' );
define( 'YOURLS_DB_HOST', 'nozomi.proxy.rlwy.net:37244' );
define( 'YOURLS_DB_PREFIX', 'yourls_' ); // можно оставить по умолчанию

// **Site options**
define( 'YOURLS_SITE', 'https://test-production-1e3f.up.railway.app' );

// **Admin user** - логин и пароль
$yourls_user_passwords = [
    'admin' => 'loli2013',
];

// **Cookie key** - случайная строка для безопасности
define( 'YOURLS_COOKIEKEY', 'r8UjW9sE2zTQ4bP1xC6kL0vF3aYdM7nH' );

// **Debug mode**
define( 'YOURLS_DEBUG', true ); // временно включаем для логов, потом можно false

// **URL shortening settings**
define( 'YOURLS_URL_CONVERT', 36 ); // 36 = a-z0-9
define( 'YOURLS_PRIVATE', true );   // true = админка приватная
define( 'YOURLS_UNIQUE_URLS', true ); // разрешаем уникальные короткие ссылки

// **Optional settings**
// define( 'YOURLS_LANG', 'en' ); // язык админки
// define( 'YOURLS_HOURS_OFFSET', 0 ); // часовой пояс

// Не меняем ниже — системные пути
