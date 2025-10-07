import express from 'express';
import http from 'http';
import { Server } from 'socket.io';
import cors from 'cors';
import bodyParser from 'body-parser';
import path from 'path';
import { fileURLToPath } from 'url';
import { open } from 'sqlite';
import sqlite3 from 'sqlite3';

const db = await open({
  filename: './notifications.sql',
  driver: sqlite3.Database
});
const dbNotifications = await db.all('SELECT * FROM notifications');
console.log(dbNotifications);
const app = express();
app.use(cors());
app.use(bodyParser.json());

const server = http.createServer(app);
const __dirname = path.dirname(fileURLToPath(import.meta.url));
app.use(express.static(__dirname));
const io = new Server(server, {
  cors: {
    origin: "*"
  }
});

const bell = document.getElementById('notification-bell');
const panel = document.getElementById('notifications-panel');
let panelVisible = false;

bell.addEventListener('click', () => {
  panelVisible = !panelVisible;
  panel.style.display = panelVisible ? 'block' : 'none';
  if (panelVisible) {
    fetch('/api/notifications/1') // Replace 1 with the actual user ID
      .then(res => res.json())
      .then(data => {
        const list = document.getElementById('notifications-list');
        list.innerHTML = '';
        data.forEach(n => {
          const li = document.createElement('li');
          li.className = 'notification-item';
          li.innerHTML = `<div class="notification-icon">🔔</div>
            <div class="notification-content">
              <p class="notification-title">${n.title}</p>
              <p class="notification-time">${n.timeAgo}</p>
            </div>`;
          list.appendChild(li);
        });
      });
  }
});

let notifications = [];
let nextId = 1;

function timeAgo(date) {
  const now = new Date();
  const diff = Math.floor((now - date) / 1000);
  if (diff < 60) return `${diff} seconds ago`;
  const diffMin = Math.floor(diff / 60);
  if (diffMin < 60) return `${diffMin} minutes ago`;
  const diffH = Math.floor(diffMin / 60);
  if (diffH < 24) return `${diffH} hours ago`;
  const diffD = Math.floor(diffH / 24);
  return `${diffD} days ago`;
}

function sendNotification(userId, type, title, message, link) {
  const notif = {
    id: nextId++,
    user_id: userId,
    type,
    title,
    message,
    link,
    is_read: false,
    created_at: new Date()
  };
  notifications.push(notif);
  io.to(`user_${userId}`).emit('new_notification', {
    ...notif,
    timeAgo: timeAgo(notif.created_at)
  });
  return notif;
}

io.on('connection', (socket) => {
  console.log('Socket connected:', socket.id);
  socket.on('join', (userId) => {
    console.log('User joining:', userId);
    socket.join(`user_${userId}`);
  });
});
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'index.html'));
});

app.get('/api/notifications/:userId', (req, res) => {
  const userId = parseInt(req.params.userId);
  const userNotifs = notifications
    .filter(n => n.user_id === userId)
    .sort((a, b) => b.created_at - a.created_at)
    .map(n => ({
      ...n,
      timeAgo: timeAgo(n.created_at)
    }));
  res.json(userNotifs);
});

app.post('/api/notifications/mark_read/:id', (req, res) => {
  const id = parseInt(req.params.id);
  const notif = notifications.find(n => n.id === id);
  if (!notif) {
    res.status(404).json({ error: "Not found" });
    return;
  }
  notif.is_read = true;
  res.json({ ok: true });
});

// Demo endpoints
app.post('/demo/move_booking', (req, res) => {
  const { userId, room, oldTime, newTime } = req.body;
  const title = `${room} booking moved`;
  const message = `Moved from ${oldTime} to ${newTime} ⚠️`;
  const link = "/bookings";
  const notif = sendNotification(userId, "moved", title, message, link);
  res.json(notif);
});

app.post('/demo/cancel_booking', (req, res) => {
  const { userId, room, date } = req.body;
  const title = `${room} booking cancelled`;
  const message = `Cancelled for ${date} ❌`;
  const link = "/bookings";
  const notif = sendNotification(userId, "cancelled", title, message, link);
  res.json(notif);
});

app.post('/demo/new_request', (req, res) => {
  const { userId, room } = req.body;
  const title = `New booking request for ${room}`;
  const message = `You have a new request ✅`;
  const link = "/requests";
  const notif = sendNotification(userId, "new_request", title, message, link);
  res.json(notif);
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
  console.log(`Server listening on port ${PORT}`);
});
