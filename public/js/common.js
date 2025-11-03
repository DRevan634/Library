async function getCurrentUser() {
    const res = await fetch('/api/auth.php');
    const json = await res.json();
    return json.user || null;
}
