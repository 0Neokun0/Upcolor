
<?php
session_start();
// unset($_SESSION['account']);
require_once('../config.php');
require_once('../function.php');

$s = <<<SQL
    SELECT user_name
    FROM accounts
    WHERE user_id=?
SQL;
$sql = $pdo -> prepare($s);
$sql -> execute([ID]);
$user_name = '';
foreach ($sql as $row) {
    $user_name = $row['user_name'];
}
require_once('../header.php');
require_once('../navbar.php');
?>

    <!-- END nav -->
<div class="container-up"></div>
<div class="container-fluid-1 text-center">
    <h4 class="h4">Welcome To</h4>
    <h1 class="h1 pt-1">UpColor</h1>
    <div class="d-grid gap-2 col-6 mx-auto pt-3">
        <button type="button" class="btn btn-primary btn-lg" onclick="location.href='../signIn/signIn.php'">ログイン</button>
        <button type="button" class="btn btn-secondary btn-lg" onclick="location.href='../signUp/signUp.php'">新規登録</button>
    </div>
</div>
<?php require_once('../footer.php'); ?>
