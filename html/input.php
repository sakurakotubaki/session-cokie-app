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