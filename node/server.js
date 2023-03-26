const http = require('http');
const  port = process.env.PORT || 8890;
var app = require('express')();
var server = require('http').Server(app);

var io = require('socket.io')(server);
var redis = require('redis');

app.use(function(request, response, next) {
    console.log(request.url);
    response.end('Server.js started!');
    next()
});

server_listen(port, function(a, port_used) {
    if (!port_used) {
        server.listen(port);
        console.log('Server.js started on port: ' + port);

    } else {
        console.log('Server.js is lisenting on port: ' + port);
    }
});

io.on('connection', function (socket) {
    console.log('a user connected');

    var redisClient = redis.createClient();

    redisClient.subscribe('notification'); // see SmartscreenSchedulesController / Update

    redisClient.on("message", function(channel, message) {
        console.log("New message: " + message + ". In channel: " + channel);
        socket.emit(channel, message);
    });

    socket.on('notification', function(msg){
        console.log('message: ' + msg);
        io.emit('notification', msg);

        // socket.broadcast.emit('chat message', msg);
    });

    socket.on('disconnect', function () {
        console.log('user disconnected');
    });
});

function server_listen(port, fn) {
    var success_ix = 0;
    var net = require('net')
    var test_ipv4 = net.createServer()
        .once('error', function (err) {
            if (err.code != 'EADDRINUSE') return fn(err)
            fn(null, true)
        })
        .once('listening', function() {
            test_ipv4.once('close', function() { success_ix++; if (success_ix == 2) fn(null, false) })
                .close()
        })
        .listen(port, '127.0.0.1');

    var test_ipv6 = net.createServer()
        .once('error', function (err) {
            if (err.code != 'EADDRINUSE') return fn(err)
            fn(null, true)
        })
        .once('listening', function() {
            test_ipv6.once('close', function() { success_ix++; if (success_ix == 2) fn(null, false) })
                .close()
        })
        .listen(port, '::');
}