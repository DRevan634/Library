<?php
require_once __DIR__ . '/../middleware.php';
requireAuth();
if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Weather Forecast</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="d-flex flex-column min-vh-100">

<?php include "_header.php"; ?>

<main class="container my-4">

    <h2>Weather Forecast</h2>
    <p>Check current temperature for any city.</p>

    <form id="weatherForm" class="mb-4">
        <label class="form-label">City name:</label>
        <input id="city" type="text" class="form-control" required placeholder="e.g. Warsaw, London">
        <button class="btn btn-primary mt-3">Get Weather</button>
    </form>

    <div id="loading" class="alert alert-info d-none">Loading...</div>
    <div id="error" class="alert alert-danger d-none"></div>
    <div id="result" class="alert alert-success d-none"></div>

</main>

<script>
async function fetchWeatherByCity(city) {
  const geoUrl = `https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(city)}&count=1`;
  const geoRes = await fetch(geoUrl);
  if (!geoRes.ok) throw new Error("Geocoding request failed");
  const geoData = await geoRes.json();

  if (!geoData.results || !geoData.results.length) {
    throw new Error("City not found");
  }

  const { latitude, longitude } = geoData.results[0];

  const weatherUrl =
    `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current_weather=true`;
  const weatherRes = await fetch(weatherUrl);
  if (!weatherRes.ok) throw new Error("Weather request failed");
  const weatherData = await weatherRes.json();

  if (!weatherData.current_weather) {
    throw new Error("No current weather data");
  }

  return {
    temperature: weatherData.current_weather.temperature,
    windspeed: weatherData.current_weather.windspeed,
    time: weatherData.current_weather.time,
  };
}

document.getElementById('weatherForm').addEventListener('submit', async (e) => {
  e.preventDefault();

  const city = document.getElementById('city').value.trim();
  const loading = document.getElementById('loading');
  const error = document.getElementById('error');
  const result = document.getElementById('result');

  loading.classList.remove('d-none');
  error.classList.add('d-none');
  result.classList.add('d-none');
  error.textContent = '';

  try {
    const data = await fetchWeatherByCity(city);

    result.innerHTML = `
      Temperature: <b>${data.temperature}°C</b><br>
      Wind speed: <b>${data.windspeed}</b> km/h<br>
      Time: <b>${data.time}</b>
    `;
    result.classList.remove('d-none');
  } catch (err) {
    error.textContent = err.message || 'Unknown error';
    error.classList.remove('d-none');
  } finally {
    loading.classList.add('d-none');
  }
});
</script>

</body>
</html>
