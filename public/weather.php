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
document.getElementById('weatherForm').addEventListener('submit', async (e)=>{
    e.preventDefault();
    
    const city = document.getElementById('city').value.trim();
    const loading = document.getElementById('loading');
    const error   = document.getElementById('error');
    const result  = document.getElementById('result');

    loading.classList.remove("d-none");
    error.classList.add("d-none");
    result.classList.add("d-none");

    const res = await fetch(`/Library/api/weather.php?city=${encodeURIComponent(city)}`);

    const data = await res.json();
    loading.classList.add("d-none");

    if (!res.ok || data.error) {
        error.textContent = data.error || "Unknown error";
        error.classList.remove("d-none");
        return;
    }

    result.innerHTML = `
        Temperature: <b>${data.temperature}°C</b><br>
        Wind speed: <b>${data.windspeed} km/h</b><br>
        Time: <b>${data.time}</b>
    `;
    result.classList.remove("d-none");
});
</script>

</body>
</html>
