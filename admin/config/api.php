<?php
session_start();
$API_URL = "http://localhost/streaming-api/CineStream/api"; // sesuaikan lokasi API kamu

function apiRequest($endpoint, $method="GET", $data=null) {
    global $API_URL;

    $ch = curl_init($API_URL . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if ($method !== "GET") {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}
