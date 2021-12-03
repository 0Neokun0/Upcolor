<?php
session_start();
require_once('../function.php');
require_once('../config.php');
login($_SESSION['account']);

// 全変数初期化
$user_name = $user_mail = $profile_img = $bg_color = $uppath = $couse_id = $introduction = $github_account = $project_name = $project_num = $qual_name = $project_link = $project_img = $template_id = $release = '';

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

    // 背景色選択
    if (isset($_POST['bg_color'])) {
        $s = <<<SQL
            UPDATE accounts
                SET bg_color=?
            WHERE user_id=?
        SQL;
        $sql = $pdo -> prepare($s);
        $sql -> execute([$_POST['bg_color'], ID]);
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
    // 自己紹介
    if ($_POST['introduction']) {
        $s = <<<SQL
                INSERT accounts
                    SET introduction=?
                WHERE user_id=?
        SQL;
        $sql = $pdo -> prepare($s);
        $sql -> execute([$_POST['introduction'], ID]);
    }

    // プログラミング言語入力
    if (isset($_POST['programming_lans'])) {
        $lans = array_diff($_POST['programming_lans'], array("",0,null));
        $s = <<<SQL
            DELETE
            FROM programming_lans
            WHERE user_id=?
        SQL;
        $sql = $pdo -> prepare($s);
        $sql -> execute([ID]);

        $s = <<<SQL
            INSERT INTO programming_lans values(?, ?)
        SQL;
        foreach ($lans as $row) {
            $sql = $pdo -> prepare($s);
            $sql -> execute([ID, htmlspecialchars($row)]);
        }
    }

    // 資格入力
    if (isset($_POST['qual_name'])) {
        $qual = array_diff($_POST['qual_name'], array("",0,null));
        $qual_delete = <<<SQL
            DELETE
            FROM qual
            WHERE user_id=?
        SQL;
        $sql = $pdo -> prepare($qual_delete);
        $sql -> execute([ID]);

        $qual_update = <<<SQL
            INSERT INTO qual values(?, ?)
        SQL;
        foreach ($qual as $row) {
            $sql = $pdo -> prepare($qual_update);
            $sql -> execute([ID, htmlspecialchars($row)]);
        }

    }

    // githubアカウントリンク
    if (strpos($_POST['github'],'://github.com/') !== false || empty($_POST['github'])) {
        $s = <<<SQL
                UPDATE accounts
                    SET github_account=?
                WHERE user_id=?
        SQL;
        $sql = $pdo -> prepare($s);
        $sql -> execute([htmlspecialchars($_POST['github']), ID]);
    } else {
        $github_err = 'githubアカウントのリンクを入力してください';
    }

    // template_id
    if (isset($_POST['template'])) {
        $s = <<< SQL
            UPDATE accounts
                SET template_id=?
            WHERE user_id=?
        SQL;
        $sql = $pdo -> prepare($s);
        $sql -> execute([$_POST['template'], ID]);
    }


    // 公開するかどうか
    $s = <<<SQL
        UPDATE accounts
            SET updated_at=CURRENT_TIMESTAMP, release_flg=?
        WHERE user_id=?
    SQL;
    $sql = $pdo -> prepare($s);
    $sql -> execute([$_POST['release'][0], ID]);

    if (empty($image_err) && empty($couse_err) && empty($github_err)) {
        // header('Location: ./userprofile.php');
    }
}

$s = <<<SQL
    SELECT *
    FROM accounts
            LEFT OUTER JOIN couses
                ON accounts.couse_id=couses.couse_id
            LEFT OUTER JOIN programming_lans
                ON accounts.user_id=programming_lans.user_id
    WHERE accounts.user_id=?
SQL;
$sql = $pdo -> prepare($s);
$sql -> execute([ID]);

$user_info = $sql -> fetch(PDO::FETCH_ASSOC);

$user_name = $user_info['user_name'];
$user_mail = $user_info['user_mail'];
$profile_img = $user_info['profile_img'];
$bg_color = $user_info['bg_color'];
$couse_id = $user_info['couse_id'];
$github_account = $user_info['github_account'];
$template_id = $user_info['template_id'];
$release = $user_info['release_flg'];
$introduction = $user_info['introduction'];

$pro_lan_select = <<<SQL
    SELECT *
    FROM programming_lans
    WHERE user_id=?
SQL;
$sql = $pdo -> prepare($pro_lan_select);
$sql -> execute([ID]);
$programming_lans = $sql -> fetchAll(PDO::FETCH_ASSOC);

$qual_select = <<<SQL
    SELECT *
    FROM qual
    WHERE user_id=?
SQL;
$sql = $pdo -> prepare($qual_select);
$sql -> execute([ID]);
$qual = $sql -> fetchAll(PDO::FETCH_ASSOC);

require_once('../header.php');
require_once('../navbar.php');
?>

<div class="container light-style pt-4">

    <!-- ヘッダー スタート-->
    <h2 class="text-center font-weight-bold py-2 mb-1">
        アカウント設定
    </h2>
    <!-- ヘッダー 終了-->

    <form action="userprofile.php" method="post" enctype="multipart/form-data">

    <!-- 左側のアカウント詳細 navigation スタート-->
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-dark">
                <div class="col-md-3 pt-0 row-bordered row-border-dark">
                    <div class="list-group account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">一般</a>

                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-info">About</a>

                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-project">プロジェクト</a>

                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-password">パスワードを変更</a>

                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-change-template">テンプレート</a>
                    </div>
                </div>
    <!-- 左側のアカウント詳細 navigation 終了-->

                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">

    <!-- プロフィール画像 スタート 未-->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">プロフィール画像</label>
                                            <div class="form-group">
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
                                    <div class="col-md-6">
                                        <label class="form-label">背景色選択</label>
                                        <div class="form-group">
                                            <input type="color" id="exampleColorInput" class="form-control form-control-color" name="bg_color" value="<?= (isset($bg_color)) ? $bg_color : '#FFFFFF' ; ?>" title="背景の色を選んでください" style="width: 50%; height: 15vh;">
                                        </div>
                                    </div>
                                </div>

    <!-- プロフィール画像 終了 -->



                                <hr class="border-secondary my-3">

    <!-- ユーザー名 スタート 完-->
                                <div class="col-12">
                                    <label class="form-label">ユーザー名</label>
                                    <div class="form-control-static col-6">
                                        <?= $user_name ?>
                                    </div>
                                </div>
    <!-- ユーザー名 終了-->

                                <hr class="border-secondary my-3">

    <!-- ユーザーメール 完-->
                                <div class="col-12">
                                    <label class="form-label">メールアドレス</label>
                                    <div class="form-control-static col-6">
                                        <?= $user_mail ?>
                                    </div>
                                </div>
    <!-- ユーザーメール  終了-->

                                <hr class="border-secondary my-3">

    <!--専攻 スタート 完-->
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
    <!--専攻 終了 -->

                                <hr class="border-secondary my-3">
                            </div>
                        </div>


    <!-- アバウト 開始 未-->
                        <div id="account-info" class="tab-pane fade">
                            <div class="card-body pb-2">

    <!-- 自己紹介 開始 未-->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">自己アピール/自己紹介</label><!--自己アピール/自己紹介どっちにする？-->
                                        <textarea class="form-control" name="introduction" rows="" placeholder = "私は本科生で、ｃ言語の勉強しています。先輩方から助けてもらってする事で感動しています。先輩方と同じく頼りになる人になりたいです。"></textarea>
                                    </div>
                                </div>
    <!-- 自己紹介 終了-->

                                <hr class="border-secondary my-3">

    <!-- プログラミング言語 開始 未 -->
                                <div class="col-12">
                                    <div id="lans-area" class="form-group">
                                        <label class="form-label">プログラミング言語</label>
                                        <?php
                                        if (empty($programming_lans)) {
                                            echo <<<HTML
                                                <div class="unit input-group mb-2">
                                                    <input type="text" class="form-control" name="programming_lans[]" placeholder="programming_language" aria-describedby="button-addon2">
                                                    <button type="button" id="button-addon2" class="lans-minus btn btn-outline-danger">ー</button>
                                                </div>
                                            HTML;
                                        } else {
                                            foreach ($programming_lans as $row) {
                                                echo <<<HTML
                                                    <div class="unit input-group mb-2">
                                                        <input type="text" class="form-control" name="programming_lans[]" placeholder="programming_language" aria-describedby="button-addon2" value="$row[programming_lan]">
                                                        <button type="button" id="button-addon2" class="lans-minus btn btn-outline-danger">ー</button>
                                                    </div>
                                                HTML;
                                            }
                                        }
                                        ?>

                                    </div>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="button" id="lans-plus" class="btn btn-outline-primary btn-lg">＋ <small>※10個まで</small></button>
                                    </div>
                                </div>
    <!-- プログラミング言語 終了 -->

                                <hr class="border-secondary my-3">

    <!-- 資格 開始 -->
                                <div class="col-12">
                                    <div id="qual-area" class="form-group">
                                        <label class="form-label">資格</label>
                                        <?php
                                        if (empty($qual)) {
                                            echo <<<HTML
                                                <div class="unit2 input-group mb-2">
                                                    <input type="text" class="form-control" name="qual_name[]" placeholder="例）i.tパスポート" aria-describedby="button-addon1">
                                                    <button type="button" id="button-addon1" class="qual-minus btn btn-outline-danger">ー</button>
                                                </div>
                                            HTML;
                                        } else {
                                            foreach ($qual as $row) {
                                            echo <<<HTML
                                                <div class="unit2 input-group mb-2">
                                                    <input type="text" class="form-control" name="qual_name[]" placeholder="例）i.tパスポート" aria-describedby="button-addon1" value="$row[qual_name]">
                                                    <button type="button" id="button-addon1" class="qual-minus btn btn-outline-danger">ー</button>
                                                </div>
                                            HTML;
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="button" id="qual-plus" class="btn btn-outline-primary btn-lg">＋ <small>※5個まで</small></button>
                                    </div>
                                </div>
                            </div>
                        </div>
    <!-- アバウト 終了 -->

    <!-- ユーザープロジェクト 開始 未-->

                        <div id="account-project" class="tab-pane fade">
                            <div class="card-body">
                                <div class="col-4">
                                    <label class="form-label"><strong>GitHUB</strong>リンク</label>
                                    <input type="text" class="form-control" name="github" value="<?= $github_account ?>">
                                </div>
                                <label class="form-label">ユーザー名</label>
                                <hr class="border-secondary my-3">
                                <hr class="border-secondary my-3">
                                <script language="JavaScript" src="../../includes/js/project_upload.js"></script>
                                <div class="col-5">
                                <div class="center mb-5">
                                        <label class="form-label"><strong>プロジェット</strong>タイトル</label>
                                    <input class="form-control form-control-sm mb-5" type="text" placeholder="タイトル">
                                    <div class="form-input">
                                        <label for="file-ip-1">


                                        <img id="file-ip-1-preview" src="../../includes/images/upcolor.png">
                                        <button type="button" class="imgRemove" onclick="myImgRemove(1)"></button>
                                        </label>

                                        <input type="file"  name="img_one" id="file-ip-1" accept="image/*" onchange="showPreview(event, 1);">
                                    </div>
                                    <small class="small">&#8634;を使用して、 画像をリセットするアイコン</small>
                                    </div>
                                </div>



                                <!--<form method="post" id="uploadForm" enctype="multipart/form-data">
                                    <input type="file" id="user_profile" name="user_profile" />
                                    <input type="button" class="button" value="Upload File" id="btn_upload">
                                </form>
                                <div class="upload_image"></div>-->


    <!-- ポートフォリオ 開始 未-->
                                <!-- <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">ポートフォリオ</label>



                                        <input type="file" id="profile_img" class="img_upload" name="profile_img" accept=".png, .jpeg, .jpg, .gif">
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
                                        </div>
                                    </div>
                                </div> -->
    <!-- ポートフォリオ 終了-->

                            <hr class="border-secondary my-3">

    <!--ユーザープロジェクト 終了-->
                            </div>
                        </div>



    <!--パスワード スタート 未-->
                        <div id="account-change-password" class="tab-pane fade">
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
    <!--パスワード 終了 -->


    <!-- テンプレート 開始 未-->
                        <div id="account-change-template" class="tab-pane fade">
                            <div class="card-body">
                                <div class="row">
                                    <?= template($template_id); ?>
                                </div>
                            </div>
                        </div>
    <!-- テンプレート 終了 -->


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
            <input type="checkbox" name="release[]" value="1" data-width="100" data-height="48" data-toggle="toggle" data-on="公開" data-off="非公開" data-onstyle="success" data-offstyle="danger" <?= ($release) ? 'checked' : '' ?>>
            <input type="hidden" name="release[]" value="0">
            <input type="submit" class="btn btn-primary btn-lg" name="success" value="確定" style="width: 100px;">
        </div>
    </form>
</div>

<!--CSS-->
<style type="text/css">
body{
    background: #f5f5f5;
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

.card {
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 15px 1px rgba(52,40,104,.08);
}
.card {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid #e5e9f2;
    border-radius: .2rem;
}
.card-header:first-child {
    border-radius: calc(.2rem - 1px) calc(.2rem - 1px) 0 0;
}
.card-header {
    border-bottom-width: 1px;
}
.card-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    color: inherit;
    background-color: #fff;
    border-bottom: 1px solid #e5e9f2;
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

<?php require_once('../footer.php'); ?>