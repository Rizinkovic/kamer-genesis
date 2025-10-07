// library.js - handles search and book display logic

const Library = (() => {
    const apiBase = 'http://localhost:3000'; // Your Express API

    // Search books by query
    async function searchBooks(query) {
        if (!query) return [];
        try {
            const res = await fetch(`${apiBase}/search?q=${encodeURIComponent(query)}`);
            if (!res.ok) throw new Error('API fetch failed');
            const books = await res.json();
            return books;
        } catch (err) {
            console.error('Error fetching books:', err);
            return [];
        }
    }

    // Render search results in table
    function renderSearchResults(books, tableBodyId) {
        const tbody = document.getElementById(tableBodyId);
        if (!tbody) return;
        tbody.innerHTML = '';

        if (!books || books.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5">No books found.</td></tr>`;
            return;
        }

        books.forEach(book => {
            tbody.innerHTML += `
                <tr>
                    <td>${book.title || ''}</td>
                    <td>${book.author || ''}</td>
                    <td>${book.published_year || ''}</td>
                    <td>${book.category || ''}</td>
                    <td>
                        <button class="view-book-btn" data-title="${encodeURIComponent(book.title)}" data-author="${encodeURIComponent(book.author)}">
                            View Book
                        </button>
                    </td>
                </tr>
            `;
        });

        // Attach click event to all view buttons
        document.querySelectorAll('.view-book-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const title = btn.getAttribute('data-title');
                const author = btn.getAttribute('data-author');
                window.location.href = `book.php?title=${title}&author=${author}`;
            });
        });
    }

    // Fetch single book data from API (optional)
    async function fetchBook(title) {
        if (!title) return null;
        const books = await searchBooks(title);
        return books.find(b => b.title.toLowerCase() === title.toLowerCase()) || null;
    }

    return {
        searchBooks,
        renderSearchResults,
        fetchBook
    };
})();
