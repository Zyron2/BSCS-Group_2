import express from 'express';
import http from 'http';
import { Server } from 'socket.io';
import cors from 'cors';
import bodyParser from 'body-parser';
import path from 'path';
import { fileURLToPath } from 'url';
import { open } from 'sqlite';
import sqlite3 from 'sqlite3';

// --- Setup DB ---
const db = await open({
  filename: './notifications.sqlite',
  driver: sqlite3.Database
});

// Create table if not exists
await db.exec(`
  CREATE TABLE IF NOT EXISTS notifications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    type TEXT,
    title TEXT,
    message TEXT,
    link TEXT,
    is_read INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )
`);

// --- Express setup ---
const app = express();
app.use(cors());
app.use(bodyParser.json());

// --- Socket.io setup ---
const server = http.createServer(app);
const io = new Server(server, { cors: { origin: "*" } });

// --- Path setup ---
const __dirname = path.dirname(fileURLToPath(import.meta.url));
app.use(express.static(__dirname));

// --- Helpers ---
function timeAgo(date) {
  const now = new Date();
  const diff = Math.floor((now - new Date(date)) / 1000);
  if (diff < 60) return `${diff} seconds ago`;
  const diffMin = Math.floor(diff / 60);
  if (diffMin < 60) return `${diffMin} minutes ago`;
  const diffH = Math.floor(diffMin / 60);
  if (diffH < 24) return `${diffH} hours ago`;
  const diffD = Math.floor(diffH / 24);
  return `${diffD} days ago`;
}

async function sendNotification(userId, type, title, message, link) {
  const result = await db.run(
    'INSERT INTO notifications (user_id, type, title, message, link, is_read) VALUES (?, ?, ?, ?, ?, ?)',
    userId, type, title, message, link, 0
  );
  const notif = await db.get('SELECT * FROM notifications WHERE id = ?', result.lastID);
  notif.timeAgo = timeAgo(notif.created_at);
  io.to(`user_${userId}`).emit('new_notification', notif);
  return notif;
}

// --- Routes ---

// Serve main HTML file
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'index.html'));
});

// Fetch notifications
app.get('/api/notifications/:userId', async (req, res) => {
  const userId = parseInt(req.params.userId);
  const notifs = await db.all(
    'SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC',
    userId
  );
  const withTime = notifs.map(n => ({ ...n, timeAgo: timeAgo(n.created_at) }));
  res.json(withTime);
});

// Mark as read
app.post('/api/notifications/mark_read/:id', async (req, res) => {
  const id = parseInt(req.params.id);
  await db.run('UPDATE notifications SET is_read = 1 WHERE id = ?', id);
  res.json({ ok: true });
});

// --- Demo notification endpoints ---
app.post('/demo/move_booking', async (req, res) => {
  const { userId, room, oldTime, newTime } = req.body;
  const notif = await sendNotification(
    userId,
    "moved",
    `${room} booking moved`,
    `Moved from ${oldTime} to ${newTime} ⚠️`,
    "/bookings"
  );
  res.json(notif);
});

app.post('/demo/cancel_booking', async (req, res) => {
  const { userId, room, date } = req.body;
  const notif = await sendNotification(
    userId,
    "cancelled",
    `${room} booking cancelled`,
    `Cancelled for ${date} ❌`,
    "/bookings"
  );
  res.json(notif);
});

app.post('/demo/new_request', async (req, res) => {
  const { userId, room } = req.body;
  const notif = await sendNotification(
    userId,
    "new_request",
    `New booking request for ${room}`,
    `You have a new request ✅`,
    "/requests"
  );
  res.json(notif);
});

// --- Socket.io events ---
io.on('connection', (socket) => {
  console.log('Socket connected:', socket.id);
  socket.on('join', (userId) => {
    console.log(`User joined room: user_${userId}`);
    socket.join(`user_${userId}`);
  });
});

// --- Start server ---
const PORT = process.env.PORT || 3000;
server.listen(PORT, () => console.log(`✅ Server running on port ${PORT}`));
