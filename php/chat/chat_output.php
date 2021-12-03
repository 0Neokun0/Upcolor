<?php
session_start();
require_once('../config.php');

$receiver_id = $_POST['receiver_id'];

$s = <<<SQL
    SELECT *
    FROM chat
            INNER JOIN accounts
                ON chat.user_id=accounts.user_id OR chat.receiver_id=accounts.user_id
    WHERE ((chat.user_id=? AND chat.receiver_id=?) OR (chat.receiver_id=? AND chat.user_id=?)) AND accounts.user_id!=?
    ORDER BY chat_id;
SQL;
$sql = $pdo -> prepare($s);
$sql -> execute([ID, $receiver_id, ID, $receiver_id, ID]);

foreach ($sql as $row) {
    echo <<<HTML
        $row[chat_text]<br>
    HTML;
}
?>