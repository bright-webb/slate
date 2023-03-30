<?php
    function startServer($port = 5000, $host ='localhost'){
        $command = "php -S {$host}:{$port}";
        exec($command);

        echo "Server started on http://{$host}:{$port}\n";
    }

    function stopServer(){
        exec('taskkill /f /im php.exe');
        echo "Server stopped\n";
    }

    function restartServer(){
        stopServer();
        startServer();
    }

    function status(){
        exec('netstat -ano | findstr :5000', $output);
        if (count($output) > 0) {
            echo "Server is running\n";
        } else {
            echo "Server is not running\n";
        }
    }
?>