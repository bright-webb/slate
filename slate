<?php
    #!/usr/bin/env php

    // This is the path to the server.php file
    require __DIR__ . '/server.php';

    $port = 5000;
    $host = 'localhost';

    if($_SERVER['argc'] > 1){
        $args = $_SERVER['argv'];
        $command = $args[1]; // 

        if($command === 'start'){
            for($i = 2; $i < $_SERVER['argc']; $i++){
                $arg = $args[$i];
                if($arg === '-p'){
                    $port = $args[$i + 1];
                }
                if($arg === '-h'){
                    $host = $args[$i + 1];
                }
            }

            startServer();
        }
        elseif($command === 'stop'){
           stopServer();
        }

        elseif($command === 'restart'){
            stopServer();
            startServer();
        }

        elseif($command === 'status'){
            exec('netstat -ano | findstr :5000', $output);
            if (count($output) > 0) {
                echo "Server is running\n";
            } else {
                echo "Server is not running\n";
            }
        }
        
        else {
            echo "Invalid command";
        }
    }
    else{
        echo "Usage: php server.php [start|stop|restart|status] [-p PORT] [-h HOST]";
    }
?>