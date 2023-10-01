<?php

require_once(__DIR__ . '/../app/config.php');

/* \クラス名が出てきたら自動的にTodoAppを付けて呼び出される
 indexクラスは名前空間に属していないため、ここで呼び出される関数に
 関しては、「この名前空間に属しているよと教えてあげなければならない。
 同じ名前空間通しで呼び出す場合は、useを使う必要はない。」
*/
use TodoApp\Database;
use TodoApp\Todo;
use TodoApp\Utils;

$pdo = Database::getInstance();

$todo = new Todo($pdo);
// postで処理されたデータを処理する
$todo->processPost();
// データベースからデータの取得　$todoを表示するために配列を取得する
$todos = $todo->getAll();

?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>Todo管理</title>
    <link rel="stylesheet" href="css/styles.css" />
</head>
<body>
    <main>
        <header>
            <h1>Todos</h1>
            <form action="?action=purge" method="post">
                <span class="purge">Purge</span>
                <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>"/>
            </form>
        </header>
        
        <!--クエリ文字列でpostフォームを区別する-->
        <form action="?action=add" method="post">
            <input type="text" name="title" placeholder="Type new todo."/>
            <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>"/>
        </form>
        
        <ul>
            <!--データの数だけforeach埋め込む-->
            <?php foreach ($todos as $todo): ?> 
            <li>
                <!--データベースのis_doneがtrueかfalseかでチェック未チェックの判定-->
                <!--クエリ文字列でpostフォームを区別する-->
                <form action="?action=toggle" method="post">
                    <input type="checkbox" <?= $todo->is_done ? 'checked' : ''; ?>>
                    <input type="hidden" name="id" value="<?= Utils::h($todo->id); ?>"/>
                    <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>"/>
                </form>
                <span class ="<?= $todo->is_done ? 'done' : ''; ?>">
                    <?= Utils::h($todo->title); ?>
                </span>
                
                <form action="?action=delete" method="post" class="delete-form">
                    <span class="delete">✕</span>
                    <input type="hidden" name="id" value="<?= Utils::h($todo->id); ?>"/>
                    <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>"/>
                </form>
            </li>
            <?php endforeach; ?>
        </ul>
    </main>
    
    <script type="text/javascript" src="js/main.js"></script>
</body>

</html>