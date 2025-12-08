// public/js/books.js

async function fetchCurrentUser() {
    const res = await fetch('/Library/api/auth.php');
    const data = await res.json();
    return data.user || null;
}

async function loadBooks() {
    const res = await fetch('/Library/api/books.php');
    const books = await res.json();
    const tbody = document.getElementById('books-table');
    tbody.innerHTML = '';
    const user = await fetchCurrentUser();
    books.forEach(b => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
      <td>${escapeHtml(b.title)}</td>
      <td>${escapeHtml(b.author)}</td>
      <td>${b.year}</td>
      <td>${b.pages}</td>
      <td>${parseFloat(b.price).toFixed(2)}</td>
      <td>${parseFloat(b.rate).toFixed(1)}</td>
      <td>${escapeHtml(b.opis_68153)}</td>
    `;
        if (user && user.role == 'admin') {
            tr.innerHTML = `<td>${b.id}</td>` + tr.innerHTML
            tr.innerHTML += `
              <td class="text-center">
                <a class="btn btn-sm btn-info me-1" href="/Library/public/edit.php?id=${b.id}">Edit</a>
                <button class="btn btn-sm btn-danger" onclick="deleteBook(${b.id})">Delete</button>
              </td>
            `
        }
        tbody.appendChild(tr);
    });

    // Adjust visibility of action buttons based on role
    if (!user || user.role !== 'admin') {
        // hide add form and action buttons
        const addForm = document.getElementById('addForm');
        if (addForm) addForm.style.display = 'none';
        document.querySelectorAll('th.admin, td.admin').forEach(el => el.style.display = 'none');
    }
}

// Add book
document.addEventListener('DOMContentLoaded', () => {
    const addForm = document.getElementById('addForm');
    if (addForm) {
        addForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const payload = {
                title: document.getElementById('b_title').value.trim(),
                author: document.getElementById('b_author').value.trim(),
                year: Number(document.getElementById('b_year').value),
                pages: Number(document.getElementById('b_pages').value),
                price: Number(document.getElementById('b_price').value),
                rate: Number(document.getElementById('b_rate').value),
                opis_68153: document.getElementById('b_opis_68153').value.trim()
            };
            const res = await fetch('/Library/api/books.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            if (res.ok) {
                location.reload();
            } else {
                const data = await res.json();
                document.getElementById('alert-box').innerHTML = `<div class="alert alert-danger">${(data.errors || []).join('<br>') || data.error}</div>`;
            }
        });
    }

    // If on edit page, load single book
    const editForm = document.getElementById('editForm');
    if (editForm) {
        const params = new URLSearchParams(window.location.search);
        const id = params.get('id');
        if (!id) {
            alert('ID missing'); window.location.href = 'books.php';
        }
        fetch(`/Library/api/books.php?id=${id}`).then(r => r.json()).then(b => {
            document.getElementById('e_id').value = b.id;
            document.getElementById('e_title').value = b.title;
            document.getElementById('e_author').value = b.author;
            document.getElementById('e_year').value = b.year;
            document.getElementById('e_pages').value = b.pages;
            document.getElementById('e_price').value = parseFloat(b.price).toFixed(2);
            document.getElementById('e_rate').value = parseFloat(b.rate).toFixed(1);
            document.getElementById('e_opis_68153').value = b.opis_68153;
        });
        editForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const payload = {
                title: document.getElementById('e_title').value.trim(),
                author: document.getElementById('e_author').value.trim(),
                year: Number(document.getElementById('e_year').value),
                pages: Number(document.getElementById('e_pages').value),
                price: Number(document.getElementById('e_price').value),
                rate: Number(document.getElementById('e_rate').value),
                opis_68153: document.getElementById('e_opis_68153').value.trim(),
                id: Number(document.getElementById('e_id').value)
            };
            const res = await fetch('/Library/api/books.php?action=update', {
                method: 'POST',
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload)
            });
            if (res.ok) {
                window.location.href = 'books.php';
            } else {
                const data = await res.json();
                document.getElementById('alert').innerHTML = `<div class="alert alert-danger">${(data.errors || []).join('<br>') || data.error}</div>`;
            }
        });
    }

    // load books list on pages that have it
    if (document.getElementById('books-table')) {
        loadBooks();
    }
});

async function deleteBook(id) {
    if (!confirm('Delete book?')) return;
    const res = await fetch('/Library/api/books.php?action=delete', {
    method: 'POST',
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
    });
    if (res.ok) {
        location.reload();
    } else {
        const data = await res.json();
        alert(data.error || 'Delete failed');
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>"']/g, function (m) { return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[m]; });
}
