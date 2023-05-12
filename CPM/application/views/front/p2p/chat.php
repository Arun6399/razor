<h1 class="text-center">Socket Io Chat </h1>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script src="socket.io.js"></script> 
<script> 

function fun()
{
    var socket = new io.Socket('localhost',{'port':8090});

    socket.connect();

    socket.on('connect', function(){
        console.log('connected');
        socket.send('hi!'); 
    });


    socket.on('message', function(data){ 
        console.log('message recived: ' + data);
    });

    socket.on('disconnect', function(){
        console.log('disconected');
    });
}

$(document).ready(function() {
    fun();
});
</script> 