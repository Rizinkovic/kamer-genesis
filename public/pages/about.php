<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kamer-Genesis</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
  <header>
    <div class="header-container">
      <a href="index.php" class="logo-link">
        <img src="../assets/images/logo1.png" alt="Kamer-Genesis Logo" class="logo" width="50" height="50">
      </a>

      <!-- Hamburger Menu for Mobile -->
      <button class="hamburger" aria-label="Toggle navigation menu">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <!-- Navigation -->
      <nav class="nav-menu">
        <a href="../index.php">Home</a>
        <a href="pages/about.php">About</a>
        <a href="../forum.php">Forum</a>
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
    <script src="../assets/js/main.js"></script>
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
<main>
  <section class="about-section">
    <h2>About KamerGenesis</h2>
    <div class="about-content">
      <p>
        KamerGenesis is a dedicated platform designed to celebrate and share the rich literary heritage of Cameroon and Africa. Our primary mission is to educate and inspire the youth by providing access to a diverse collection of Cameroonian and African books that reflect our vibrant cultures, histories, and traditions. We believe that literature is a powerful tool for preserving identity and fostering pride in our roots.
      </p>
      <p>
        Through KamerGenesis, we aim to bridge the gap by offering a wide range of African-based literature, including novels, folktales, historical accounts, and contemporary works. Our platform serves as a digital library where young minds can explore stories that resonate with their heritage, encouraging a deeper understanding of their past and present. Whether it's learning about ancient kingdoms, modern struggles, or the beauty of our oral traditions, KamerGenesis is here to empower the next generation with knowledge and inspiration.
      </p>
      <p>
        Join us on this journey to rediscover and share the literary treasures of Cameroon and Africa, fostering a community that values education, culture, and creativity. Together, we can build a legacy of pride and understanding through the power of books.
      </p>
    </div>
  </section>
</main>
<footer>
  <p>© <?php echo date('Y'); ?> Kamer-Genesis | Built by <a href="https://www.github.com/Rizinkovic" class="href">Rizinkovic</a></p>
  <div class="social-media">
    <a href="#" target="_blank"><i class="fa-brands fa-facebook"></i></a>
    <a href="#" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
    <a href="#" target="_blank"><i class="fa-brands fa-instagram"></i></a>
  </div>  
  <div class="utils">
    <a href="./disclaimer.php">Disclaimer</a>
    <a href="./contact.php" class="href">Contact Us</a>
    <a href="./donate.php" class="href">Donate</a>
  </div>
</footer>
<style>
    /* Social media icons */
.social-media a {
  display: inline-flex;
  align-items: center;
  margin: 0 1rem;
  padding: 0.5rem;
  color: var(--link-color);
  text-decoration: none;
  transition: color 0.3s;
}

.social-media a:hover {
  color: var(--link-hover-color);
}

.social-media a i {
  margin-right: 0.5rem;
  font-size: 1.2rem;
  width: 1.5rem; /* Consistent icon width for alignment */
  text-align: center;
}

.social-media a i {
  margin-right: 0.5rem;
  font-size: 1.2rem;
  width: 1.5rem; 
  text-align: center;
}
</style>

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

/* About section */
.about-section {
  max-width: 1200px;
  margin: 2rem auto;
  padding: 1rem;
  background: var(--bg-dark);
  color: var(--text-light);
  border-radius: 16px;
}

/* Title */
.about-section h2 {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  color: var(--text-light);
  text-align: center;
}

/* Content */
.about-content {
  background: var(--bg-card);
  padding: 1.5rem;
  border-radius: 12px;
  line-height: 1.6;
}

.about-content p {
  font-size: 1rem;
  color: var(--text-light);
  margin-bottom: 1rem;
}

.about-content p:last-child {
  margin-bottom: 0;
}

/* Responsive tweaks */
@media (max-width: 768px) {
  .about-section {
    padding: 0.5rem;
  }

  .about-section h2 {
    font-size: 1.5rem;
  }

  .about-content {
    padding: 1rem;
  }

  .about-content p {
    font-size: 0.9rem;
  }
}

@media (max-width: 480px) {
  .about-section h2 {
    font-size: 1.3rem;
  }

  .about-content {
    padding: 0.75rem;
  }
}
</style>