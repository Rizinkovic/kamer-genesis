<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KamerGenesis - Search Books</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<main>
  <section class="home">
    <div class="logo">
      <img src="assets/images/main.png" alt="KamerGenesis Logo" width="300" style="cursor:pointer;">
    </div>

    <form class="search-bar" id="search-form">
      <input type="text" id="search-input" placeholder="Search Cameroonian or African books..." required>
      <button type="submit">🔍</button>
    </form>

    <div class="book-links">
  <a href="latest.php">Latest Added</a>
  <a href="most_view.php">Most Downloaded</a>
</div>

  </section>
</main>

<?php include 'includes/footer.php'; ?>


<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('search-form');
  form.addEventListener('submit', e => {
    e.preventDefault();
    const query = document.getElementById('search-input').value.trim();
    if(query) window.location.href = `search.php?q=${encodeURIComponent(query)}`;
  });
});
</script>
</body>
</html>
<style>
  .book-links {
  display: flex;
  justify-content: center;
  gap: 20px; /* space between the links */
  margin-top: 20px; /* space between form and links */
}

.book-links a {
  text-decoration: none;
  color: var(--link-color);
  font-weight: 500;
  transition: all 0.3s ease;
  padding: 6px 10px;
  border-radius: 8px;
}

.book-links a:hover {
  background-color: var(--accent-color, #e0e0e0);
  color: var(--text-color, #000);
  transform: scale(1.05);
}

</style>