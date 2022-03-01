<?php
session_start();
require_once('../function.php');
require_once('../config.php');
login($_SESSION['account']);

// 全変数初期化
$user_name = $user_mail = $profile_img = $bg_color = $uppath = $couse_id = $introduction = $github_account = $project_title = $project_num = $qual_name = $project_img = $template_id = $release = '';

// エラー初期化
$profile_image_err = $couse_err = $github_err = $pronum_err = '';

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

    // 自己紹介 about_tab
    $s = <<<SQL
        UPDATE accounts
            SET introduction=?
        WHERE user_id=?
    SQL;
    $sql = $pdo -> prepare($s);
    $sql -> execute([$_POST['introduction'], ID]);

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

    // スキルツール入力
    if (isset($_POST['tool_name'])) {
        $tool = array_diff($_POST['tool_name'], array("",0,null));
        $tool_delete = <<<SQL
            DELETE
            FROM tools
            WHERE user_id=?
        SQL;
        $sql = $pdo -> prepare($tool_delete);
        $sql -> execute([ID]);

        $tool_update = <<<SQL
            INSERT INTO tools values(?, ?)
        SQL;
        foreach ($tool as $row) {
            $sql = $pdo -> prepare($tool_update);
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

    // プロジェクト数
    if (is_numeric($_POST['project_num']) || empty($_POST['project_num'])) {
        $update_num = <<<SQL
            UPDATE accounts
                SET project_num=?
            WHERE user_id=?
        SQL;
        $sql = $pdo -> prepare($update_num);
        $sql -> execute([$_POST['project_num'], ID]);
    } else {
        $pronum_err = '数値で入力してください';
    }

    //プロジェクト名、画像

    // Count total files
    $countfiles = count($_FILES['files']['name']);

    // Prepared statement
    $query = "UPDATE accounts SET project_name=?, project_image=? WHERE user_id=?";

    $statement = $pdo -> prepare($query);

    // Loop all files
    for($i = 0; $i < $countfiles; $i++) {

        // File name
        $filename = $_FILES['files']['name'][$i];

        // Location
        $target_file = '../../project_img/'.$filename;

        // file extension
        $file_extension = pathinfo(
            $target_file, PATHINFO_EXTENSION);

        $file_extension = strtolower($file_extension);

        // Valid image extension
        $valid_extension = array("png","jpeg","jpg");

        if(in_array($file_extension, $valid_extension)) {

            // Upload file
            if(move_uploaded_file(
                $_FILES['files']['tmp_name'][$i],
                $target_file)
            ) {

                // Execute query
                $statement -> execute([$_POST['project_title'], $target_file, ID]);
            }
        }
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

    if (empty($image_err) && empty($couse_err) && empty($github_err) && empty($pronum_err)) {
        header('Location: ./userprofile.php');
    }
}

$s = <<<SQL
    SELECT *
    FROM accounts
            LEFT OUTER JOIN couses
                ON accounts.couse_id=couses.couse_id
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
$introduction = $user_info['introduction'];
$github_account = $user_info['github_account'];
$project_num = $user_info['project_num'];
$project_title = $user_info['project_name'];
$project_img = $user_info['project_image'];
$template_id = $user_info['template_id'];
$release = $user_info['release_flg'];
//programing language 処理開始

$pro_lan_select = <<<SQL
    SELECT *
    FROM programming_lans
    WHERE user_id=?
SQL;
$sql = $pdo -> prepare($pro_lan_select);
$sql -> execute([ID]);
$pro_lans = $sql -> fetchAll(PDO::FETCH_ASSOC);

//programing language 処理終了

//資格処理開始

$qual_select = <<<SQL
    SELECT *
    FROM qual
    WHERE user_id=?
SQL;
$sql = $pdo -> prepare($qual_select);
$sql -> execute([ID]);
$qual = $sql -> fetchAll(PDO::FETCH_ASSOC);

//資格処理終了

//ツールスキル処理開始

$tool_select = <<<SQL
    SELECT *
    FROM tools
    WHERE user_id=?
SQL;
$sql = $pdo -> prepare($tool_select);
$sql -> execute([ID]);
$tool = $sql -> fetchAll(PDO::FETCH_ASSOC);

//ツールスキル処理終了

require_once('../header.php');
require_once('../navbar.php');
?>
<link rel="stylesheet" href="../../includes/css/profile_edit.css">
<div class="container light-style pt-4">

    <!-- ヘッダー スタート-->
    <h2 class="text-center font-weight-bold py-2 mb-1 mt-5">
        アカウント設定
    </h2>
    <!-- ヘッダー 終了-->

    <form action="profile_edit.php" method="post" enctype="multipart/form-data">

    <!-- 左側のアカウント詳細 navigation スタート-->
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-dark">
                <div class="col-md-3 pt-0 row-bordered row-border-dark">
                    <div class="list-group account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general">一般</a>

                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-info">About</a>

                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-project">プロジェクト</a>

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
                                        <label class="form-label"><strong><i class="fa fa-picture-o mr-2" aria-hidden="true"></i>プロフィール画像</strong></label>
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
                                        <label class="form-label"><strong><i class="fa fa-id-badge mr-2" aria-hidden="true"></i>背景色選択</strong></label>
                                        <div class="form-group">
                                            <input type="color" id="exampleColorInput" class="form-control form-control-color" name="bg_color" value="<?= (isset($bg_color)) ? $bg_color : '#FFFFFF' ; ?>" title="背景の色を選んでください" style="width: 50%; height: 15vh;">
                                        </div>
                                    </div>
                                </div>

    <!-- プロフィール画像 終了 -->

                                <hr class="border-secondary my-3">

    <!-- ユーザー名 スタート 完-->
                                <div class="col-12">
                                    <label class="form-label"><strong><i class="fa fa-user mr-2" aria-hidden="true"></i>ユーザー名</strong></label>
                                    <div class="form-control-static col-6">
                                        <?= $user_name ?>
                                    </div>
                                </div>
    <!-- ユーザー名 終了-->

                                <hr class="border-secondary my-3">

    <!-- ユーザーメール 完-->
                                <div class="col-12">
                                    <label class="form-label"><strong><i class="fa fa-address-book mr-2" aria-hidden="true"></i>メールアドレス</strong></label>
                                    <div class="form-control-static col-6">
                                        <?= $user_mail ?>
                                    </div>
                                </div>
    <!-- ユーザーメール  終了-->

                                <hr class="border-secondary my-3">

    <!--専攻 スタート 完-->
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label"><strong><i class="fa fa-graduation-cap mr-2" aria-hidden="true"></i>専攻</strong></label>
                                        <div class="col-6">
                                            <select class="custom-select <?= (!empty($couse_err)) ? 'is-invalid' : ''; ?>" name="couse">
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
                                        <label class="form-label"><strong><i class="fa fa-bullhorn mr-2" aria-hidden="true"></i>自己アピール/自己紹介</strong></label><!--自己アピール/自己紹介どっちにする？-->
                                        <textarea class="form-control" name="introduction" rows="5" placeholder = "私は本科生で、ｃ言語の勉強しています。先輩方から助けてもらってする事で感動しています。先輩方と同じく頼りになる人になりたいです。"><?= $introduction ?></textarea>
                                    </div>
                                </div>
    <!-- 自己紹介 終了-->

                                <hr class="border-secondary my-3">

    <!-- プログラミング言語 開始 未 -->
                                <div class="col-12">
                                    <div id="lans-area" class="form-group">
                                        <label class="form-label"><strong><i class="fa fa-code mr-2"></i>プログラミング言語</strong></label>
                                        <?php
                                        if (empty($pro_lans)) {
                                            echo <<<HTML
                                                <div class="unit input-group mb-2">
                                                    <input type="text" class="form-control" name="programming_lans[]" placeholder="例）ｃ言語/HTML/PHP" aria-describedby="button-addon2">
                                                    <button type="button" id="button-addon2" class="lans-minus btn btn-outline-danger">ー</button>
                                                </div>
                                            HTML;
                                        } else {
                                            foreach ($pro_lans as $row) {
                                                echo <<<HTML
                                                    <div class="unit input-group mb-2">
                                                        <input type="text" class="form-control" name="programming_lans[]" placeholder="例）ｃ言語/HTML/PHP" aria-describedby="button-addon2" value="$row[programming_lan]">
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
                                        <label class="form-label"><strong><i class="fa fa-certificate mr-2" aria-hidden="true"></i>資格</strong></label>
                                        <?php
                                        if (empty($qual)) {
                                            echo <<<HTML
                                                <div class="unit2 input-group mb-2">
                                                    <input type="text" class="form-control" name="qual_name[]" placeholder="例）i.t.パスワード/TOEIC/情報検定 " aria-describedby="button-addon1">
                                                    <button type="button" id="button-addon1" class="qual-minus btn btn-outline-danger">ー</button>
                                                </div>
                                            HTML;
                                        } else {
                                            foreach ($qual as $row) {
                                            echo <<<HTML
                                                <div class="unit2 input-group mb-2">
                                                    <input type="text" class="form-control" name="qual_name[]" placeholder="例）i.t.パスワード/TOEIC/情報検定" aria-describedby="button-addon1" value="$row[qual_name]">
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

                                <hr class="border-secondary my-3">

    <!-- ツールスキル 開始 -->
                                <div class="col-12">
                                    <div id="tool-area" class="form-group">
                                        <label class="form-label"><strong>ツールスキル</strong></label>
                                        <?php
                                        if (empty($tool)) {
                                            echo <<<HTML
                                                <div class="unit3 input-group mb-2">
                                                    <input type="text" class="form-control" name="tool_name[]" placeholder="例）GIT/Bootstrap/NPM" aria-describedby="button-addon0">
                                                    <button type="button" id="button-addon0" class="tool-minus btn btn-outline-danger">ー</button>
                                                </div>
                                            HTML;
                                        } else {
                                            foreach ($tool as $row) {
                                            echo <<<HTML
                                                <div class="unit3 input-group mb-2">
                                                    <input type="text" class="form-control" name="tool_name[]" placeholder="例）GIT/Bootstrap/NPM" aria-describedby="button-addon0" value="$row[tool_name]">
                                                    <button type="button" id="button-addon0" class="tool-minus btn btn-outline-danger">ー</button>
                                                </div>
                                            HTML;
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="button" id="tool-plus" class="btn btn-outline-primary btn-lg">＋ <small>※5個まで</small></button>
                                    </div>
                                </div>
    <!-- ツールスキル 開始 -->

                                <hr class="border-secondary my-3">

                            </div>
                        </div>

    <!-- アバウト 終了 -->

    <!-- ユーザープロジェクト 開始 未-->

                        <div id="account-project" class="tab-pane fade">
                            <div class="card-body">
                                <div class="col-4">
                                    <label class="form-label"><strong><i class="fa fa-link mr-2" aria-hidden="true"></i>GitHUB</strong>リンク</label>
                                    <input type="text" class="form-control <?= (!empty($github_err)) ? 'is-invalid' : ''; ?>" name="github" value="<?= $github_account ?>">
                                    <span class="invalid-feedback"><?= $pronum_err; ?></span>
                                </div>

                                <hr class="border-secondary my-3">
                                <div class="col-4">
                                    <label class = "form-label"><strong><i class="fa fa-sort-numeric-asc mr-2" aria-hidden="true"></i>プロジェクト数</strong></label>
                                    <input type = "text" class= "form-control <?= (!empty($pronum_err)) ? 'is-invalid' : ''; ?>" name= "project_num" value="<?= $project_num ?>">
                                    <span class="invalid-feedback"><?= $pronum_err; ?></span>
                                </div>
                                <hr class="border-secondary my-3">
                        <!--プロジェット処理のファイル-project_upload.js-->
                                <script language="JavaScript" src="../../includes/js/project_upload.js"></script>
                        <!--project_upload.js ファイル -->
                                <div class="col-5">
                                    <div class="center mb-5">
                                        <label class="form-label"><strong><i class="fa fa-asterisk mr-2" aria-hidden="true"></i>プロジェクト</strong>タイトル</label>
                                        <input type="text" class="form-control form-control-sm mb-5" name="project_title" placeholder="タイトル" value="<?= $project_title ?>">
                                        <div class="form-input">
                                            <label for="file-ip-1">
                                            <img id="file-ip-1-preview" name="files[]" src="../../includes/images/upcolor.png">
                                            <button type="button" class="imgRemove" onclick="myImgRemove(1)"></button>
                                            </label>

                                            <input type="file" name="files[]" id="file-ip-1" accept="image/*" onchange="showPreview(event, 1);">
                                        </div>
                                        <small class="small">&#8634;を使用して、 画像をリセットするアイコン</small>
                                    </div>
                                </div>

                            <hr class="border-secondary my-3">

    <!--ユーザープロジェクト 終了-->
                            </div>
                        </div>

    <!-- テンプレート 開始 未-->
                        <div id="account-change-template" class="tab-pane fade">
                            <div class="card-body">
                                <div class="row">
                                    <?= template($template_id); ?>
                                </div>
                            </div>
                        </div>
    <!-- テンプレート 終了 -->

                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
            <input type="checkbox" name="release[]" value="1" data-width="100" data-height="48" data-toggle="toggle" data-on="公開" data-off="非公開" data-onstyle="success" data-offstyle="danger" <?= ($release) ? 'checked' : '' ?>>
            <input type="hidden" name="release[]" value="0">
            <input type="submit" class="btn btn-primary btn-lg" name="success" value="確定" style="width: 100px;" onclick="window.onbeforeunload = null;">
        </div>
    </form>
</div>


<?php require_once('../footer.php'); ?>

<script>
    window.onbeforeunload = function(e) {
        e.returnValue = "ページを離れようとしています。よろしいですか？";
    }
</script>