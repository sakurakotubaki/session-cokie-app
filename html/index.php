<?php
// index.php
require 'get_data.php';
$tasks = get_data();
?>
<!DOCTYPE html>
<html>
<body>
    <h1>Task List</h1>
    <a href="input.php">クッキーとセッション</a>
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