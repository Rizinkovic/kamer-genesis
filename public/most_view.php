<?php include 'includes/header.php'; ?>
<main>
  <section class="latest-books">
    <h2>Most Viewed/Downloaded Books</h2>
    <div class="books-container">
      <div id="books-grid" class="books-grid">
        <p>Loading books...</p>
      </div>
    </div>
  </section>
</main>
<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', async () => {
  const grid = document.getElementById('books-grid');

  try {
    const res = await fetch('http://localhost:3000/books/most-viewed');
    if (!res.ok) throw new Error('Failed to fetch books');
    const books = await res.json();
    console.log('Most viewed books:', books);

    grid.innerHTML = '';
    if (!books || books.length === 0) {
      grid.innerHTML = '<p>No books found.</p>';
      return;
    }

    books.forEach(book => {
      const imageUrl = book.cover_image_url && book.cover_image_url.startsWith('http') 
        ? book.cover_image_url 
        : 'https://via.placeholder.com/120x180?text=Book+Cover';

      grid.innerHTML += `
        <div class="book-card">
          <div class="book-cover">
            <img src="${imageUrl}" alt="${book.title || 'Book Cover'}" onerror="this.src='https://via.placeholder.com/120x180?text=Book+Cover'">
          </div>
          <div class="book-info">
            <h3 class="title">${book.title || 'Unknown Title'}</h3>
            <p class="author">${book.author || 'N/A'}</p>
            <a href="book.php?id=${encodeURIComponent(book.id)}" class="view-btn">View Details</a>
          </div>
        </div>
      `;
    });
  } catch (err) {
    console.error('Error fetching books:', err);
    grid.innerHTML = `<p>Error loading books: ${err.message}</p>`;
  }
});
</script>

<style>
/* ===== Modern Dark UI Inspired by Comic Dashboard ===== */
:root {
  --bg-dark: #c1e6ca25;
  --bg-card: #75f5955e;
  --text-light: #130202ff;
  --text-muted: #9ca3af;
  --accent: #1dd11dff;
  --accent-hover: #099b63ff;
}

/* Page container */
.latest-books {
  max-width: 1200px;
  margin: 2rem auto;
  padding: 1rem;
  background: var(--bg-dark);
  color: var(--text-light);
  border-radius: 16px;
}

/* Title */
.latest-books h2 {
  font-size: 1.8rem;
  font-weight: 700;
  margin-bottom: 1rem;
  color: var(--text-light);
  text-align: left;
}

/* Horizontal scroll container */
.books-container {
  overflow-x: auto;
  scrollbar-width: thin;
  scrollbar-color: var(--accent) transparent;
  -webkit-overflow-scrolling: touch;
  padding-bottom: 1rem;
}

.books-container::-webkit-scrollbar {
  height: 8px;
}
.books-container::-webkit-scrollbar-thumb {
  background: var(--accent);
  border-radius: 4px;
}

/* Grid - becomes row-like horizontally */
.books-grid {
  display: flex;
  gap: 1rem;
  padding: 0.5rem;
}

/* Book card */
.book-card {
  background: var(--bg-card);
  border-radius: 12px;
  width: 160px;
  flex-shrink: 0;
  overflow: hidden;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
  display: flex;
  flex-direction: column;
}

.book-card:hover {
  transform: translateY(-6px) scale(1.03);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

/* Book cover */
.book-cover {
  width: 100%;
  height: 210px;
  overflow: hidden;
  border-bottom: 2px solid rgba(255, 255, 255, 0.05);
}

.book-cover img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

/* Book info */
.book-info {
  padding: 0.75rem;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.book-info .title {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 0.3rem;
  color: var(--text-light);
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.book-info .author {
  font-size: 0.85rem;
  color: var(--text-muted);
  margin-bottom: 0.5rem;
}

/* Button */
.view-btn {
  display: inline-block;
  padding: 0.4rem 0.8rem;
  background: var(--accent);
  color: #fff;
  text-decoration: none;
  border-radius: 6px;
  font-size: 0.85rem;
  text-align: center;
  transition: background 0.2s ease, transform 0.2s ease;
}

.view-btn:hover {
  background: var(--accent-hover);
  transform: scale(1.05);
}

/* Responsive tweaks */
@media (max-width: 768px) {
  .latest-books {
    padding: 0.5rem;
  }

  .latest-books h2 {
    font-size: 1.5rem;
  }

  .book-card {
    width: 140px;
  }

  .book-cover {
    height: 180px;
  }
}

@media (max-width: 480px) {
  .books-grid {
    gap: 0.7rem;
  }

  .book-card {
    width: 130px;
  }

  .book-cover {
    height: 160px;
  }

  .book-info .title {
    font-size: 0.9rem;
  }
}
</style>