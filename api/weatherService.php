<?php

class WeatherService {

    public static function fetchWeatherByCoordinates($lat, $lon) {
        $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&current_weather=true";

        $response = @file_get_contents($url);

        if ($response === FALSE) {
            http_response_code(503);
            return ['error' => 'Weather service unavailable (timeout or no response)'];
        }

        $data = json_decode($response, true);

        if (!isset($data['current_weather'])) {
            http_response_code(502);
            return ['error' => 'Bad response from Open-Meteo'];
        }

        return [
            'temperature' => $data['current_weather']['temperature'],
            'windspeed'   => $data['current_weather']['windspeed'],
            'time'        => $data['current_weather']['time']
        ];
    }

    public static function geocodeCity($city) {
        $url = "https://geocoding-api.open-meteo.com/v1/search?name=" . urlencode($city);
        $response = @file_get_contents($url);

        if ($response === FALSE) {
            http_response_code(503);
            return ['error' => 'City geocoding unavailable'];
        }

        $data = json_decode($response, true);

        if (empty($data['results'])) {
            http_response_code(400);
            return ['error' => 'City not found'];
        }

        return [
            'lat' => $data['results'][0]['latitude'],
            'lon' => $data['results'][0]['longitude']
        ];
    }
}
