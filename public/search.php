<?php include 'includes/header.php'; ?>
<main>
<section class="search-results">
  <form action="search.php" method="GET" class="search-bar">
    <input type="text" name="q" placeholder="Search books..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" required>
    <button type="submit">🔍</button>
  </form>

  <h2>Search Results for "<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>"</h2>

  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Year</th>
        <th>Category</th>
        <th>View</th>
      </tr>
    </thead>
    <tbody id="search-results-body">
      <tr><td colspan="5">Loading...</td></tr>
    </tbody>
  </table>
</section>
</main>
<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const tbody = document.getElementById('search-results-body');
    const query = "<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>".trim();
    
    if (!query) {
        tbody.innerHTML = `<tr><td colspan="5">Please enter a search query.</td></tr>`;
        return;
    }

    try {
        const res = await fetch(`http://localhost:3000/search?q=${encodeURIComponent(query)}`);
        console.log('Search fetch status:', res.status, 'OK:', res.ok);
        if (!res.ok) throw new Error(`HTTP error: ${res.status}`);
        const books = await res.json();
        console.log('Search results:', books);

        tbody.innerHTML = '';
        if (!books || books.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5">No books found for "${query}".</td></tr>`;
        } else {
            books.forEach(book => {
                console.log('Generating link for book ID:', book.id); // Log ID
                tbody.innerHTML += `
                    <tr>
                        <td>${book.title || 'N/A'}</td>
                        <td>${book.author || 'N/A'}</td>
                        <td>${book.published_year || 'N/A'}</td>
                        <td>${book.category || 'N/A'}</td>
                        <td><a href="book.php?id=${encodeURIComponent(book.id)}" class="view-link">View Book</a></td>
                    </tr>
                `;
            });
        }
    } catch (err) {
        console.error('Search error:', err.message);
        tbody.innerHTML = `<tr><td colspan="5">Error loading books: ${err.message}</td></tr>`;
    }
});
</script>

<style>
body {
    font-family: Arial, sans-serif;
    background-color: var(--background-color, #fff);
    color: var(--text-color, #333);
    transition: all 0.3s ease;
}

.search-bar {
    display: flex;
    align-items: center;
    max-width: 600px;
    margin: 1.5rem auto;
    background: var(--background-color, #fff);
    border: 2px solid var(--text-color, #333);
    border-radius: 25px;
    overflow: hidden;
}

.search-bar input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: none;
    background: transparent;
    color: var(--text-color, #333);
    font-size: 1rem;
    outline: none;
}

.search-bar input::placeholder {
    color: var(--text-color, #333);
    opacity: 0.7;
}

.search-bar button {
    background: var(--link-color, #006545);
    color: #fff;
    border: none;
    padding: 0.75rem 1.5rem;
    font-size: 1.2rem;
    cursor: pointer;
    transition: background 0.3s;
}

.search-bar button:hover {
    background: var(--link-hover-color, #0af094);
}

.search-results {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 1rem;
    text-align: center;
}

.search-results h2 {
    font-size: 1.8rem;
    color: var(--text-color, #333);
    margin-bottom: 1.5rem;
    word-break: break-word;
}

.search-results table {
    width: 100%;
    border-collapse: collapse;
    background: var(--background-color, #fff);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.search-results th,
.search-results td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--text-color, #333);
}

.search-results th {
    background: var(--header-bg, #f4f4f4);
    font-weight: bold;
}

.search-results td a.view-link {
    color: var(--link-color, #006545);
    text-decoration: none;
    font-weight: bold;
}

.search-results td a.view-link:hover {
    color: var(--link-hover-color, #0af094);
}

@media (max-width: 768px) {
    .search-results table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    .search-results th, .search-results td {
        padding: 0.75rem;
        font-size: 0.9rem;
    }
    .search-results h2 {
        font-size: 1.5rem;
    }
    .search-bar {
        max-width: 90%;
    }
}

@media (max-width: 480px) {
    .search-results th, .search-results td {
        padding: 0.5rem;
        font-size: 0.8rem;
    }
    .search-results h2 {
        font-size: 1.3rem;
    }
}
</style>