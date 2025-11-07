let notifications = [];  // array of { id, userId, type, title, description, link, isRead, createdAt }

module.exports = {
  notifications,
  addNotification(notification) {
    notification.id = notifications.length + 1;
    notification.createdAt = new Date();
    notification.isRead = false;
    notifications.unshift(notification);
    return notification;
  },
  getRecent(userId, limit=10) {
    return notifications.filter(n => n.userId === userId).slice(0, limit);
  },
  countUnread(userId) {
    return notifications.filter(n => n.userId === userId && !n.isRead).length;
  },
  markRead(userId, notificationId) {
    const n = notifications.find(n => n.id === notificationId && n.userId === userId);
    if (n) n.isRead = true;
    return n;
  }
};
