<?php
/*
Plugin Name: UTM Tracking
Description: Сохраняет UTM метки при переходе по короткой ссылке
Version: 1.0
Author: Your Name
*/

yourls_add_action('shunt_redirect', 'utm_track');

function utm_track($args) {
    global $ydb;

    $shorturl = $args[0]; // короткий идентификатор ссылки
    $longurl  = yourls_get_keyword_longurl($shorturl);

    // Сохраняем только GET-параметры кроме YOURLS ID
    $params = $_GET;
    unset($params['id']);

    if (!empty($params)) {
        // Сохраняем в базу данных
        $stmt = $ydb->prepare("INSERT INTO yourls_utm_log (keyword, utm_data, timestamp) VALUES (:keyword, :utm_data, NOW())");
        $stmt->execute([
            ':keyword' => $shorturl,
            ':utm_data'=> json_encode($params)
        ]);
    }

    return false; // продолжить обычный редирект
}
