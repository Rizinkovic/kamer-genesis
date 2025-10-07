<?php include 'includes/header.php'; ?>
<main>
  <section class="forum-section">
    <div class="forum-rules">
      <h2>Forum Rules</h2>
      <ul>
        <li>No unrelated questions.</li>
        <li>No insults or inappropriate content (e.g., porn).</li>
        <li>Keep discussions respectful and on-topic.</li>
      </ul>
    </div>

    <div class="forum-posts" id="forum-posts">
      <p>Loading posts...</p>
    </div>

    <div class="post-form" id="post-form">
      <h3>Post a New Thread</h3>
      <input type="text" id="post-pseudo" placeholder="Enter your pseudo" required>
      <textarea id="post-content" placeholder="Write your post..." required></textarea>
      <button onclick="submitPost()">Submit Post</button>
    </div>
  </section>
</main>
<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', async () => {
  const postsContainer = document.getElementById('forum-posts');
  const postForm = document.getElementById('post-form');

  // Load posts and comments
  async function loadPosts() {
    try {
      const res = await fetch('http://localhost:3000/forum/posts');
      if (!res.ok) throw new Error('Failed to fetch posts');
      const posts = await res.json();
      console.log('Forum posts:', posts);

      postsContainer.innerHTML = '';
      if (!posts || posts.length === 0) {
        postsContainer.innerHTML = '<p>No posts yet.</p>';
        return;
      }

      posts.forEach(post => {
        const isAdmin = post.is_admin;
        const pseudoDisplay = isAdmin ? `${post.pseudo} <span class="admin-check">✔</span>` : post.pseudo;
        const postTime = post.created_at?.toDate ? post.created_at.toDate().toLocaleString() : 'N/A';
        postsContainer.innerHTML += `
          <div class="post-card">
            <div class="post-header">
              <span class="post-pseudo">${pseudoDisplay}</span>
              <span class="post-time">${postTime}</span>
            </div>
            <p class="post-content">${post.content}</p>
            ${post.comments.length > 0 ? `
              <div class="comments-section">
                ${post.comments.map(comment => {
                  const isAdminComment = comment.is_admin;
                  const commentPseudo = isAdminComment ? `${comment.pseudo} <span class="admin-check">✔</span>` : comment.pseudo;
                  const commentTime = comment.created_at?.toDate ? comment.created_at.toDate().toLocaleString() : 'N/A';
                  return `
                    <div class="comment-card" data-post-id="${post.id}" data-comment-id="${comment.id}">
                      <div class="comment-header">
                        <span class="comment-pseudo">${commentPseudo}</span>
                        <span class="comment-time">${commentTime}</span>
                        <button class="delete-btn" style="display: ${isAdmin ? 'inline' : 'none'};">Delete</button>
                      </div>
                      <p class="comment-content">${comment.content}</p>
                    </div>
                  `;
                }).join('')}
              </div>
            ` : '<p>No comments yet.</p>'}
            <div class="comment-form">
              <input type="text" class="comment-pseudo" placeholder="Enter your pseudo" required>
              <textarea class="comment-content" placeholder="Write a comment..." required></textarea>
              <button onclick="submitComment('${post.id}')">Add Comment</button>
            </div>
          </div>
        `;
      });

      // Add delete event listeners for admin
      document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
          const postId = btn.parentElement.parentElement.dataset.postId;
          const commentId = btn.parentElement.parentElement.dataset.commentId;
          if (confirm('Are you sure you want to delete this comment?')) {
            const res = await fetch(`http://localhost:3000/forum/comment/${postId}/${commentId}`, {
              method: 'DELETE'
            });
            if (res.ok) loadPosts(); // Reload posts on success
          }
        });
      });
    } catch (err) {
      console.error('Error loading posts:', err);
      postsContainer.innerHTML = `<p>Error loading posts: ${err.message}</p>`;
    }
  }

  loadPosts();

  // Submit a new post
  window.submitPost = async () => {
    const pseudo = document.getElementById('post-pseudo').value.trim();
    const content = document.getElementById('post-content').value.trim();
    if (!pseudo || !content) {
      alert('Please enter a pseudo and content.');
      return;
    }

    try {
      const res = await fetch('http://localhost:3000/forum/post', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ pseudo, content })
      });
      if (res.ok) {
        document.getElementById('post-pseudo').value = '';
        document.getElementById('post-content').value = '';
        loadPosts();
      }
    } catch (err) {
      console.error('Error submitting post:', err);
    }
  };

  // Submit a comment
  window.submitComment = async (postId) => {
    const pseudo = document.querySelector(`#forum-posts [data-post-id="${postId}"] .comment-pseudo`).value.trim();
    const content = document.querySelector(`#forum-posts [data-post-id="${postId}"] .comment-content`).value.trim();
    if (!pseudo || !content) {
      alert('Please enter a pseudo and content.');
      return;
    }

    try {
      const res = await fetch(`http://localhost:3000/forum/comment/${postId}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ pseudo, content })
      });
      if (res.ok) {
        document.querySelector(`#forum-posts [data-post-id="${postId}"] .comment-pseudo`).value = '';
        document.querySelector(`#forum-posts [data-post-id="${postId}"] .comment-content`).value = '';
        loadPosts();
      }
    } catch (err) {
      console.error('Error submitting comment:', err);
    }
  };
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
  --admin-check: #ffd700; /* Golden checkmark color */
}

/* Forum section */
.forum-section {
  max-width: 1200px;
  margin: 2rem auto;
  padding: 1rem;
  background: var(--bg-dark);
  color: var(--text-light);
  border-radius: 16px;
}

/* Forum rules */
.forum-rules {
  background: var(--bg-card);
  padding: 1rem;
  border-radius: 12px;
  margin-bottom: 1.5rem;
}

.forum-rules h2 {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  color: var(--text-light);
}

.forum-rules ul {
  list-style-type: disc;
  padding-left: 1.5rem;
}

.forum-rules li {
  font-size: 0.9rem;
  color: var(--text-muted);
  margin-bottom: 0.3rem;
}

/* Posts container */
.forum-posts {
  margin-bottom: 1.5rem;
}

/* Post card */
.post-card {
  background: var(--bg-card);
  border-radius: 12px;
  margin-bottom: 1rem;
  padding: 0.75rem;
  transition: transform 0.2s ease;
}

.post-card:hover {
  transform: translateY(-3px);
}

/* Post header */
.post-header {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  margin-bottom: 0.5rem;
}

.post-pseudo {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--text-light);
}

.admin-check {
  color: var(--admin-check);
  font-size: 0.8rem;
  margin-left: 0.2rem;
}

.post-time {
  font-size: 0.75rem;
  color: var(--text-muted);
}

/* Post content */
.post-content {
  font-size: 0.9rem;
  color: var(--text-light);
  margin-bottom: 0.5rem;
}

/* Comments section */
.comments-section {
  margin-top: 0.5rem;
  padding-left: 1rem;
  border-left: 2px solid var(--accent);
}

/* Comment card */
.comment-card {
  background: rgba(255, 255, 255, 0.05);
  border-radius: 8px;
  padding: 0.5rem;
  margin-bottom: 0.5rem;
}

/* Comment header */
.comment-header {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
  margin-bottom: 0.3rem;
}

.comment-pseudo {
  font-size: 0.85rem;
  font-weight: 500;
  color: var(--text-light);
}

.comment-time {
  font-size: 0.7rem;
  color: var(--text-muted);
}

.delete-btn {
  background: var(--accent);
  color: #fff;
  border: none;
  border-radius: 4px;
  padding: 0.2rem 0.5rem;
  font-size: 0.7rem;
  cursor: pointer;
  transition: background 0.2s ease;
}

.delete-btn:hover {
  background: var(--accent-hover);
}

/* Comment content */
.comment-content {
  font-size: 0.85rem;
  color: var(--text-light);
}

/* Post form */
.post-form {
  background: var(--bg-card);
  padding: 1rem;
  border-radius: 12px;
}

.post-form h3 {
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  color: var(--text-light);
}

.post-form input,
.post-form textarea {
  width: 100%;
  margin-bottom: 0.5rem;
  padding: 0.5rem;
  border: 1px solid var(--accent);
  border-radius: 4px;
  background: rgba(255, 255, 255, 0.1);
  color: var(--text-light);
}

.post-form textarea {
  height: 100px;
  resize: vertical;
}

.post-form button {
  background: var(--accent);
  color: #fff;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  font-size: 0.9rem;
  cursor: pointer;
  transition: background 0.2s ease;
}

.post-form button:hover {
  background: var(--accent-hover);
}

/* Comment form */
.comment-form {
  margin-top: 0.5rem;
  padding-left: 1rem;
}

.comment-form input,
.comment-form textarea {
  width: 100%;
  margin-bottom: 0.3rem;
  padding: 0.4rem;
  border: 1px solid var(--accent);
  border-radius: 4px;
  background: rgba(255, 255, 255, 0.1);
  color: var(--text-light);
}

.comment-form textarea {
  height: 60px;
  resize: vertical;
}

.comment-form button {
  background: var(--accent);
  color: #fff;
  border: none;
  padding: 0.4rem 0.8rem;
  border-radius: 6px;
  font-size: 0.85rem;
  cursor: pointer;
  transition: background 0.2s ease;
}

.comment-form button:hover {
  background: var(--accent-hover);
}

/* Responsive tweaks */
@media (max-width: 768px) {
  .forum-section {
    padding: 0.5rem;
  }

  .forum-rules h2 {
    font-size: 1.3rem;
  }

  .forum-rules li {
    font-size: 0.85rem;
  }

  .post-form h3 {
    font-size: 1.1rem;
  }

  .post-form input,
  .post-form textarea {
    font-size: 0.9rem;
  }
}

@media (max-width: 480px) {
  .forum-rules {
    padding: 0.5rem;
  }

  .post-card {
    padding: 0.5rem;
  }

  .post-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .post-time {
    margin-top: 0.2rem;
  }

  .comment-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .comment-time {
    margin-top: 0.2rem;
  }
}
</style>