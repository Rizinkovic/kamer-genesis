<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kamer-Genesis - Contact</title>
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

      // Handle contact form submission
      document.querySelector('.contact-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
          const response = await fetch('https://formspree.io/f/xkgzoogl', {
            method: 'POST',
            headers: {
              'Accept': 'application/json'
            },
            body: JSON.stringify(data),
            mode: 'no-cors' // Formspree doesn't require CORS for this endpoint
          });

          if (response.ok) {
            alert('Thank you for your message! We will get back to you soon.');
            form.reset(); // Clear the form
          } else {
            alert('There was an issue submitting your message. Please try again.');
          }
        } catch (err) {
          console.error('Error submitting form:', err);
          alert('An error occurred. Please check the console or try again later.');
        }
      });
    </script>

    <section class="about-section">
      <h2>Contact Us</h2>
      <div class="about-content">
        <p>
          We’d love to hear from you! Whether you have questions about KamerGenesis, suggestions for new books, or feedback on our platform, feel free to reach out. Our team is dedicated to supporting the community and enhancing your experience.
        </p>
        <p>
          <strong>Email:</strong> support@kamergenesis.org<br>
          <strong>Phone:</strong> +237 6 123 4567<br>
          <strong>Address:</strong> P.O. Box 123, Yaoundé, Cameroon
        </p>
        <p>
          For partnership inquiries or to report issues, please send us an email or use the contact form below. We aim to respond within 48 hours. Thank you for being part of the KamerGenesis community!
        </p>
        <!-- Contact form wired to Formspree -->
        <form action="#" method="post" class="contact-form">
          <input type="text" name="name" placeholder="Your Name" required>
          <input type="email" name="email" placeholder="Your Email" required>
          <textarea name="message" placeholder="Your Message" required></textarea>
          <button type="submit">Send Message</button>
        </form>
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

    /* About section (reused for Contact) */
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

    .about-content strong {
      color: var(--text-light);
    }

    /* Contact Form */
    .contact-form {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .contact-form input,
    .contact-form textarea {
      padding: 0.5rem;
      border: 1px solid var(--accent);
      border-radius: 4px;
      background: rgba(255, 255, 255, 0.1);
      color: var(--text-light);
    }

    .contact-form textarea {
      height: 100px;
      resize: vertical;
    }

    .contact-form button {
      padding: 0.5rem 1rem;
      background: var(--accent);
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 0.9rem;
      cursor: pointer;
      transition: background 0.2s ease;
    }

    .contact-form button:hover {
      background: var(--accent-hover);
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

      .contact-form input,
      .contact-form textarea {
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

      .contact-form button {
        font-size: 0.85rem;
      }
    }
  </style>
</body>
</html>