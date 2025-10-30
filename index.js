const express = require('express');
const http = require('http');
const cors = require('cors');
const socketio = require('socket.io');

const notificationRoutes = require('./routes/notifications');
const socket = require('./socket');

const app = express();
app.use(cors());
app.use(express.json());

app.use('/api/notifications', notificationRoutes);

const server = http.createServer(app);
const io = socketio(server, {
  cors: { origin: '*' }
});
socket.init(io);

const PORT = 4000;
server.listen(PORT, () => {
  console.log(`🚀 Server running on port ${PORT}`);
});
