<?php
session_start();
require_once('../config.php');
date_default_timezone_set('japan/tokyo');

$request = '';
if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    $request = strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
}
if ($request !== 'xmlhttprequest') {
    exit;
}

$chat_text = '';
if (isset($_POST['receiver_id']) && isset($_POST['chat_text'])) {
    $receiver_id = $_POST['receiver_id'];
    $chat_text = htmlspecialchars($_POST['chat_text']);
}

if ($chat_text == '') {
    exit;
}

if ($_POST['receiver_id'] == ID) {
    exit;
}

$s = <<<SQL
    INSERT INTO chat(user_id, receiver_id, chat_text) values(?, ?, ?)
SQL;
$sql = $pdo -> prepare($s);
$sql -> execute([ID, $receiver_id, $chat_text]);

$id_select = <<<SQL
    SELECT MAX(chat_id)
    FROM chat
    WHERE user_id=? AND receiver_id=?
SQL;
$sql -> $pdo -> preare($id_select);
$sql -> execute([ID, $receiver_id]);
$max_id = $sql -> fetch(PDO::FETCH_ASSOC);

$_SESSION['test'] = [
    'id' => $max_id['chat_id']
];

$update_select = <<<SQL
    SELECT chat_id
    FROM last_updates
    WHERE user_id=? AND receiver_id=?
SQL;
$sql = $pdo -> prepare($update_select);
$sql -> execute([ID, $receiver_id]);

if (!($sql -> fetch(PDO::FETCH_ASSOC))) {
    $insert_id = <<<SQL
        INSERT INTO last_updates(user_id, receiver_id, chat_id) values(?, ?, ?)
    SQL;
    $sql = $pdo -> prepare($insert_id);
    $sql -> execute([ID, $receiver_id, $max_id['chat_id']]);
} else {
    $update_id = <<<SQL
        UPDATE last_updates SET chat_id=? WHERE user_id=? AND receiver_id=?
    SQL;
    $sql = $pdo -> prepare($update_id);
    $sql -> execute([$max_id['chat_id'], ID, $receiver_id]);
}