let io;
function init(serverIO) {
  io = serverIO;
  io.on('connection', socket => {
    console.log(`Socket connected: ${socket.id}`);
    // assume userId=1; in real code you authenticate
    socket.join('user_1');

    socket.on('disconnect', () => {
      console.log(`Socket disconnected: ${socket.id}`);
    });
  });
}
function getIO() {
  return io;
}
module.exports = { init, getIO };
