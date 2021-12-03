<?php
session_start();
require_once('../config.php');
require_once('../function.php');

$receiver_id = $_POST['receiver_id'];

require_once('../header.php');
require_once('../navbar.php');
?>
<h1><?= NAME ?></h1>
<form method="POST" onsubmit="send_message(); return false;">
    <input type="hidden" id="receiver_id" value="<?= $receiver_id ?>">
    <textarea id="chat_text" cols="30" rows="10"></textarea><br>
    <input type="submit" value="送信">
</form>

<div id="messageTextBox"></div>

<?php require_once('../footer.php'); ?>

<script text="text/javascript">
    $(function () {
        read_message();
        setInterval('read_message()', 3000);
    });
</script>