<?php
// 確認ページ (confirm.php)
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["name"] = $_POST["name"];
    $_SESSION["email"] = $_POST["email"];
    $_SESSION["subject"] = $_POST["subject"];
    $_SESSION["message"] = $_POST["message"];
    // クッキーの期限は30日
    setcookie("name", $_POST["name"], time() + (86400 * 30), "/"); // 86400 = 1 day
}

$name = $_SESSION["name"];
$email = $_SESSION["email"];
$subject = $_SESSION["subject"];
$message = $_SESSION["message"];
?>
<!-- 確認ページ (confirm.php) -->
<!DOCTYPE html>
<html>
<body>
    <h1>Confirmation</h1>
    <p>Name: <?php echo $name; ?></p>
    <p>Email: <?php echo $email; ?></p>
    <p>Subject: <?php echo $subject; ?></p>
    <p>Message: <?php echo $message; ?></p>
    <button class="index">最初のページへ戻る</button>
    <script>
        // JavaScriptを使用して、ボタンをクリックするイベントが発生したときに、index.phpのページに戻る
        document.querySelector(".index").addEventListener("click", function() {
            // index.phpのページに戻る
            window.location.href = '/';
        });
    </script>
</body>
</html>