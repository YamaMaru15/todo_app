<?php

session_start();

// データベースにアクセスするための準備、定数定義
define('DSN', 'mysql:host=127.0.0.1;port=3306;dbname=todo_list;charset=utf8;');
define('DB_USER', 'C9_USER');
define('DB_PASSWORD', '');
define('SITE_URL', 'https://fbfbbdbbb97045ba8bb52556205a0d38.vfs.cloud9.ap-southeast-2.amazonaws.com/work/public/index.php');
// define('SITE_URL', 'https://' . $_SERVER['HTTP_HOST']);

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