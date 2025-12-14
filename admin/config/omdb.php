<?php
$OMDB_API_KEY = "2e8e2d6e";

function omdbCurlGet($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    // Optional: verify SSL
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    $response = curl_exec($ch);
    $err = curl_error($ch);
    $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    if ($response === false) {
        return [ 'Response' => 'False', 'Error' => 'Network error: ' . ($err ?: 'unknown') ];
    }
    $data = json_decode($response, true);
    if (!is_array($data)) {
        return [ 'Response' => 'False', 'Error' => 'Invalid JSON' ];
    }
    // OMDb sometimes returns 200 with Response False; include status for diagnostics
    $data['_status'] = $status;
    return $data;
}

function omdbSearch($title) {
    global $OMDB_API_KEY;
    $title = trim($title ?? '');
    if ($title === '') {
        return [ 'Response' => 'False', 'Error' => 'Query is empty' ];
    }
    $url = "https://www.omdbapi.com/?apikey=$OMDB_API_KEY&s=" . urlencode($title);
    return omdbCurlGet($url);
}

function omdbDetail($imdbID) {
    global $OMDB_API_KEY;
    $safeId = urlencode($imdbID);
    $url = "https://www.omdbapi.com/?apikey=$OMDB_API_KEY&i=$safeId&plot=full";
    return omdbCurlGet($url);
    
}
