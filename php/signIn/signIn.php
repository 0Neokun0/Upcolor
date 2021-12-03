<?php
//Session Start
//セッション開始
session_start();
require_once('../config.php');
require_once('../function.php');

// Inserting variable
// 変数を代入します

$user_mail = $password = '';
$user_mail_err = $password_err = '';

// Process form  when post submit
// 送信後にフォームを処理します

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize POST
    // POSTをきれいにします
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $user_mail = trim($_POST['user_mail']);
    $password = trim($_POST['password']);

    // Validate mail
    // メールを検証

    if (empty($user_mail)) {
        $user_mail_err = 'メールアドレスを入力してください';
    }

    // Validate mail
    // パスワードを検証します

    if (empty($password)) {
        $password_err = 'パスワードを入力してください';
    }

    // Make sure errors are empty
    // エラーが空であることを確認します

    if (empty($user_mail_err) && empty($password_err)) {

        // Prepare query
        // クエリを準備します

        $s = <<<SQL
                SELECT user_name, user_mail, password
                FROM accounts
                WHERE user_mail = :user_mail
        SQL;

        // Prepare statement
        // ステートメントを準備します

        if ($sql = $pdo -> prepare($s)) {

            // Bind params
            // パラメータをバインドします

            $sql -> bindParam(':user_mail', $user_mail, PDO::PARAM_STR);

            // Attempt execute
            // 実行を試みます

            if ($sql -> execute()) {

                // Check if mail exists
                // メールが存在するかどうかを確認します

                if ($sql -> rowCount() === 1) {

                    // ログインするアカウントのローを追加
                    if ($row = $sql -> fetch()) {

                        $hashed_password = $row['password'];

                        // パスワードが一致するか確認

                        if (password_verify($password, $hashed_password)) {

                            // Successful login
                            // ログイン成功

                            $s = <<<SQL
                                    SELECT *
                                    FROM accounts
                                    WHERE user_mail=?
                            SQL;
                            $sql = $pdo -> prepare($s);
                            $sql -> execute([$user_mail]);

                            foreach ($sql as $row) {
                                $_SESSION['account'] = [
                                    'user_id' => $row['user_id'], 'user_name' => $row['user_name'], 'user_mail' => $row['user_mail']
                                ];
                            }

                            header('Location: ../home/index.php');
                        } else {

                            // Display wrong password message
                            // パスワードが間違っている

                            $password_err = 'パスワードが間違っています';
                        }
                    }
                } else {

                    // Mail error
                    // メールがありません

                    $user_mail_err = 'メールのアカウントはありません';
                }
            } else {
                die('ログイン失敗しました');
            }
        }

        // Close statement
        //ステートメントを閉じる

        unset($sql);
    }

    // Close connection
    //接続を閉じます

    unset($pdo);
}

require_once('../header.php');
require_once('../navbar.php');
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>ログイン</h2>
                <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="form-group mt-3">
                        <label for="mail">メール</label>
                        <input type="mail" name="user_mail" class="form-control form-control-lg <?= (!empty($user_mail_err)) ? 'is-invalid' : ''; ?>" value="<?= $user_mail; ?>" autofocus>
                        <span class="invalid-feedback"><?= $user_mail_err; ?></span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="password">パスワード</label>
                        <input type="password" name="password" class="form-control form-control-lg <?= (!empty($password_err)) ? 'is-invalid' : '';?>">
                        <span class="invalid-feedback"><?= $password_err; ?></span>
                    </div>
                    <div class="form-row mt-3">
                        <div class="col">
                            <input type="submit" value="ログイン" class="btn btn-success btn-block">
                        </div>
                        <div class="col">
                            <a href="../signUp/signUp.php" class="btn btn-light btn-block">アカウントをお持ちでない方はこちら 登録</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once('../footer.php'); ?>