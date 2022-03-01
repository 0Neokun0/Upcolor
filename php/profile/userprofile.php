<?php
session_start();
require_once('../config.php');
require_once('../function.php');

// 自作関数
login($_SESSION['account']);

$profile_img = $bg_color = $couse_name = $github_link = $qual_name = $tool_name = $introduction = $github_name = $project_num = $share_url = $project_title = $portfolio_link = $portfolio_img = '';

// データベースからログインしている人の情報を取ってきます
$s = <<<SQL
    SELECT *
    FROM accounts
            LEFT OUTER JOIN couses
                ON accounts.couse_id=couses.couse_id
            LEFT OUTER JOIN programming_lans
                ON accounts.user_id=programming_lans.user_id
            LEFT OUTER JOIN qual
                ON accounts.user_id=qual.user_id
            LEFT OUTER JOIN tools
                ON accounts.user_id=tools.user_id
    WHERE accounts.user_id=?
SQL;
$sql = $pdo -> prepare($s);
$sql -> execute([ID]);

if ($user_info = $sql -> fetch(PDO::FETCH_ASSOC)) {
    $user_mail = $user_info['user_mail'];
    $profile_img = $user_info['profile_img'];
    $bg_color = $user_info['bg_color'];
    $project_num = $user_info['project_num'];
    $couse_id = $user_info['couse_id'];
    $introduction = $user_info['introduction'];
    $github_account = $user_info['github_account'];
    $share_url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'].'/UpColor/php/template.php?'.NAME;
    $project_title = $user_info['project_name'];
    $project_img = $user_info['project_image'];
    $template_id = $user_info['template_id'];
    $release = $user_info['release_flg'];
}
$tx_color = get_text_color($bg_color);
//programing language 処理開始

$pro_lan_select = <<<SQL
    SELECT *
    FROM programming_lans
    WHERE user_id=?
SQL;
$sql = $pdo -> prepare($pro_lan_select);
$sql -> execute([ID]);
$programming_lans = $sql -> fetchAll(PDO::FETCH_ASSOC);

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
$tools_select = <<<SQL
    SELECT *
    FROM tools
    WHERE user_id=?
SQL;
$sql = $pdo -> prepare($tools_select);
$sql -> execute([ID]);
$tools = $sql -> fetchAll(PDO::FETCH_ASSOC);
//ツールスキル処理終了

// チャットの情報を取ってきます
$chat = <<<SQL
    SELECT *
    FROM chat
    LEFT JOIN accounts ON chat.user_id=accounts.user_id
    WHERE chat_id IN (SELECT MAX(chat_id) FROM chat WHERE receiver_id=? GROUP BY user_id)
    ORDER BY chat_id DESC
    LIMIT 4
SQL;
$chat = $pdo -> prepare($chat);
$chat -> execute([ID]);

// ここからHTML
require_once('../header.php');
require_once('../navbar.php');
?>

<!-- プロフィール表示欄 -->

<link rel="stylesheet" href="../../includes/css/userprofile.css"> <!-- user profile css-->
<div class="container pt-5">
    <div class="row clickable-row mt-3">
        <div class="col-lg-4">
            <div class="profile-card-4 z-depth-3">
                <div class="card">
                    <div class="card-body text-center rounded-top" style="color: <?= $tx_color ?>; background: <?= $bg_color ?>;">
                        <div class="user-box">
                            <img src="<?= $profile_img ?>" alt="user avatar">
                        </div>
                        <h5 class="mb-1"><?= NAME ?></h5>
                        <h6><?= $couse_name ?></h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group shadow-none">
                            <li class="list-group-item">
                                <div class="list-icon">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <div class="list-details">
                                    <span><?= MAIL ?></span>
                                    <small>メールアドレス</small>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="list-icon">
                                    <i class="fa fa-globe"></i>
                                </div>
                                <div class="list-details">
                                    <button id="url" style="font-size: small;" value="<?= $share_url ?>"><?= basename($share_url) ?></button> <span class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard()">コピー</span>
                                    <small>urlでプロフィールを共有できます</small>
                                </div>
                            </li>
                        </ul>
                        <div class="row text-center mt-4">
                            <div class="col p-2">
                                <h4 class="mb-1 line-height-5"><?= $project_num ?></h4>
                                <small class="mb-0 font-weight-bold">プロジェクト</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="<?= $github_account ?>" class="btn-social btn-github waves-effect waves-light m-1" target="_blank"><i class="fa fa-github"></i></a>
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
                        <!--<li class="nav-item">
                            <a href="javascript:void();" data-target="#edit" data-toggle="pill" class="nav-link"><i class="icon-note"></i> <span class="hidden-xs">プロフィール編集</span></a>
                        </li>-->
                    </ul>
                    <div class="tab-content p-3">
                        <div class="tab-pane active show" id="profile">
                            <h5 class="mb-4"><strong>ユーザープロフィール</strong></h5>
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
                                    foreach ($programming_lans as $row) {
                                        echo <<<HTML
                                            <span class="badge badge-success badge-pill"><i class="fa fa-code"></i>&nbsp$row[programming_lan]</span>
                                        HTML;
                                    }
                                    ?>
                                    <hr>
                                    <h6><strong>資格</strong><i class="fa fa-certificate ml-2" aria-hidden="true"></i></h6>
                                    <?php
                                    foreach ($qual as $row) {
                                        echo <<<HTML
                                            <span class="badge badge-info badge-pill"><i class="fa fa-mortar-board"></i>&nbsp$row[qual_name]</span>
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
                                            <a href="#"><img class="card-img-top" src="<?= $project_img ?>" alt=""></a>
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

                                <div class="my-3 p-3 bg-white rounded box-shadow">
                                    <h6 class="border-bottom border-gray pb-2 mb-0">最近の更新</h6>
                                    <?php
                                    foreach ($chat as $row) {
                                        echo <<<HTML
                                            <div class="media text-muted pt-3">

                                                <form action="../chat/chat_input.php" method="post">
                                                    <div class="card-group">

                                                            <div class="card text-dark bg-light mb-3">
                                                                <button class = "wrapper">
                                                                    <img src="$row[profile_img]" alt="" class="mr-2 rounded" width="32" height="32">
                                                                    <input type="hidden" name="receiver_id" value="$row[user_id]">
                                                                    <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                                                        <strong class="d-block text-gray-dark">$row[user_name]</strong>
                                                                        $row[chat_text]
                                                                    </p>
                                                            </button>

                                                    </div>
                                                </div>

                                                </form>

                                            </div>
                                        HTML;
                                    }
                                    ?>
                                    <small class="d-block text-right mt-3"><a href="../chat/chat_all.php"><strong>すべてのメッセージ</strong></a></small>
                                </div>

                        </div>
                    <!-- メッセージシステム終了-->
    <!--プロファイル編集セクション-->
                        <div class="tab-pane" id="edit">

                        </div>
                        <!--プロファイル編集終了-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- ここまで -->

<?php require_once('../footer.php'); ?>