<?php
session_start();
require_once('../function.php');
require_once('../config.php');
index($_SESSION['account']);

// 全変数初期化
$user_name = $user_mail = $profile_img = $uppath = $couse_id = $programming_lans[] = $github_account = $release = $portfolio_name = $portfolio_link = $portfolio_img = '';

// エラー初期化
$profile_image_err = $couse_err = $github_err = '';

if (isset($_POST['success'])) {
    if (is_uploaded_file($_FILES["profile_img"]["tmp_name"])) {
        // 画像用フォルダがなければフォルダを作る
        if (!file_exists('../../profile_images')) {
            mkdir('../../profile_images');
        }

        // ユーザーのプロフィール画像パスを持ってくる
        $s = <<<SQL
                SELECT profile_img
                FROM accounts
                WHERE user_id=?
        SQL;
        $sql = $pdo -> prepare($s);
        $sql -> execute([ID]);

        $delete_file = $sql -> fetch(PDO::FETCH_ASSOC);
        if ($delete_file['profile_img']) {
            unlink($delete_file['profile_img']);
        }

        // アップロードするファイル名をユーザー名＋拡張子にする
        $upfile = NAME.'.'.basename($_FILES['profile_img']['type']);
        // アップロードするパスを指定
        $uppath = '../../profile_images/'.$upfile;

        // アップロードファイル移動、上書き
        if (move_uploaded_file($_FILES["profile_img"]["tmp_name"], $uppath)) {
            chmod($uppath, 0664);
        }
    }

    //画像がアップロードされている場合はアップデート
    if (!empty($uppath)) {
        $s = <<<SQL
                UPDATE accounts
                    SET profile_img=?
                WHERE user_id=?
        SQL;
        $sql = $pdo -> prepare($s);
        $sql -> execute([$uppath, ID]);

    // 画像削除ボタンが押されている場合は削除
    } else if (isset($_POST['img_delete'])) {
        $s = <<<SQL
                UPDATE accounts
                    SET profile_img=""
                WHERE user_id=?
        SQL;
        $sql = $pdo -> prepare($s);
        $sql -> execute([ID]);
    }

    // 専攻選択
    if ($_POST['couse']) {
        $s = <<<SQL
                UPDATE accounts
                    SET couse_id=?
                WHERE user_id=?
        SQL;
        $sql = $pdo -> prepare($s);
        $sql -> execute([$_POST['couse'], ID]);
    } else {
        $couse_err = '専攻を選択してください';
    }

    if (strstr($_POST['github'], 'https://github.com/') || empty($_POST['github'])) {
        $s = <<<SQL
                UPDATE accounts
                    SET github_account=?
                WHERE user_id=?
        SQL;
        $sql = $pdo -> prepare($s);
        $sql -> execute([h($_POST['github']), ID]);
    } else {
        $github_err = 'githubアカウントのリンクを入力してください';
    }

    // 公開するかどうか

    $s = <<<SQL
            UPDATE accounts
                SET release_flg=?
            WHERE user_id=?
    SQL;
    $sql = $pdo -> prepare($s);
    $sql -> execute([$_POST['release'][0], ID]);

    if (empty($image_err) && empty($couse_err) && empty($github_err)) {
        // header('Location: ../home/index.php');
    }
}

$s = <<<SQL
        SELECT *
        FROM accounts
                LEFT OUTER JOIN couses
                    ON accounts.couse_id=couses.couse_id
                LEFT OUTER JOIN programming_lans
                    ON accounts.user_id=programming_lans.user_id
                LEFT OUTER JOIN portfolios
                    ON accounts.user_id=portfolios.user_id
        WHERE accounts.user_id=?
SQL;

$sql = $pdo -> prepare($s);
$sql -> execute([ID]);

foreach ($sql as $row) {
    $user_name = $row['user_name'];
    $user_mail = $row['user_mail'];
    $profile_img = $row['profile_img'];
    $couse_id = $row['couse_id'];
    $programming_lans[] = $row['programming_lan'];
    $github_account = $row['github_account'];
    $release = $row['release_flg'];
    $portfolio_name = $row['portfolio_name'];
    $portfolio_link = $row['portfolio_link'];
    $portfolio_img = $row['portfolio_img'];
}

require_once('../header.php');
?>

<div class="container light-style container-p-y">

    <!-- ヘッダー スタート-->
    <h4 class="font-weight-bold py-3 mb-4">
        アカウント設定
    </h4>
    <!-- ヘッダー 終了-->

    <form action="profile-edit.php" method="post" enctype="multipart/form-data">

    <!-- 左側のアカウント詳細 navigation スタート-->
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-dark">
                <div class="col-md-3 pt-0 row-bordered row-border-dark">
                    <div class="list-group account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">一般</a>

                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-info">About</a>

                        <!--未定機能<a class="list-group-item list-group-item-action" data-toggle="list" href="#account-links">他のアカウントへのリンク</a>-->

                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-project">プロジェクト</a>

                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-password">パスワードを変更</a>

                    </div>
                </div>
    <!-- 左側のアカウント詳細 navigation 終了-->

                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">

    <!-- プロフィール画像セクション スタート 未-->
                            <div class="card-body">
                                <div class="col-12">
                                    <label class="form-label">プロフィール画像</label>
                                    <div class="form-group col-12">
                                        <input type="file" id="profile_img" class="img_upload" name="profile_img" accept=".png, .jpeg, .jpg, .gif">
                                        <input type="checkbox" id="img_delete" name="img_delete" value="1">
                                        <img src="<?= $profile_img ?>" id="tl_img" class="img_preview" style="width: 100px;">
                                        <label for="profile_img" class="btn btn-outline-primary <?= (!empty($profile_img_err)) ? 'is-invalid' : ''; ?>">
                                            アップロード
                                        </label>
                                        <label for="img_delete" class="btn btn-outline-danger" name="delete" style="<?= (empty($profile_img)) ? 'display: none;' : ''; ?>">
                                            削除
                                        </label>
                                        <span class="invalid-feedback"><?= $profile_img_err; ?></span>
                                        <div class="text-light small mt-1">許可されているJPG、GIF、またはPNG。 最大サイズ800K</div>
                                    </div>
                                </div>
    <!-- プロフィール画像セクション 終了 -->

                                <hr class="border-secondary my-3">

    <!-- ユーザー名セクション スタート 完-->
                                <div class="col-12">
                                    <label class="form-label">ユーザー名</label>
                                    <div class="form-control-static col-6">
                                        <?= $user_name ?>
                                    </div>
                                </div>
    <!-- ユーザー名セクション 終了-->

                                <hr class="border-secondary my-3">

    <!-- ユーザーメールセクション 完-->
                                <div class="col-12">
                                    <label class="form-label">メールアドレス</label>
                                    <div class="form-control-static col-6">
                                        <?= $user_mail ?>
                                    </div>
                                </div>
    <!-- ユーザーメールセクション  終了-->

                                <hr class="border-secondary my-3">

    <!--専攻セクション スタート 完-->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">専攻</label>
                                        <div class="col-6">
                                            <select class="custom-select  <?= (!empty($couse_err)) ? 'is-invalid' : ''; ?>" name="couse">
                                                <?= couse($couse_id); ?>
                                            </select>
                                            <span class="invalid-feedback"><?= $couse_err; ?></span>
                                        </div>
                                    </div>
                                </div>
    <!--専攻セクション 終了 -->

                                <hr class="border-secondary my-3">
                            </div>
                        </div>


    <!-- アバウトセクション 開始 未-->
                        <div id="account-info" class="tab-pane fade">
                            <div class="card-body pb-2">

    <!-- 自己紹介セクション 開始 未-->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">自己アピール/自己紹介</label><!--自己アピール/自己紹介どっちにする？-->
                                        <textarea class="form-control" rows="" placeholder = ""></textarea>
                                    </div>
                                </div>
    <!-- 自己紹介セクション 終了-->

                                <hr class="border-secondary my-3">

    <!-- ポートフォリオセクション 開始 未-->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">ポートフォリオ</label>





                                        <!-- <input type="file" id="profile_img" class="img_upload" name="profile_img" accept=".png, .jpeg, .jpg, .gif">
                                        <input type="checkbox" id="img_delete" name="img_delete" value="1">
                                        <img src="<?= $portfolio_img ?>" id="tl_img" class="img_preview" style="width: 100px;">
                                        <div class="<?= (empty($profile_img)) ? 'mt-2' : ''; ?>">
                                            <label for="profile_img" class="btn btn-outline-primary">
                                                アップロード
                                            </label>
                                            <label for="img_delete" class="btn btn-outline-danger" name="delete" style="<?= (empty($profile_img)) ? 'display: none;' : ''; ?>">
                                                削除
                                            </label>
                                            <div class="text-light small mt-1">許可されているJPG、GIF、またはPNG。 最大サイズ800K</div>
                                        </div> -->
                                    </div>
                                </div>
    <!-- ポートフォリオセクション 終了-->

                                <hr class="border-secondary my-3">
                            </div>
                        </div>
    <!-- アバウトセクション 終了 -->

    <!-- ユーザープロジェクトセクション 開始 未-->
                        <div id="account-project" class="tab-pane fade">
                            <div class="card-body">
                                <button type="button" class="btn btn-twitter">Connect to <strong>GitHUB</strong></button>

                                <div class="col-4">
                                    <label class="form-label"><strong>GitHUB</strong>リンク</label>
                                    <input type="text" class="form-control" name="github" value="<?= $github_account ?>">
                                </div>
                                <hr class="border-light my-3">

                                <h5 class="mb-2">
                                    <a href="javascript:void(0)" class="float-right text-muted text-tiny"><i class="ion ion-md-close"></i> Remove</a>
                                    <i class="ion ion-logo-google text-google"></i>
                                    GITHUBに接続しています：
                                </h5>
                                例ーhttps://github.neokun.jp.com.......
                            </div>
                            <hr class="border-dark my-1">
                            <hr class="border-dark my-1">
                        </div>
    <!--ユーザープロジェクトセクション 終了-->

    <!--パスワードセクション スタート 未-->
                        <div id="account-change-password" class="tab-pane fade" >
                            <div class="card-body pb-2">
                                <div class="form-group">
                                    <label class="form-label">Current password</label>
                                    <input type="password" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">New password</label>
                                    <input type="password" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Repeat new password</label>
                                    <input type="password" class="form-control">
                                </div>
                            </div>
                        </div>
    <!--パスワードセクション 終了 -->



            <!-- 未定機能 linked.in / code.pen / google.drive/+ / dropbox プロファイルリンク-->
            <!--    <div class="tab-pane fade" id="account-links">
                        <div class="card-body pb-2">
                            <div class="form-group">
                                <label class="form-label">linked.in</label>
                                <input type="text" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Google/drive/+</label>
                                <input type="text" class="form-control" value="">
                            </div>
                            <div class="form-group">
                                <label class="form-label">LinkedIn</label>
                                <input type="text" class="form-control" value="">
                            </div>

                        </div>
                    </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
            <div class="sample3Area" id="makeImg">
                <input type="checkbox" id="sample3check" name="release[]" value="1" <?= ($release) ? 'checked' : '' ?>>

                <label for="sample3check">
                    <span></span>
                </label>
            </div>
            <input type="hidden" name="release[]" value="0">
            <input type="submit" class="btn btn-primary" name="success" value="確定">
        </div>
    </form>
</div>

<!--CSS-->
<style type="text/css">
body{
    background: #f5f5f5;
    margin-top:20px;
}

.ui-w-80 {
    width: 80px !important;
    height: auto;
}

.btn-default {
    border-color: rgba(24,28,33,0.1);
    background: rgba(0,0,0,0);
    color: #4E5155;
}

label.btn {
    margin-bottom: 0;
}

.btn-outline-primary {
    border-color: #26B4FF;
    background: transparent;
    color: #26B4FF;
}

.btn {
    cursor: pointer;
}

.text-light {
    color: #babbbc !important;
}

.btn-facebook {
    border-color: rgba(0,0,0,0);
    background: #3B5998;
    color: #fff;
}

.btn-instagram {
    border-color: rgba(0,0,0,0);
    background: #000;
    color: #fff;
}

.card {
    background-clip: padding-box;
    box-shadow: 0 1px 4px rgba(24,28,33,0.012);
}

.row-bordered {
    overflow: hidden;
}

.account-settings-fileinput {
    position: absolute;
    visibility: hidden;
    width: 1px;
    height: 1px;
    opacity: 0;
}
.account-settings-links .list-group-item.active {
    font-weight: bold !important;
}
html:not(.dark-style) .account-settings-links .list-group-item.active {
    background: transparent !important;
}
.account-settings-multiselect ~ .select2-container {
    width: 100% !important;
}
.light-style .account-settings-links .list-group-item {
    padding: 0.85rem 1.5rem;
    border-color: rgba(24, 28, 33, 0.03) !important;
}
.light-style .account-settings-links .list-group-item.active {
    color: #4e5155 !important;
}
.material-style .account-settings-links .list-group-item {
    padding: 0.85rem 1.5rem;
    border-color: rgba(24, 28, 33, 0.03) !important;
}
.material-style .account-settings-links .list-group-item.active {
    color: #4e5155 !important;
}
.dark-style .account-settings-links .list-group-item {
    padding: 0.85rem 1.5rem;
    border-color: rgba(255, 255, 255, 0.03) !important;
}
.dark-style .account-settings-links .list-group-item.active {
    color: #fff !important;
}
.light-style .account-settings-links .list-group-item.active {
    color: #4E5155 !important;
}
.light-style .account-settings-links .list-group-item {
    padding: 0.85rem 1.5rem;
    border-color: rgba(24,28,33,0.03) !important;
}
</style>

<?= require_once('../footer.php'); ?>