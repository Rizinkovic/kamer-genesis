const express = require('express');
const cors = require('cors');
const admin = require('firebase-admin');
const fs = require('fs');
const path = require('path');

const app = express();
// Use CORS with multiple origins for local development
app.use(cors({
  origin: ['http://localhost', 'http://localhost/kamer-genesis/public'], // Allow both origins
  methods: ['GET', 'POST', 'DELETE', 'OPTIONS'], // Explicitly allow methods
  allowedHeaders: ['Content-Type', 'x-admin-key'] // Allow custom headers
}));
app.use(express.json());

const serviceAccountPath = path.join(__dirname, 'kamergenesis-firebase-adminsdk-fbsvc-266bf56dc3.json');
if (!fs.existsSync(serviceAccountPath)) {
    console.error('Firebase service account file not found!');
    process.exit(1);
}

const serviceAccount = JSON.parse(fs.readFileSync(serviceAccountPath, 'utf8'));
admin.initializeApp({
    credential: admin.credential.cert(serviceAccount)
});

const db = admin.firestore();

// Admin UID (replace with your actual admin UID from Firebase Auth)
const ADMIN_UID = 'your-admin-uid-here'; // Update this with the real UID

app.get('/', (req, res) => {
    res.send('KamerGenesis API is running');
});

app.get('/search', async (req, res) => {
    const q = req.query.q?.trim().toLowerCase();
    if (!q) return res.json([]);

    try {
        const snapshot = await db
            .collection('books')
            .where('search_keywords', 'array-contains', q)
            .get();

        if (snapshot.empty) {
            return res.json([]);
        }

        const books = snapshot.docs.map(doc => ({
            id: doc.id,
            ...doc.data()
        }));
        console.log('Search results:', books);
        res.json(books);
    } catch (err) {
        console.error('Error fetching books:', err);
        res.status(500).json({ error: 'Failed to fetch books' });
    }
});

app.get('/book/:id', async (req, res) => {
    const id = req.params.id.trim();
    if (!id) return res.status(400).json({ error: 'Book ID required' });

    try {
        const doc = await db.collection('books').doc(id).get();
        if (!doc.exists) {
            console.log(`Book with ID ${id} not found`);
            return res.status(404).json({ error: 'Book not found' });
        }
        res.json({ id: doc.id, ...doc.data() });
    } catch (err) {
        console.error('Error fetching book:', err);
        res.status(500).json({ error: 'Failed to fetch book' });
    }
});

app.post('/book/:id/visit', async (req, res) => {
    const id = req.params.id.trim();
    if (!id) return res.status(400).json({ error: 'Book ID required' });

    try {
        const bookRef = db.collection('books').doc(id);
        const doc = await bookRef.get();
        if (!doc.exists) {
            console.log(`Book with ID ${id} not found`);
            return res.status(404).json({ error: 'Book not found' });
        }

        await bookRef.update({
            visit_count: admin.firestore.FieldValue.increment(1)
        });

        const updatedDoc = await bookRef.get();
        const visitCount = updatedDoc.data().visit_count || 0;
        console.log(`Visit count for ${id}: ${visitCount}`);
        res.json({ visit_count: visitCount });
    } catch (err) {
        console.error('Error updating visit count:', err);
        res.status(500).json({ error: 'Failed to update visit count' });
    }
});

app.post('/book/:id/download', async (req, res) => {
    const id = req.params.id.trim();
    if (!id) return res.status(400).json({ error: 'Book ID required' });

    try {
        const bookRef = db.collection('books').doc(id);
        const doc = await bookRef.get();
        if (!doc.exists) {
            console.log(`Book with ID ${id} not found`);
            return res.status(404).json({ error: 'Book not found' });
        }

        await bookRef.update({
            download_count: admin.firestore.FieldValue.increment(1)
        });

        const updatedDoc = await bookRef.get();
        const downloadCount = updatedDoc.data().download_count || 0;
        console.log(`Download count for ${id}: ${downloadCount}`);
        res.json({ download_count: downloadCount });
    } catch (err) {
        console.error('Error updating download count:', err);
        res.status(500).json({ error: 'Failed to update download count' });
    }
});

app.get('/books/latest', async (req, res) => {
  const limit = parseInt(req.query.limit) || 12;
  try {
    const snapshot = await db
      .collection('books')
      .orderBy('created_at', 'desc')
      .limit(limit)
      .get();

    if (snapshot.empty) {
      return res.json([]);
    }

    const books = snapshot.docs.map(doc => ({
      id: doc.id,
      ...doc.data()
    }));

    res.json(books);
  } catch (err) {
    console.error('Error fetching latest books:', err);
    res.status(500).json({ error: 'Failed to fetch latest books' });
  }
});

app.get('/books/most-downloaded', async (req, res) => {
  const limit = parseInt(req.query.limit) || 12;
  try {
    const snapshot = await db.collection('books')
      .orderBy('download_count', 'desc')
      .orderBy('visit_count', 'desc')
      .limit(limit)
      .get();
    const books = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
    res.json(books);
  } catch (err) {
    res.status(500).json({ error: 'Failed to fetch most downloaded books' });
  }
});

app.get('/books/most-viewed', async (req, res) => {
  const limit = parseInt(req.query.limit) || 12;
  try {
    const snapshot = await db.collection('books')
      .orderBy('visit_count', 'desc')
      .limit(limit)
      .get();
    const books = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
    res.json(books);
  } catch (err) {
    res.status(500).json({ error: 'Failed to fetch most viewed books' });
  }
});

// Add a new post
app.post('/forum/post', async (req, res) => {
  const { pseudo, content } = req.body;
  if (!pseudo || !content) return res.status(400).json({ error: 'Pseudo and content required' });

  try {
    const postRef = await db.collection('forum').add({
      pseudo,
      content,
      created_at: admin.firestore.FieldValue.serverTimestamp(),
      is_admin: false
    });
    res.json({ id: postRef.id, success: true });
  } catch (err) {
    console.error('Error adding post:', err);
    res.status(500).json({ error: 'Failed to add post' });
  }
});

// Add a comment to a post
app.post('/forum/comment/:postId', async (req, res) => {
  const { postId } = req.params;
  const { pseudo, content } = req.body;
  if (!pseudo || !content) return res.status(400).json({ error: 'Pseudo and content required' });

  try {
    const commentRef = await db.collection('forum').doc(postId).collection('comments').add({
      pseudo,
      content,
      created_at: admin.firestore.FieldValue.serverTimestamp(),
      is_admin: false
    });
    res.json({ id: commentRef.id, success: true });
  } catch (err) {
    console.error('Error adding comment:', err);
    res.status(500).json({ error: 'Failed to add comment' });
  }
});

// Admin post (with golden checkmark)
app.post('/forum/admin-post', async (req, res) => {
  const { pseudo, content } = req.body;
  if (!pseudo || !content) return res.status(400).json({ error: 'Pseudo and content required' });

  // Check if request is from admin (simplified; use auth token in production)
  const isAdmin = req.headers['x-admin-key'] === 'your-admin-secret-key'; // Replace with secure method
  if (!isAdmin) return res.status(403).json({ error: 'Admin access required' });

  try {
    const postRef = await db.collection('forum').add({
      pseudo,
      content,
      created_at: admin.firestore.FieldValue.serverTimestamp(),
      is_admin: true
    });
    res.json({ id: postRef.id, success: true });
  } catch (err) {
    console.error('Error adding admin post:', err);
    res.status(500).json({ error: 'Failed to add admin post' });
  }
});

// Delete a comment (admin only)
app.delete('/forum/comment/:postId/:commentId', async (req, res) => {
  const { postId, commentId } = req.params;
  // Check if request is from admin (simplified; use auth token in production)
  const isAdmin = req.headers['x-admin-key'] === 'your-admin-secret-key'; // Replace with secure method
  if (!isAdmin) return res.status(403).json({ error: 'Admin access required' });

  try {
    await db.collection('forum').doc(postId).collection('comments').doc(commentId).delete();
    res.json({ success: true });
  } catch (err) {
    console.error('Error deleting comment:', err);
    res.status(500).json({ error: 'Failed to delete comment' });
  }
});

// Fetch all posts with comments
app.get('/forum/posts', async (req, res) => {
  try {
    const snapshot = await db.collection('forum').orderBy('created_at', 'desc').get();
    const posts = await Promise.all(snapshot.docs.map(async doc => {
      const commentsSnapshot = await doc.ref.collection('comments').orderBy('created_at', 'asc').get();
      const comments = commentsSnapshot.docs.map(commentDoc => ({
        id: commentDoc.id,
        ...commentDoc.data()
      }));
      return { id: doc.id, ...doc.data(), comments };
    }));
    res.json(posts);
  } catch (err) {
    console.error('Error fetching posts:', err);
    res.status(500).json({ error: 'Failed to fetch posts' });
  }
});

const PORT = 3000;
app.listen(PORT, () => console.log(`KamerGenesis API running on http://localhost:${PORT}`));