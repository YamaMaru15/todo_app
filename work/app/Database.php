<?php

namespace TodoApp;

class Database {
    
    /* 呼び出す$instance の値が必ず一つになるようにクラス変数を作成する
       getInstance() が呼ばれるたびにデータベースに
    　 接続してしまうと複数の接続ができてしまって無駄になってしまう。
    　 $instanceがからの時だけデータベースにアクセスする。
    　 これは機能拡張したりして行くうえで、大切な考え方。
       
    */
    private static $instance;

    public static function getInstance() {
        // pdoでデータベースにアクセス
        try{
            if(!isset(self::$instance)) {
                 self::$instance = new \PDO(
                DSN, 
                getenv(DB_USER), 
                DB_PASSWORD,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, //エラーを取得する
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ, //オブジェクト形式で結果を取得
                    \PDO::ATTR_EMULATE_PREPARES => false, //SQLに合わせた型で取得(自動型変換されない)
                ]
                );
            }

            
            return self::$instance;
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }
}