const express = require('express');
const http = require('http');

const { Server } = require('socket.io');
const cors = require('cors');

const app = express();

app.use(cors());

const server = http.createServer(app);

const io = new Server(server, {
    cors: {
        origin: '*'
    }
});

io.on('connection', (socket) => {

    console.log('Client connected');

    socket.on('join_show', (showId) => {
        socket.join(showId);

    });

    socket.on('disconnect', () => {
        console.log('Disconnected');
    });
    
});

app.use(express.json());

app.post('/emit-seat-update', (req, res) => {

    const {
        show_id,
        seat_ids,
        status
    } = req.body;

    io.to(show_id).emit(
        'seat_update',
        {
            seat_ids,
            status
        }
    );

    res.json({
        success: true
    });
});

server.listen(3001, () => {
    console.log(
        'Socket server running on 3001'
    );
});