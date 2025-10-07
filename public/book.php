<?php
include 'includes/header.php';
?>
<main>
<section class="book-details">
<?php
$id = $_GET['id'] ?? '';
echo "<script>console.log('PHP ID: \"$id\"');</script>";
if (!$id) {
    echo "<p>Book ID is missing.</p>";
    exit;
}
?>
<div id="book-container">
  <p>Loading book details...</p>
</div>
</section>
</main>
<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const container = document.getElementById('book-container');
    const bookId = "<?php echo htmlspecialchars($id); ?>".trim();
    console.log('book.php JS ID:', bookId);
    console.log('book-container:', container);

    if (!bookId || !container) {
        console.log('Invalid book ID or container missing');
        container.innerHTML = `<p>Invalid book ID or container not found.</p>`;
        return;
    }

    // Fetch and increment visit count
    let visitCount = 0;
    try {
        const visitRes = await fetch(`http://localhost:3000/book/${encodeURIComponent(bookId)}/visit`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });
        console.log('Visit fetch status:', visitRes.status, 'OK:', visitRes.ok);
        if (visitRes.ok) {
            const visitData = await visitRes.json();
            visitCount = visitData.visit_count || 0;
            console.log('Visit count:', visitCount);
        } else {
            console.warn('Failed to fetch visit count');
        }
    } catch (err) {
        console.error('Visit fetch error:', err.message);
    }

    // Fetch and increment download count (initial fetch)
    let downloadCount = 0;
    try {
        const downloadRes = await fetch(`http://localhost:3000/book/${encodeURIComponent(bookId)}/download`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });
        console.log('Download fetch status:', downloadRes.status, 'OK:', downloadRes.ok);
        if (downloadRes.ok) {
            const downloadData = await downloadRes.json();
            downloadCount = downloadData.download_count || 0;
            console.log('Download count:', downloadCount);
        } else {
            console.warn('Failed to fetch download count');
        }
    } catch (err) {
        console.error('Download fetch error:', err.message);
    }

    try {
        const apiUrl = `http://localhost:3000/book/${encodeURIComponent(bookId)}`;
        console.log('Fetching from:', apiUrl);
        const res = await fetch(apiUrl, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' }
        });
        console.log('Fetch status:', res.status, 'OK:', res.ok);
        if (!res.ok) throw new Error(`HTTP error: ${res.status}`);
        const book = await res.json();
        console.log('Book data:', book);

        if (book.error) {
            container.innerHTML = `<p>${book.error}</p>`;
            return;
        }

        const downloadLink = book.download_link && book.download_link.startsWith('http') 
            ? book.download_link 
            : null;

        const imageUrl = book.cover_image_url && book.cover_image_url.startsWith('http') 
            ? book.cover_image_url 
            : 'https://via.placeholder.com/250x350?text=Book+Cover';

        container.innerHTML = `
            <div class="book-card">
                <img src="${imageUrl}" alt="${book.title || 'Book Image'}" class="book-cover" onerror="this.src='https://via.placeholder.com/250x350?text=Book+Cover'">
                <div class="book-info">
                    <h2>${book.title || 'Unknown Title'}</h2>
                    <p class="author"><strong>Author:</strong> ${book.author || 'N/A'}</p>
                    <p class="year"><strong>Year:</strong> ${book.published_year || 'N/A'}</p>
                    <p class="category"><strong>Category:</strong> ${book.category || 'N/A'}</p>
                    <p class="description"><strong>Description:</strong><br>${book.description || 'No description available.'}</p>
                    <div class="counts">
                        <p class="visit-count"><strong>Visits:</strong> ${visitCount}</p>
                        <p class="download-count"><strong>Downloads:</strong> ${downloadCount}</p>
                    </div>
                    ${downloadLink 
                        ? `<a href="${downloadLink}" target="_blank" class="btn" onclick="incrementDownloadCount('${bookId}')">Download Book</a>`
                        : `<p class="error">No valid download link available.</p>`}
                </div>
            </div>
        `;
    } catch (err) {
        console.error('Fetch error:', err.message);
        container.innerHTML = `<p>Error loading book details: ${err.message}</p>`;
    }

    // Function to increment download count on button click
    window.incrementDownloadCount = async (bookId) => {
        try {
            const downloadRes = await fetch(`http://localhost:3000/book/${encodeURIComponent(bookId)}/download`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            });
            console.log('Download click status:', downloadRes.status, 'OK:', downloadRes.ok);
            if (downloadRes.ok) {
                const downloadData = await downloadRes.json();
                const newDownloadCount = downloadData.download_count || 0;
                console.log('Updated download count:', newDownloadCount);
                const downloadCountElement = document.querySelector('.download-count');
                if (downloadCountElement) {
                    downloadCountElement.innerHTML = `<strong>Downloads:</strong> ${newDownloadCount}`;
                }
            } else {
                console.warn('Failed to update download count');
            }
        } catch (err) {
            console.error('Download count update error:', err.message);
        }
    };
});
</script>

<style>
.book-details {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 1rem;
    text-align: center;
    background: var(--background-color);
    color: var(--text-color);
}

.book-card {
    display: flex;
    gap: 2rem;
    background: var(--background-color);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
    border-radius: 8px;
    align-items: flex-start;
}

.book-cover {
    max-width: 250px;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    object-fit: cover;
}

.book-info {
    flex: 1;
    text-align: left;
}

.book-info h2 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--text-color);
}

.book-info .author,
.book-info .year,
.book-info .category,
.book-info .visit-count,
.book-info .download-count {
    font-size: 1.1rem;
    margin-bottom: 0.75rem;
    color: var(--text-color);
}

.book-info .description {
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    color: var(--text-color);
}

.book-info .counts {
    display: flex;
    gap: 2rem;
    margin-bottom: 1.5rem;
}

.book-info .error {
    color: var(--error-color, #d32f2f);
    font-weight: bold;
    font-size: 1rem;
    margin-bottom: 1rem;
    text-align: left;
}

.book-info .btn {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    background: var(--link-color, #006545);
    color: var(--toggle-knob, #fff);
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    transition: background 0.3s;
    margin-top: 1rem;
}

.book-info .btn:hover {
    background: var(--link-hover-color, #0af094);
}

@media (max-width: 768px) {
    .book-details {
        padding: 0.5rem;
    }

    .book-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 1rem;
    }

    .book-cover {
        max-width: 200px;
    }

    .book-info {
        text-align: center;
    }

    .book-info h2 {
        font-size: 1.8rem;
    }

    .book-info .author,
    .book-info .year,
    .book-info .category,
    .book-info .visit-count,
    .book-info .download-count {
        font-size: 1rem;
    }

    .book-info .description {
        font-size: 0.9rem;
    }

    .book-info .counts {
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .book-info .btn {
        padding: 0.6rem 1.2rem;
        font-size: 0.95rem;
        margin-top: 0.5rem;
    }

    .book-info .error {
        text-align: center;
    }
}

@media (max-width: 480px) {
    .book-card {
        padding: 0.75rem;
    }

    .book-cover {
        max-width: 150px;
    }

    .book-info h2 {
        font-size: 1.5rem;
    }

    .book-info .author,
    .book-info .year,
    .book-info .category,
    .book-info .visit-count,
    .book-info .download-count {
        font-size: 0.9rem;
    }

    .book-info .description {
        font-size: 0.8rem;
    }

    .book-info .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
}
</style>