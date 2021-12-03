<?php
session_start();
require_once('../config.php');
require_once('../function.php');

$select_chat_all = <<<SQL
    SELECT chat_text, accounts.user_id, accounts.user_name, accounts.profile_img
    FROM chat
            INNER JOIN accounts
                ON chat.user_id=accounts.user_id OR chat.receiver_id=accounts.user_id
    WHERE chat_id IN (
            SELECT MAX(chat_id)
            FROM chat
                    LEFT JOIN accounts
                        ON chat.user_id=accounts.user_id OR chat.receiver_id=accounts.user_id
            WHERE chat.user_id=? OR chat.receiver_id=?
            GROUP BY accounts.user_id
            ) AND accounts.user_id!=?
    ORDER BY chat_id DESC
SQL;
$sql = $pdo -> prepare($select_chat_all);
$sql -> execute([ID, ID, ID]);

// require_once('../header.php');
// require_once('../navbar.php');
?>
<?php
foreach ($sql as $row) {
    echo <<<HTML
        <form action="chat_input.php" method="post">
            <button type="submit">
            <input type="hidden" name="receiver_id" value="$row[user_id]">
            <img src="$row[profile_img]">
            <p>$row[user_name]</p>
            <p>$row[chat_text]</p>
            </button>
        </form>
        <br>
    HTML;
}
?>
