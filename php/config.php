<?php
// データベースを繋ぐために使います
define('DSN', 'mysql:host=localhost; dbname=upcolor; charset=utf8');
define('DB_USER', 'staff');
define('DB_PASS', 'password');

try {
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// header関数を書くために使います
define('URL', (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\'));

// ログインしているときにIDにユーザーIDを入れます
define('ID', (isset($_SESSION['account'])) ? $_SESSION['account']['user_id'] : '');
define('NAME', (isset($_SESSION['account'])) ? $_SESSION['account']['user_name'] : '');
define('MAIL', (isset($_SESSION['account'])) ? $_SESSION['account']['user_mail'] : '');