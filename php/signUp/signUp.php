<?php
// セッション開始
session_start();
require_once('../config.php');
require_once('../function.php');

// Inserting variable
//変数を挿入します
$user_name = $user_mail = $password = $confirm_password = '';
$user_name_err = $user_mail_err = $password_err = $confirm_password_err = '';

// Process form when post submit
//送信後にフォームを処理します

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize POST
    // POSTを綺麗にします

    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $user_name = trim($_POST['user_name']);
    $user_mail = trim($_POST['user_mail']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate user_name
    // ユーザー名を検証

    if(isset($user_name)){

        if (preg_match('/^[a-zA-Z]{2,}[0-9]*$/', $user_name)) {

            $s = <<<SQL
                SELECT user_id
                FROM accounts
                WHERE user_name=:user_name
            SQL;

            if ($sql = $pdo -> prepare($s)) {

                // Bind variables
                //変数をバインドします

                $sql -> bindParam(':user_name', $user_name, PDO::PARAM_STR);

                // Attempt to execute
                //実行を試みます

                if ($sql -> execute()) {

                    // Check if user_name exists
                    // ユーザー名が存在するかどうかを確認します

                    if ($sql -> rowCount() === 1) {

                        $user_mail_err = 'そのユーザー名はすでに使用されています';
                    }
                } else {

                    die('確認を失敗しました');
                }
            }
            unset($sql);
        } else {
            $user_name_err = 'ユーザー名は英数字で入力してください 例)rei1234';
        }
    } else {
        $user_name_err = 'ユーザー名を入力してください';
    }

    // Validate mail
    // メールを検証

    if (isset($user_mail)) {

        if (strpos('/^[0-9]{7}$/', $user_mail)) {
            var_dump($user_mail);
        }

        // Prepare a select statement
        // selectステートメントを準備します

        $s = <<<SQL
            SELECT user_id
            FROM accounts
            WHERE user_mail=:user_mail
        SQL;

        if ($sql = $pdo -> prepare($s)) {

            // Bind variables
            //変数をバインドします

            $sql -> bindParam(':user_mail', $user_mail, PDO::PARAM_STR);

            // Attempt to execute
            //実行を試みます

            if ($sql -> execute()) {

                // Check if mail exists
                //メールが存在するかどうかを確認します

                if ($sql -> rowCount() === 1) {

                    $user_mail_err = 'そのメールアドレスはすでに使用されています';
                }
            } else {

                die('確認を失敗しました');
            }
        }
        unset($sql);
    } else {
        $user_mail_err = 'メールアドレスを入力してください';
    }

    // Validate password
    // パスワードを検証します

    if (empty($password)) {
        $password_err = 'パスワードを入力してください';
    } else if (strlen($password) < 6) {
        $password_err = 'パスワードは最低でも6文字必要です';
    }

    // Validate Confirm password
    // パスワードの確認を検証します

    if (empty($confirm_password)) {
        $confirm_password_err = 'パスワードを再入力してください';
    } else if ($password !== $confirm_password) {
        $confirm_password_err = 'パスワードが一致していません';
    }

    // Make sure errors are empty
    // エラーが空であることを確認します

    if (empty($user_name_err) && empty($user_mail_err) && empty($password_err) && empty($confirm_password_err)) {
        // Hash password
        // パスワードのハッシュ化

        $password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare insert query
        // インサートクエリを準備します

        $s = <<<SQL
            INSERT
            INTO accounts (user_name, user_mail, password)
            VALUES (:user_name, :user_mail, :password)
        SQL;

        if ($sql = $pdo -> prepare($s)) {

            // Bind parameters
            // パラメータをバインドします

            $sql -> bindParam(':user_name', $user_name, PDO::PARAM_STR);
            $sql -> bindParam(':user_mail', $user_mail, PDO::PARAM_STR);
            $sql -> bindParam(':password', $password, PDO::PARAM_STR);

            // Attempt to execute
            // 実行を試みます

            if ($sql -> execute()) {

                // Redirect to login
                // ログインにリダイレクト

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

                header('Location: ../welcome_to_upcolor/');

            } else {
                die('実行失敗');
            }
        }
        unset($sql);
    }

    // Cloase connection
    // 接続終了
    unset($pdo);
}

require_once('../header.php');
require_once('../navbar.php');
?>

<div class="container">
    <div class="row mt-5">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>アカウントを作成</h2>
                <p>このフォームに記入して登録してください</p>
                <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="form-group mt-3">
                        <label for="name"><i class="fa fa-user-plus mr-2" aria-hidden="true"></i>ユーザー名</label>
                        <input type="text" name="user_name" class="form-control form-control-lg <?= (!empty($user_name_err)) ? 'is-invalid' : ''; ?>" value="<?= $user_name ?>" autofocus>
                        <span class="invalid-feedback"><?= $user_name_err; ?></span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="mail"><i class="fa fa-envelope mr-2" aria-hidden="true"></i>メール</label>
                        <input type="mail" name="user_mail" class="form-control form-control-lg <?= (!empty($user_mail_err)) ? 'is-invalid' : ''; ?>" value="<?= $user_mail; ?>" placeholder="2100002@i-seifu.jp">
                        <span class="invalid-feedback"><?= $user_mail_err; ?></span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="password"><i class="fa fa-unlock-alt mr-2" aria-hidden="true"></i>パスワード</label>
                        <input type="password" name="password" class="form-control form-control-lg <?= (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?= $password_err; ?></span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="confirm_password"><i class="fa fa-unlock mr-2" aria-hidden="true"></i>パスワードを認証</label>
                        <input type="password" name="confirm_password" class="form-control form-control-lg <?= (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?= $confirm_password_err; ?></span>
                    </div>
                    <div class="form-row mt-3">
                        <div class="col">
                            <input type="submit" value="登録" class="btn btn-success btn-block">
                        </div>
                        <div class="col">
                            <a href="../signIn/signIn.php" class="btn btn-light btn-block">既にアカウントを持っている方はこちら</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once('../footer.php'); ?>