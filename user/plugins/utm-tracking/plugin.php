<?php
/*
Plugin Name: UTM Tracking
Plugin URI: https://github.com/yourls/yourls
Description: Сохраняет и передаёт UTM-метки при переходе по коротким ссылкам
Version: 1.1
Author: Custom
*/

// Не запускать напрямую
if (!defined('YOURLS_ABSPATH')) die();

// Создание таблицы для хранения UTM данных
yourls_add_action('activated_utm-tracking/plugin.php', 'utm_tracking_create_table');
function utm_tracking_create_table() {
    global $ydb;
    $table = YOURLS_DB_TABLE_URL . '_utm';
    
    $sql = "CREATE TABLE IF NOT EXISTS `$table` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `keyword` varchar(200) NOT NULL,
        `utm_source` varchar(255) DEFAULT NULL,
        `utm_medium` varchar(255) DEFAULT NULL,
        `utm_campaign` varchar(255) DEFAULT NULL,
        `utm_term` varchar(255) DEFAULT NULL,
        `utm_content` varchar(255) DEFAULT NULL,
        `click_time` timestamp DEFAULT CURRENT_TIMESTAMP,
        `ip` varchar(45) DEFAULT NULL,
        `referrer` varchar(500) DEFAULT NULL,
        `user_agent` varchar(500) DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `keyword` (`keyword`),
        KEY `utm_campaign` (`utm_campaign`),
        KEY `click_time` (`click_time`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $ydb->fetchAffected($sql);
}

// Перехват редиректа для сохранения UTM данных
yourls_add_filter('redirect_location', 'utm_tracking_save_and_modify', 10, 2);
function utm_tracking_save_and_modify($location, $keyword) {
    global $ydb;
    
    // Проверяем что location не пустой
    if (empty($location)) {
        return $location;
    }
    
    // Получаем UTM параметры (используем filter_input для безопасности)
    $utm_source = filter_input(INPUT_GET, 'utm_source', FILTER_SANITIZE_SPECIAL_CHARS);
    $utm_medium = filter_input(INPUT_GET, 'utm_medium', FILTER_SANITIZE_SPECIAL_CHARS);
    $utm_campaign = filter_input(INPUT_GET, 'utm_campaign', FILTER_SANITIZE_SPECIAL_CHARS);
    $utm_term = filter_input(INPUT_GET, 'utm_term', FILTER_SANITIZE_SPECIAL_CHARS);
    $utm_content = filter_input(INPUT_GET, 'utm_content', FILTER_SANITIZE_SPECIAL_CHARS);
    
    // Если есть хотя бы один UTM параметр, сохраняем
    if ($utm_source || $utm_medium || $utm_campaign || $utm_term || $utm_content) {
        $table = YOURLS_DB_TABLE_URL . '_utm';
        
        $ip = yourls_get_IP();
        $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
        
        // Используем prepared statement через PDO
        $sql = "INSERT INTO `$table` 
                (keyword, utm_source, utm_medium, utm_campaign, utm_term, utm_content, ip, referrer, user_agent) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $ydb->fetchAffected($sql, [
                $keyword,
                $utm_source,
                $utm_medium,
                $utm_campaign,
                $utm_term,
                $utm_content,
                $ip,
                $referrer,
                $user_agent
            ]);
        } catch (Exception $e) {
            error_log("UTM Tracking: Error saving data - " . $e->getMessage());
        }
        
        // Добавляем UTM параметры к целевому URL
        $parsed = parse_url($location);
        $params = array();
        
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $params);
        }
        
        if ($utm_source) $params['utm_source'] = $utm_source;
        if ($utm_medium) $params['utm_medium'] = $utm_medium;
        if ($utm_campaign) $params['utm_campaign'] = $utm_campaign;
        if ($utm_term) $params['utm_term'] = $utm_term;
        if ($utm_content) $params['utm_content'] = $utm_content;
        
        $new_query = http_build_query($params);
        $location = $parsed['scheme'] . '://' . $parsed['host'] . 
                    (isset($parsed['path']) ? $parsed['path'] : '') . 
                    '?' . $new_query .
                    (isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '');
    }
    
    return $location;
}

// Добавляем страницу статистики в админку
yourls_add_action('plugins_loaded', 'utm_tracking_add_page');
function utm_tracking_add_page() {
    yourls_register_plugin_page('utm_stats', 'UTM Статистика', 'utm_tracking_stats_page');
}

// Страница со статистикой
function utm_tracking_stats_page() {
    global $ydb;
    
    if (!yourls_is_valid_user()) {
        die('Доступ запрещён');
    }
    
    $table = YOURLS_DB_TABLE_URL . '_utm';
    
    // Получаем статистику по кампаниям
    $sql = "SELECT utm_campaign, utm_source, utm_medium, COUNT(*) as clicks, 
                   MIN(click_time) as first_click, MAX(click_time) as last_click
            FROM `$table`
            WHERE utm_campaign IS NOT NULL
            GROUP BY utm_campaign, utm_source, utm_medium
            ORDER BY clicks DESC
            LIMIT 100";
    
    $campaigns = $ydb->fetchObjects($sql);
    
    echo '<h2>UTM Статистика</h2>';
    
    if ($campaigns) {
        echo '<table class="tblSorter" style="width:100%">
            <thead>
                <tr>
                    <th>Кампания</th>
                    <th>Источник</th>
                    <th>Канал</th>
                    <th>Клики</th>
                    <th>Первый клик</th>
                    <th>Последний клик</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($campaigns as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row->utm_campaign) . '</td>';
            echo '<td>' . htmlspecialchars($row->utm_source ?: '-') . '</td>';
            echo '<td>' . htmlspecialchars($row->utm_medium ?: '-') . '</td>';
            echo '<td>' . $row->clicks . '</td>';
            echo '<td>' . date('d.m.Y H:i', strtotime($row->first_click)) . '</td>';
            echo '<td>' . date('d.m.Y H:i', strtotime($row->last_click)) . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
    } else {
        echo '<p>Нет данных UTM</p>';
    }
    
    // Статистика по источникам
    $sql_sources = "SELECT utm_source, COUNT(*) as clicks
                    FROM `$table`
                    WHERE utm_source IS NOT NULL
                    GROUP BY utm_source
                    ORDER BY clicks DESC
                    LIMIT 20";
    
    $sources = $ydb->fetchObjects($sql_sources);
    
    if ($sources) {
        echo '<h3 style="margin-top: 30px;">Топ источников</h3>';
        echo '<table class="tblSorter" style="width:100%">
            <thead>
                <tr>
                    <th>Источник (utm_source)</th>
                    <th>Клики</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($sources as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row->utm_source) . '</td>';
            echo '<td>' . $row->clicks . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
    }
    
    // Статистика по коротким ссылкам
    $sql_keywords = "SELECT u.keyword, u.url, u.title, COUNT(utm.id) as utm_clicks
                     FROM " . YOURLS_DB_TABLE_URL . " u
                     LEFT JOIN `$table` utm ON u.keyword = utm.keyword
                     GROUP BY u.keyword
                     HAVING utm_clicks > 0
                     ORDER BY utm_clicks DESC
                     LIMIT 50";
    
    $keywords = $ydb->fetchObjects($sql_keywords);
    
    if ($keywords) {
        echo '<h3 style="margin-top: 30px;">Короткие ссылки с UTM переходами</h3>';
        echo '<table class="tblSorter" style="width:100%">
            <thead>
                <tr>
                    <th>Короткая ссылка</th>
                    <th>Название</th>
                    <th>UTM переходы</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($keywords as $row) {
            $short_url = yourls_link($row->keyword);
            echo '<tr>';
            echo '<td><a href="' . $short_url . '" target="_blank">' . $short_url . '</a></td>';
            echo '<td>' . htmlspecialchars($row->title ?: $row->url) . '</td>';
            echo '<td>' . $row->utm_clicks . '</td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
    }
}