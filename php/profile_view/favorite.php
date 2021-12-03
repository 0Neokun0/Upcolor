<?php
session_start();
require_once('../config.php');
require_once('../function.php');

$request = '';
if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    $request = strtolower($_SERVER['HTTP_X_REQUESTED_WITH']);
}
if ($request !== 'xmlhttprequest') {
    exit;
}

$favorited_id = '';
if (isset($_POST['favorited_id']) && isset($_POST['InDel'])) {
    $favorited_id = $_POST['favorited_id'];
    $flg = $_POST['InDel'];
}

if ($flg === 'true') {
    $s = <<<SQL
        INSERT INTO favorite values(?, ?);
    SQL;
} else {
    $s = <<<SQL
        DELETE FROM favorite WHERE user_id=? AND favorited_id=?
    SQL;
}
$sql = $pdo -> prepare($s);
$sql -> execute([ID, $favorited_id]);
?>