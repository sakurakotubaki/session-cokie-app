# sessionとcookie

## session
セッションとはサーバーに一時的なデータを保存する手法
セッションは、一時的なデータをサーバーに保存する事ができる仕組みです。 ブラウザにはセッションIDという鍵をブラウザに保存しておくことで、ページを訪れた際にIDを照合することでデータを取得することができます。

## cookie
クッキーはブラウザ側でデータを一時的に保存する仕組みです。クッキーはサーバーからブラウザに送られ、ブラウザはそれを保存し、次回そのサイトを訪れるときにクッキーをサーバーに送り返します。

## 簡単な入力フォームでの例
ユーザーの情報を入力して確認するページがよくありますよね。裏側では、sessionとcookieの技術が使われています。

こちらの入力ページで、クッキーとセッションに情報を保持させて、次のページで誰のデータか特定して、表示することができます。

```php
<!-- 入力ページ (input.php) -->
<!-- sessionにデータを１時的に保存する -->
<!DOCTYPE html>
<html>
<body>
    <form action="confirm.php" method="post">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo isset($_COOKIE['name']) ? $_COOKIE['name'] : '' ?>"><br>
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email"><br>
        <label for="subject">Subject:</label><br>
        <input type="text" id="subject" name="subject"><br>
        <label for="message">Message:</label><br>
        <textarea id="message" name="message"></textarea><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
```

セッションとクッキーの機能を使ってページに訪れたユーザーを特定して、以前入力した情報を表示することができます。
ちなみ、最初のページへ戻るボタンを押すと、JavaScriptのDOMの操作で、最初のページへページを移動することができる。

```php
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
```
