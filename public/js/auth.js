async function logout() {
    await fetch('/Library/api/auth.php?action=logout', { method: 'POST' });
  window.location.href = '../public/home.php';
}

document.addEventListener('DOMContentLoaded', () => {
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value;
      const res = await fetch('/Library/api/auth.php?action=login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
      });
      const data = await res.json();
      if (res.ok) {
        window.location.href = '/Library/public/books.php';
      } else {
        document.getElementById('alert').innerHTML = `<div class="alert alert-danger">${data.error || data.errors?.join(', ')}</div>`;
      }
    });
  }

  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const username = document.getElementById('r_username').value.trim();
      const email = document.getElementById('r_email').value.trim();
      const password = document.getElementById('r_password').value;
      const password2 = document.getElementById('r_password2').value;
      if (password !== password2) {
        document.getElementById('alert').innerHTML = `<div class="alert alert-danger">Passwords do not match</div>`;
        return;
      }
        const res = await fetch('/Library/api/auth.php?action=register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, email, password })
      });
      const data = await res.json();
      if (res.ok) {
        window.location.href = '/Library/public/login.php';
      } else {
        document.getElementById('alert').innerHTML = `<div class="alert alert-danger">${(data.errors || []).join('<br>') || data.error}</div>`;
      }
    });
  }
});
