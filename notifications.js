const express = require('express');
const router = express.Router();
const db = require('../db');
const socket = require('../socket').getIO();

// middleware demo: assume userId=1 for all calls
router.use((req, res, next) => {
  req.user = { id: 1 };
  next();
});

router.get('/unread_count', (req, res) => {
  const count = db.countUnread(req.user.id);
  res.json({ unreadCount: count });
});

router.get('/recent', (req, res) => {
  const list = db.getRecent(req.user.id, 10);
  res.json({ notifications: list });
});

router.post('/mark_read', (req, res) => {
  const { notification_id } = req.body;
  db.markRead(req.user.id, notification_id);
  res.json({ success: true });
});

// endpoint to create a new notification (for demo purposes)
router.post('/create', (req, res) => {
  const { type, title, description, link } = req.body;
  const notif = db.addNotification({
    userId: req.user.id,
    type, title, description, link
  });
  // push to socket
  socket.to(`user_${req.user.id}`).emit('notification', { notification: notif });
  res.json({ notification: notif });
});

module.exports = router;
