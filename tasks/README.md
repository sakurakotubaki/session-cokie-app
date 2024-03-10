## PHPとデータベースを接続する
MySQLでタスクテーブルを作成するためのSQLコマンドは以下の通りです。ここでは、テーブル名をtasksとしています。

まず、既に選択されているデータベース（前回の例ではmyDatabase）に対して、タスクテーブルを作成します。テーブルにはid（主キー）、title（タスクのタイトル）、description（タスクの詳細）、status（タスクの状態）、due_date（タスクの期限）の5つのカラムを設定します。

```sql
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('TO_DO', 'IN_PROGRESS', 'DONE') NOT NULL DEFAULT 'TO_DO',
    due_date DATE
);
```

MySQLのINSERT INTOコマンドを使用して、タスクテーブルにダミーのデータを3件挿入します。以下にそのSQLコマンドを示します。

```sql
INSERT INTO tasks (title, description, status, due_date) VALUES
('Shopping', 'Buy groceries for the week', 'TO_DO', '2022-12-31'),
('Housework', 'Clean the entire house', 'IN_PROGRESS', '2022-12-30'),
('Cleaning', 'Clean the kitchen', 'DONE', '2022-12-29');
```

テーブルのデータを確認する
```sql
SELECT * FROM tasks LIMIT 3;
```

## PHPのコード
まず、PHPとMySQLを接続するためのコードを書きます。このコードは、db_connect.phpというファイルに保存します。PDO（PHP Data Objects）を使用して、エラーハンドリングとSQLインジェクション対策を行います。
```php
<?php
<?php
// db_connect.php
// Docker Composeを使用している場合、MySQLのホスト名はサービス名（この場合はdb）になります。
$host = 'db';
$db   = 'myDatabase';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);
?>
```

次に、データを取得するコードを書きます。このコードは、get_data.phpというファイルに保存します。
```php
<?php
// get_data.php
require 'db_connect.php';

function get_data() {
    global $pdo;

    try {
        $sql = "SELECT * FROM tasks";
        $stmt = $pdo->query($sql);
        $tasks = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        $tasks = [];
    }

    return $tasks;
}
?>
```

そして、データを追加するコードを書きます。このコードは、add_data.phpというファイルに保存します。
```php
<?php
// add_data.php
require 'db_connect.php';

$title = 'New Task';
$description = 'This is a new task';
$status = 'TO_DO';
$due_date = '2023-01-01';

$sql = "INSERT INTO tasks (title, description, status, due_date) VALUES (?, ?, ?, ?)";
$stmt= $pdo->prepare($sql);
$stmt->execute([$title, $description, $status, $due_date]);
?>
```

最後に、HTMLでデータを表示するコードを書きます。このコードは、index.phpというファイルに保存します。PHPのforeachを使用して、取得したタスクのリストをループします。

```php
<?php
// index.php
require 'get_data.php';
$tasks = get_data();
?>
<!DOCTYPE html>
<html>
<body>
    <h1>Task List</h1>
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <h2><?= $task['title'] ?></h2>
                <p><?= $task['description'] ?></p>
                <p>Status: <?= $task['status'] ?></p>
                <p>Due date: <?= $task['due_date'] ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
```