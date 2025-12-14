<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$API_URL = "http://localhost/streaming-api/CineStream/api";

/**
 * Mengirim request ke API endpoint
 * @param string $endpoint Path API (contoh: '/movies/read.php')
 * @param string $method HTTP method (GET, POST, PUT, DELETE)
 * @param array $data Data untuk dikirim (untuk POST/PUT)
 * @return array Response dari API
 */
function apiRequest($endpoint, $method = "GET", $data = null) {
    global $API_URL;

    $ch = curl_init($API_URL . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    if ($method !== "GET") {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    
    if ($response === false) {
        return ['error' => 'Network error: ' . $err];
    }
    
    return json_decode($response, true);
}
