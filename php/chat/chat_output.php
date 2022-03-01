<head>
    <link rel="stylesheet" href="../../includes/css/chat_output.css">
</head>
<?php

session_start();
require_once('../config.php');

$receiver_id = $_POST['receiver_id'];

$select_id = <<<SQL
    SELECT MAX(chat_id)
    FROM chat
    WHERE user_id=? OR receiver_id=?
SQL;
$sql = $pdo -> prepare($select_id);
$sql -> execute([$receiver_id, $receiver_id]);
$max_id = $sql -> fetch(PDO::FETCH_ASSOC);

$inorup = <<<SQL
    SELECT MAX(chat_id)
    FROM last_updates
    WHERE user_id=? AND receiver_id=?
SQL;
$sql = $pdo -> prepare($inorup);
$sql -> execute([ID, $receiver_id]);

if (!($sql -> fetch(PDO::FETCH_ASSOC))) {
    $insert_id = <<<SQL
        INSERT INTO last_updates(user_id, receiver_id, chat_id) values(?, ?, ?)
    SQL;
    $sql = $pdo -> prepare($insert_id);
    $sql -> execute([ID, $receiver_id, $max_id['MAX(chat_id)']]);
} else {
    $update_id = <<<SQL
        UPDATE last_updates SET chat_id=? WHERE user_id=? AND receiver_id=?
    SQL;
    $sql = $pdo -> prepare($update_id);
    $sql -> execute([$max_id['MAX(chat_id)'], ID, $receiver_id]);
}

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
    if ($row['receiver_id'] != ID) {
        echo <<<HTML
            <div class="from-me text-break">
                $row[chat_text]
            </div>
        HTML;
    } else {
        echo <<<HTML
            <div class="from-them text-break">
                $row[chat_text]
            </div>
        HTML;
    }

}
?>
