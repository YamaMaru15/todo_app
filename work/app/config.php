<?php

session_start();

// データベースにアクセスするための準備、定数定義
define('DSN', 'mysql:dbname=todo_app;host=localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'password');
define('SITE_URL', 'http://localhost/todo_app/work/public/');
// define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST']);

spl_autoload_register(function($class) {
    // 名前空間を抜かした名前をロードする
    $prefix = 'TodoApp\\';
    
    //名前空間が先頭 0 番目だったら、
    if(strpos($class, $prefix) === 0){
        // 名前空間文の文字列をstrlenでぬかす。
        $fileName = sprintf(__DIR__ . '/%s.php', substr($class, strlen($prefix)));    
        if(file_exists($fileName)) {
            require($fileName);        
        } else {
            echo 'File not found:' . $fileName;
            exit;
        }
    }
});