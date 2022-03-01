<?php
session_start();
require_once('../config.php');
require_once('../function.php');
login($_SESSION['account']);
index($_GET['name']);

$viewing_id = $profile_img = $user_mail = $bg_color = $couse_name =  $tool_name = $github_link = $github_name = $portfolio_name = $portfolio_link = $portfolio_img = $favorited_id = '';

// データベースからログインしている人の情報を取ってきます
$s = <<<SQL
    SELECT *
    FROM accounts
            LEFT OUTER JOIN couses
                ON accounts.couse_id=couses.couse_id
            LEFT OUTER JOIN programming_lans
                ON accounts.user_id=programming_lans.user_id
            LEFT OUTER JOIN tools
                ON accounts.user_id=tools.user_id
            LEFT OUTER JOIN favorite
                ON accounts.user_id=favorite.favorited_id
    WHERE accounts.user_name=?
SQL;
$sql = $pdo -> prepare($s);
$sql -> execute([$_GET['name']]);

// 取ってきた情報の代入
// foreach ($sql as $row) {
//     // var_dump($row);
//     // var_export($row[0]);
//     $viewing_id = $row[0];
//     $profile_img = $row['profile_img'];
//     $user_mail = $row['user_mail'];
//     $bg_color = $row['bg_color'];
//     $couse_name = $row['couse_name'];
//     $pro_lans[] = $row['programming_lan'];
//     $github_link = $row['github_account'];
//     $github_name = basename($github_link);
//     $favorited_id = $row['favorited_id'];
// }

$user_info = $sql -> fetch();

$user_id = $user_info[0];
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
$favorited_id = $user_info['favorited_id'];
$release = $user_info['release_flg'];
$tx_color = get_text_color($bg_color);
//programing language 処理開始

$pro_lan_select = <<<SQL
    SELECT *
    FROM programming_lans
    WHERE user_id=?
SQL;
$sql = $pdo -> prepare($pro_lan_select);
$sql -> execute([$user_id]);
$pro_lans = $sql -> fetchAll(PDO::FETCH_ASSOC);

//programing language 処理終了

//資格処理開始

$qual_select = <<<SQL
    SELECT *
    FROM qual
    WHERE user_id=?
SQL;
$sql = $pdo -> prepare($qual_select);
$sql -> execute([$user_id]);
$qual = $sql -> fetchAll(PDO::FETCH_ASSOC);

//資格処理終了

//ツールスキル処理開始

$tool_select = <<<SQL
    SELECT *
    FROM tools
    WHERE user_id=?
SQL;
$sql = $pdo -> prepare($tool_select);
$sql -> execute([$user_id]);
$tools = $sql -> fetchAll(PDO::FETCH_ASSOC);

//ツールスキル処理終了

// ここからHTML
require_once('../header.php');
require_once('../navbar.php');
?>

<!-- プロフィール表示欄 -->
<link rel="stylesheet" href="../../includes/css/profile_view.css">
<div class="container pt-5">
    <div class="row clickable-row mt-3" >
        <div class="col-lg-4">
            <div class="profile-card-4 z-depth-3">
                <div class="card">
                    <div class="card-body text-center rounded-top" style="color: <?= $tx_color ?>; background: <?= $bg_color ?>;">
                        <div class="user-box">
                            <img src="<?= $profile_img ?>" alt="user avatar">
                        </div>
                        <h5 class="mb-1"><?= $_GET['name'] ?></h5>
                        <h6><?= $couse_name ?></h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group shadow-none">
                            <li class="list-group-item">
                                <div class="list-icon">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <div class="list-details">
                                    <span><?= $user_mail ?></span>
                                    <small>メールアドレス</small>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <label for="favorite" id="send_fov">
                                    <div class="list-icon">
                                        <input type="checkbox" id="favorite" <?= ($favorited_id) ? 'checked' : '' ?>>
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <div class="list-details">
                                        <span>お気に入りに登録します</span>
                                    </div>
                                </label>
                            </li>
                        </ul>
                        <div class="row text-center mt-4">
                            <div class="col p-2">
                                <h4 class="mb-1 line-height-5">5</h4>
                                <small class="mb-0 font-weight-bold">プロジェクト</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                    <div class="card-footer text-center">
                        <a href="<?= $github_account ?>" class="btn-social btn-github waves-effect waves-light m-1" target="_blank"><i class="fa fa-github"></i></a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card z-depth-3">
                <div class="card-body">
                    <ul class="nav nav-pills nav-pills-primary nav-justified">
                        <li class="nav-item">
                            <a href="javascript:void();" data-target="#profile" data-toggle="pill" class="nav-link active show"><i class="fa fa-user"></i> <span class="hidden-xs">プロフィール</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void();" data-target="#messages" data-toggle="pill" class="nav-link"><i class="fa fa-comments-o"></i> <span class="hidden-xs">メッセージ</span></a>
                        </li>
                        <!--<li class="nav-item1">
                            <a href="javascript:void();" data-target="#edit" data-toggle="pill" class="nav-link"><i class="icon-note"></i> <span class="hidden-xs">プロフィール編集</span></a>
                        </li>-->
                    </ul>
                    <div class="tab-content p-3">
                        <div class="tab-pane active show" id="profile">
                            <h5 class="mb-3"><strong>ユーザープロフィール</strong></h5>
                            <div class="row">
                                <div class="list-group-item col-md-6">
                                    <h6><strong>About</strong><i class="fa fa-bullhorn ml-2" aria-hidden="true"></i></h6>
                                    <p style="font-family:'Yu Mincho', serif;font-weight: 700;">
                                    <?= $introduction ?> <!--自己紹介変数 -->
                                    </p>
                                    <!--<h6>趣味</h6>
                                    <p>
                                        APEX
                                    </p> -->
                                </div>

                                <!--スキルと資格セクション-->
                                <div class="list-group-item col-md-6">
                                    <h6><strong>スキル</strong><i class="fa fa-code ml-2"></i></h6>
                                    <?php
                                    foreach ($pro_lans as $row) {
                                        echo <<<HTML
                                            <span class="badge badge-danger"><i class="fa fa-gear"></i>&nbsp$row[programming_lan]</span>
                                        HTML;
                                    }
                                    ?>
                                    <hr>
                                    <h6><strong>資格</strong><i class="fa fa-certificate ml-2" aria-hidden="true"></i></h6>
                                    <?php
                                    foreach ($qual as $row) {
                                        echo <<<HTML
                                            <span class="badge badge-info"><i class="fa fa-mortar-board"></i>&nbsp$row[qual_name]</span>
                                        HTML;
                                    }
                                    ?>
                                    <hr>
                                    <h6><strong>ツールスキル</strong><i class="fa fa-cogs ml-2" aria-hidden="true"></i></h6>
                                    <?php
                                    foreach ($tools as $row) {
                                        echo <<<HTML
                                            <span class="badge badge-info badge-pill"><i class="fa fa-gear"></i>&nbsp$row[tool_name]</span>
                                        HTML;
                                    }

                                    ?>
                                </div>
                                <!--スキルと資格セクション終了-->

                                <!-- プロジェクトセクション-->
                                <div class="col-md-12">
                                    <h5 class="mt-2 mb-3"><strong>プロジェクト</strong><i class="fa fa-asterisk ml-2" aria-hidden="true"></i></h5>
                                    <hr style="width:50%;text-align:left;margin-left:0">
                                    <div class="col-lg-4 col-sm-6 mb-4">
                                        <div class="card h-100">
                                            <img class="card-img-top" src="<?= $project_img ?>" alt="">
                                                <div class="card-body">
                                                    <h4 class="card-title">
                                                        <a href="#"><?= $project_title ?></a>
                                                    </h4>
                                                        <p class="card-text"></p>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- プロジェクトセクション終了-->

                            </div>
                            <!--/row-->
                        </div>
                    <!-- メッセージシステム-->
                        <div class="tab-pane" id="messages">
                            <div class="form-outline">
                                <!-- <h6 class="border-bottom border-gray pb-2 mb-0">メッセージを送る</h6> -->
                                <form method="POST" onsubmit="send_only_message(); return false;">
                                    <input type="hidden" id="receiver_id" value="<?= $user_id ?>">
                                    <label class="form-label" for="textAreaExample"> <i class="fa fa-envelope-o"></i>&nbsp;Message&nbsp;to&nbsp;<strong><?= $user_name ?></strong></label>
                                    <textarea class="form-control" id="chat_text" col = "3" rows="4"></textarea>

                                    <br>
                                    <button type="submit" class="btn btn-primary">送信</button>
                                </form>
                            </div>
                        </div>
                    <!-- メッセージシステム終了-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    let favorited_id = <?= $user_id ?>
</script>

<?php require_once('../footer.php'); ?>