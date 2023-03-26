<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.socket.io/socket.io-1.3.5.js"></script>
<script>
    $(document).ready(function() {

        var socket = io.connect('http://localhost:8890');

        socket.on('notification', function (data) {

            var message = JSON.parse(data);

            $( "#notifications" ).prepend( "<p><strong>" + message.name + "</strong>: " + message.message + "</p>" );

        });

    });
</script>