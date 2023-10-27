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
    <!-- delete purge toggle処理については、非同期処理（fetch送信→画面の更新→DB更新）-->
    <!-- add処理については、非同期処理（fetch送信→DB更新→画面のTodoの追加）-->

    <!-- 今までは各要素(toggle delete purge)でトークンを送信していたが簡潔化 -->
    <main data-token="<?= Utils::h($_SESSION['token']); ?>">
        <header>
            <h1>Todos</h1>
            <span class="purge">Purge</span>
        </header>
        
        <form action="?action=add" method="post">
            <input type="text" name="title" placeholder="Type new todo."/>
        </form>
        
        <ul>
            <!--データの数だけforeach埋め込む-->
            <?php foreach ($todos as $todo): ?> 
                <!-- カスタムデータ属性を設定 もとinputのvalueで送信していたid> -->
                <!-- idはフォームから送られたものかどうかを判定 -->
                <li data-id="<?= Utils::h($todo->id); ?>">
                    <!--データベースのis_doneがtrueかfalseかでチェック未チェックの判定-->
                    <!--クエリ文字列でpostフォームを区別する-->
                    <input type="checkbox" <?= $todo->is_done ? 'checked' : ''; ?>>
                    <span><?= Utils::h($todo->title); ?></span>
                    <span class="delete">✕</span>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
    
    <script type="text/javascript" src="js/main.js"></script>
</body>

</html>