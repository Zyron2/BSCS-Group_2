import './bootstrap';

const API_BASE = 'http://127.0.0.1:8000/api/notifications';

const badgeEl = document.getElementById('notification-badge');
const iconEl = document.getElementById('notification-icon');
const panelEl = document.getElementById('notification-panel');
const listEl = document.getElementById('notification-list');
const dashListEl = document.getElementById('dashboard-notification-list');
const markAllBtn = document.getElementById('mark-all-read');

async function fetchUnreadCount() {
  const resp = await fetch(`${API_BASE}/unread_count`, { credentials: 'include' });
  const { unreadCount } = await resp.json();
  if (unreadCount > 0) {
    badgeEl.innerText = unreadCount;
    badgeEl.style.display = 'inline';
  } else {
    badgeEl.style.display = 'none';
  }
}

async function fetchRecentNotifications() {
  const resp = await fetch(`${API_BASE}/recent`);
  const { notifications } = await resp.json();
  listEl.innerHTML = '';
  dashListEl.innerHTML = '';
  notifications.forEach(n => {
    const item = document.createElement('div');
    item.className = `notification-item ${n.isRead ? '' : 'unread'}`;
    item.innerHTML = `<strong>${n.title}</strong><div>${n.description || ''}</div><small>${new Date(n.createdAt).toLocaleString()}</small>`;
    item.onclick = () => {
      markRead(n.id);
      if (n.link) window.location.href = n.link;
    };
    listEl.appendChild(item);

    const dashItem = document.createElement('li');
    dashItem.className = n.isRead ? '' : 'unread';
    dashItem.innerText = `${n.title} (${new Date(n.createdAt).toLocaleTimeString()})`;
    dashListEl.appendChild(dashItem);
  });
}

async function markRead(id) {
  await fetch(`${API_BASE}/mark_read`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ notification_id: id })
  });
  await refresh();
}

markAllBtn.onclick = async () => {
  // For demo: mark all visible as read
  const items = await fetch(`${API_BASE}/recent`);
  const { notifications } = await items.json();
  for (const n of notifications) {
    if (!n.isRead) await markRead(n.id);
  }
};

iconEl.onclick = () => {
  panelEl.classList.toggle('hidden');
};

function initSocket() {
  const socket = io('http://localhost:4000');
  socket.on('notification', data => {
    const n = data.notification;
    showToast(n.title);
    fetchUnreadCount();
    fetchRecentNotifications();
  });
}

function showToast(message) {
  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.innerText = message;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 4000);
}

// initial load
refresh = async () => {
  await fetchUnreadCount();
  await fetchRecentNotifications();
}
async function fetchUnreadCount() {
  try {
    const resp = await fetch(`${API_BASE}/unread_count`, { credentials: 'include' });
    if (!resp.ok) {
      console.error('fetchUnreadCount error', resp.status, await resp.text());
      return;
    }
    const { unreadCount } = await resp.json();
  } catch (err) {
    console.error('fetchUnreadCount exception', err);
  }
}

refresh();
initSocket();
