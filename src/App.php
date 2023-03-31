<?php
    namespace Slate;

    class App{
        public $connection;

        public function __construct(){
            $this->connection = new Connection();
        }

        public static function start(){

        }
        public function query($sql){
            return $this->connection->query($sql);
            // usage
            // $app->query('SELECT * FROM users');
        }

        /* 
        * select data from the database
        * @param string $table
        * @param array $columns
        * @param array $where
        * @param array $order
        * @param array $limit
        * @return array
        */

        public function selectWhere($table, $columns, $where, $order = [], $limit = []){
            $sql = "SELECT ";
            $sql .= implode(', ', $columns);
            $sql .= " FROM $table";
            $sql .= " WHERE ";
            $sql .= implode(' AND ', $where);
            if(count($order) > 0){
                $sql .= " ORDER BY ";
                $sql .= implode(', ', $order);
            }
            if(count($limit) > 0){
                $sql .= " LIMIT ";
                $sql .= implode(', ', $limit);
            }
            return $this->connection->select($sql);
            // usage
            // $app->selectWhere('users', ['*'], ['id = 1']);
            // $app->selectWhere('users', ['*'], ['id = 1', 'name = "John"']);
            // $app->selectWhere('users', ['*'], ['id = 1', 'name = "John"'], ['id DESC']);
            // $app->selectWhere('users', ['*'], ['id = 1', 'name = "John"'], ['id DESC'], [0, 10]);
        }

        /*
        * select data from the database
        * @param string $table
        * @param array $columns
        * @param array $order
        * @param array $limit
        * @return array
        */

        public function select($table, $columns, $order = [], $limit = []){
            $sql = "SELECT ";
            $sql .= implode(', ', $columns);
            $sql .= " FROM $table";
            if(count($order) > 0){
                $sql .= " ORDER BY ";
                $sql .= implode(', ', $order);
            }
            if(count($limit) > 0){
                $sql .= " LIMIT ";
                $sql .= implode(', ', $limit);
            }
            return $this->connection->select($sql);
            // usage
            // $app->select('users', ['*']);
            // $app->select('users', ['*'], ['id DESC']);
            // $app->select('users', ['*'], ['id DESC'], [0, 10]);
        }

        /*
        * insert data into the database
        * @param string $table
        * @param array $data
        * @return bool
        */

        public function insert($table, $data){
            
            $sql = "INSERT INTO $table (";
            $sql .= implode(', ', array_keys($data));
            $sql .= ") VALUES (";
            $sql .= "'" . implode("', '", array_values($data)) . "'";
            $sql .= ")";
            if($this->connection->insert($sql)){
                return true;
            }
            else{
                return false;
            }
        }

        /*
        * update data in the database
        * @param string $table
        * @param array $data
        * @param array $where
        * @return bool
        */

        public function update($table, $data, $where){
            $sql = "UPDATE $table SET ";
            $sql .= implode(', ', $data);
            $sql .= " WHERE ";
            $sql .= implode(' AND ', $where);

            if($this->connection->update($sql)){
                return true;
            }
            else{
                return false;
            }
        }

        /*
        * delete data from the database
        * @param string $table
        * @param array $where
        * @return bool
        */

        public function delete($table, $where){
            $sql = "DELETE FROM $table WHERE ";
            $sql .= implode(' AND ', $where);

            if($this->connection->delete($sql)){
                return true;
            }
            else{
                return false;
            }
        }

        /*
        * get the last inserted id
        * @return int
        */

        public function lastId(){
            return $this->connection->lastId();
        }

        // error handling
        public function error($code){
            $root = $_SERVER['DOCUMENT_ROOT'];
            $path = "error";
            $view = $code;
            $data = [];
            render($view, $path, $data);
        }

        // get the current url
        public function url(){
            $url = $_SERVER['REQUEST_URI'];
            $url = explode('?', $url);
            return $url[0];

            // usage
            // $app->url();
        }

        // custom error handler
        public function errorHandler($code, $message, $file, $line){
            $this->error($code);

            // log the error
            $this->log($message, $file, $line);
            // usage 
            // $app->errorHandler(404, 'Page not found', 'index.php', 10);
        }

        // custom exception handler
        public function exceptionHandler($exception){
            $this->error(500);

            // log the error
            $this->log($exception->getMessage(), $exception->getFile(), $exception->getLine());
            // usage
            // $app->exceptionHandler(new Exception('Page not found', 404));
        }

        // log the error
        public function log($message, $file, $line){
            $log = $message . ' in ' . $file . ' on line ' . $line;
            $log = '[' . date('Y-m-d H:i:s') . '] ' . $log . PHP_EOL;
            $log = str_replace('\\', '/', $log);
            $log = str_replace(' ', '_', $log);
            $log = str_replace(':', '-', $log);
            $log = str_replace('/', '-', $log);
            $log = str_replace(']', '', $log);
            $log = str_replace('[', '', $log);
            // usage
            // $app->log('Page not found', 'index.php', 10);
        }

        // get the current page
        public function page(){
            $url = $this->url();
            $url = explode('/', $url);
            $url = array_filter($url);
            $url = array_values($url);
            return $url[0];

            // usage
            // $app->page();
        }

        // middleware for authentication and authorization 
        public function middleware($middleware){
            $middleware = new $middleware;
            return $middleware->handle();

            // usage
            // $app->middleware('Auth');
            // usage in Auth.php
            // public function handle(){
            //     if(!isset($_SESSION['user'])){
            //         return redirect('login');
            //     }
        }

        // redirect to a page
        public function redirect($page){
            header('Location: ' . $page);
            exit;

            // usage
            // $app->redirect('login');
        }

        // get the current user
        public function user(){
            if(isset($_SESSION['user'])){
                return $_SESSION['user'];
            }
            else{
                return false;
            }

            // usage
            // $app->user();
        }

        // get the current user id
        public function userId(){
            if(isset($_SESSION['user'])){
                return $_SESSION['user']['id'];
            }
            else{
                return false;
            }

            // usage
            // $app->userId();
        }

       // method to extend another part of a page in another page
       public function extend($view, $path, $data = []){
        $root = $_SERVER['DOCUMENT_ROOT'];
        $path = $path;
        $view = $view;
        $data = $data;
        render($view, $path, $data);

        // usage
        // $app->extend('header', 'layouts');
        // $app->extend('footer', 'layouts');
       }

         // method to include another part of a page in another page
            public function include($view, $path, $data = []){
                $root = $_SERVER['DOCUMENT_ROOT'];
                $path = $path;
                $view = $view;
                $data = $data;
                render($view, $path, $data);
        
                // usage
                // $app->include('header', 'layouts');
                // $app->include('footer', 'layouts');
            }

        // method to render a view
        public function render($view, $path, $data = []){
            $root = $_SERVER['DOCUMENT_ROOT'];
            $path = $path;
            $view = $view;
            $data = $data;
            render($view, $path, $data);

            // usage
            // $app->render('index', 'pages');
        }

        public static function locale(){
           if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
                $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                $parts = explode(',', $language);
                $locale = substr($parts[0], 0, -2);
               return $locale;
           }
           else{
               return 'en';
           }
        }
    }
?>