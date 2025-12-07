<?php
require_once __DIR__ . '/WeatherService.php';

header('Content-Type: application/json');

$city = $_GET['city'] ?? null;
$lat  = $_GET['lat']  ?? null;
$lon  = $_GET['lon']  ?? null;

if ($city) {
    $geo = WeatherService::geocodeCity($city);
    if (isset($geo['error'])) { echo json_encode($geo); exit; }

    $result = WeatherService::fetchWeatherByCoordinates($geo['lat'], $geo['lon']);
    echo json_encode($result);
    exit;
}

if ($lat && $lon) {
    $result = WeatherService::fetchWeatherByCoordinates($lat, $lon);
    echo json_encode($result);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Provide ?city= or ?lat= & ?lon=']);
