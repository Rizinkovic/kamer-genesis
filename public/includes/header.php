<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kamer-Genesis</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
  <header>
    <div class="header-container">
      <a href="index.php" class="logo-link">
        <img src="assets/images/logo1.png" alt="Kamer-Genesis Logo" class="logo" width="50" height="50">
      </a>

      <!-- Hamburger Menu for Mobile -->
      <button class="hamburger" aria-label="Toggle navigation menu">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <!-- Navigation -->
      <nav class="nav-menu">
        <a href="index.php">Home</a>
        <a href="pages/about.php">About</a>
        <a href="forum.php">Forum</a>
      </nav>

      <!-- Theme Toggle -->
      <div class="theme-toggle">
        <input type="checkbox" id="theme-switch" aria-label="Toggle dark mode">
        <label for="theme-switch" class="theme-label">
          <span class="theme-icon"></span>
        </label>
      </div>
    </div>
  </header>

  <main>
    <script src="assets/js/main.js"></script>
    <script>
      // Theme toggle logic
      (function() {
        const DEFAULT_THEME = 'light';
        const THEME_KEY = 'theme';
        const DATA_ATTR = 'data-theme';
        const toggle = document.getElementById('theme-switch');
        const icon = document.querySelector('.theme-icon');

        if (!toggle || !icon) {
          console.warn('Theme toggle or icon not found');
          return;
        }

        // Load saved theme or default
        const savedTheme = localStorage.getItem(THEME_KEY) || DEFAULT_THEME;
        document.documentElement.setAttribute(DATA_ATTR, savedTheme);
        toggle.checked = savedTheme === 'dark';
        icon.textContent = savedTheme === 'dark' ? '🌙' : '☀️';

        // Handle toggle change
        toggle.addEventListener('change', () => {
          const newTheme = toggle.checked ? 'dark' : 'light';
          document.documentElement.setAttribute(DATA_ATTR, newTheme);
          localStorage.setItem(THEME_KEY, newTheme);
          icon.textContent = newTheme === 'dark' ? '🌙' : '☀️';
          console.log(`Theme switched to: ${newTheme}`);
        });
      })();
    </script>
  </main>
</body>
</html>