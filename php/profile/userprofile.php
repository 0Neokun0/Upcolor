<?php
session_start();
require_once('../config.php');
require_once('../function.php');

// 自作関数
login($_SESSION['account']);

$profile_img = $bg_color = $couse_name = $github_link = $qual_name = $introduction = $github_name = $project_num = $share_url = $portfolio_name = $portfolio_link = $portfolio_img = '';

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
    WHERE accounts.user_id=?
SQL;
$sql = $pdo -> prepare($s);
$sql -> execute([ID]);

if ($user_info = $sql -> fetch(PDO::FETCH_ASSOC)) {
    $user_name = $user_info['user_name'];
    $user_mail = $user_info['user_mail'];
    $profile_img = $user_info['profile_img'];
    $bg_color = $user_info['bg_color'];
    $couse_id = $user_info['couse_id'];
    $introduction = $user_info['introduction'];
    $github_account = $user_info['github_account'];
    $template_id = $user_info['template_id'];
    $release = $user_info['release_flg'];
}

$tx_color = get_text_color($bg_color);

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

// チャットの情報を取ってきます
// $chat = <<<SQL
//     SELECT *
//     FROM chat
//     LEFT JOIN accounts ON chat.user_id=accounts.user_id
//     WHERE chat_id IN (SELECT MAX(chat_id) FROM chat WHERE receiver_id=? GROUP BY user_id)
//     ORDER BY chat_id DESC
//     LIMIT 4
// SQL;
// $chat = $pdo -> prepare($chat);
// $chat -> execute([ID]);

// ここからHTML
require_once('../header.php');
require_once('../navbar.php');
?>

<!-- プロフィール表示欄 -->
<div class="container pt-5">
    <div class="row clickable-row">
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
                        <a href="javascript:void()" class="btn-social btn-facebook waves-effect waves-light m-1"><i class="fa fa-facebook"></i></a>
                        <a href="javascript:void()" class="btn-social btn-google-plus waves-effect waves-light m-1"><i class="fa fa-google-plus"></i></a>
                        <a href="javascript:void()" class="list-inline-item btn-social btn-behance waves-effect waves-light"><i class="fa fa-behance"></i></a>
                        <a href="javascript:void()" class="list-inline-item btn-social btn-dribbble waves-effect waves-light"><i class="fa fa-dribbble"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card z-depth-3">
                <div class="card-body">
                    <ul class="nav nav-pills nav-pills-primary nav-justified">
                        <li class="nav-item">
                            <a href="javascript:void();" data-target="#profile" data-toggle="pill" class="nav-link active show"><i class="icon-user"></i> <span class="hidden-xs">プロフィール</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void();" data-target="#messages" data-toggle="pill" class="nav-link"><i class="icon-envelope-open"></i> <span class="hidden-xs">メッセージ</span></a>
                        </li>
                        <!--<li class="nav-item">
                            <a href="javascript:void();" data-target="#edit" data-toggle="pill" class="nav-link"><i class="icon-note"></i> <span class="hidden-xs">プロフィール編集</span></a>
                        </li>-->
                    </ul>
                    <div class="tab-content p-3">
                        <div class="tab-pane active show" id="profile">
                            <h5 class="mb-3">ユーザープロフィール</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>About</h6>
                                    <p>
                                    <?= $introduction ?> <!--自己紹介変数 -->
                                    </p>
                                    <!--<h6>趣味</h6>
                                    <p>
                                        APEX
                                    </p> -->
                                </div>

                                <!--スキルと資格セクション-->
                                <div class="col-md-6">
                                    <h6>スキル</h6>
                                    <?php
                                    foreach ($programming_lans as $row) {
                                        echo <<<HTML
                                            <a class="badge badge-light badge-pill">$row[programming_lan]</a>
                                        HTML;
                                    }
                                    ?>
                                    <hr>
                                    <h6>資格</h6>
                                    <?php
                                    foreach ($qual as $row) {
                                        echo <<<HTML
                                            <a class="badge bage-light badge-pill">$row[qual_name]</a>
                                        HTML;
                                    }
                                    ?>
                                </div>
                                <!--スキルと資格セクション終了-->

                                <!-- プロジェクトセクション-->
                                <div class="col-md-12">
                                    <h5 class="mt-2 mb-3"><span class="fa fa-clock-o ion-clock float-right"></span>プロジェクト</h5>


                                    <hr style="width:50%;text-align:left;margin-left:0">
                                    <div class="col-lg-4 col-sm-6 mb-4">
                                        <div class="card h-100">
                                            <a href="#"><img class="card-img-top" src="<?= $portfolio_img ?>" alt=""></a>
                                                <div class="card-body">
                                                    <h4 class="card-title">
                                                        <a href="#"><?= $project_name ?></a>
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
                            <div class="alert alert-info alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <div class="alert-icon">
                                    <i class="icon-info"></i>
                                </div>
                                <div class="alert-message">
                                    <span><strong>メッセージ!</strong> 新しいメッセージを届いてきていました.</span>
                                </div>
                            </div>
                            <main role="main" class="container bootdey.com">
                                <div class="my-3 p-3 bg-white rounded box-shadow">
                                    <h6 class="border-bottom border-gray pb-2 mb-0">Recent updates</h6>
                                    <?php
                                    foreach ($chat as $row) {
                                        echo <<<HTML
                                            <div class="media text-muted pt-3">
                                                <img src="$row[profile_img]" alt="" class="mr-2 rounded" width="32" height="32">
                                                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                                                    <strong class="d-block text-gray-dark">$row[user_name]</strong>
                                                    $row[chat_text]
                                                </p>
                                            </div>
                                        HTML;
                                    }
                                    ?>
                                    <small class="d-block text-right mt-3"><a href="../chat/chat_all.php">All messages</a></small>
                                </div>
                            </main>
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

<script type="text/javascript">
    let copy_target = <?= $share_url; ?>
</script>

<style type="text/css">
/* User Cards */
.user-box {
    width: 200px;
    margin: auto;
    margin-bottom: 20px;

}
.container {
width: auto;
}

.row {
width: auto;
}

.user-box img {
    width: 100%;
    border-radius: 50%;
	padding: 3px;
	background: #fff;
	-webkit-box-shadow: 0px 5px 25px 0px rgba(0, 0, 0, 0.2);
    -moz-box-shadow: 0px 5px 25px 0px rgba(0, 0, 0, 0.2);
    -ms-box-shadow: 0px 5px 25px 0px rgba(0, 0, 0, 0.2);
    box-shadow: 0px 5px 25px 0px rgba(0, 0, 0, 0.2);
}

.profile-card-2 .card {
	position:relative;
}

.profile-card-2 .card .card-body {
	z-index:1;
}

.profile-card-2 .card::before {
    content: "";
    position: absolute;
    top: 0px;
    right: 0px;
    left: 0px;
	border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
    height: 112px;
    background-color: #e6e6e6;
}

.profile-card-2 .card.profile-primary::before {
	background-color: #008cff;
}
.profile-card-2 .card.profile-success::before {
	background-color: #15ca20;
}
.profile-card-2 .card.profile-danger::before {
	background-color: #fd3550;
}
.profile-card-2 .card.profile-warning::before {
	background-color: #ff9700;
}
.profile-card-2 .user-box {
	margin-top: 30px;
}

.profile-card-3 .user-fullimage {
	position:relative;
}

.profile-card-3 .user-fullimage .details{
	position: absolute;
    bottom: 0;
    left: 0px;
	width:100%;
}

.profile-card-4 .user-box {
    width: 110px;
    margin: auto;
    margin-bottom: 10px;
    margin-top: 15px;
}

.profile-card-4 .list-icon {
    display: table-cell;
    font-size: 30px;
    padding-right: 20px;
    vertical-align: middle;
    color: #223035;
}

.profile-card-4 .list-details {
	display: table-cell;
	vertical-align: middle;
	font-weight: 600;
    color: #223035;
    font-size: 15px;
    line-height: 15px;
}

.profile-card-4 .list-details small{
	display: table-cell;
	vertical-align: middle;
	font-size: 12px;
	font-weight: 400;
    color: #808080;
}

/*Nav Tabs & Pills */
.nav-tabs .nav-link {
    color: #223035;
	font-size: 12px;
    text-align: center;
	letter-spacing: 1px;
    font-weight: 600;
	margin: 2px;
    margin-bottom: 0;
	padding: 12px 20px;
    text-transform: uppercase;
    border: 1px solid transparent;
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;

}
.nav-tabs .nav-link:hover{
    border: 1px solid transparent;
}
.nav-tabs .nav-link i {
    margin-right: 2px;
	font-weight: 600;
}

.top-icon.nav-tabs .nav-link i{
	margin: 0px;
	font-weight: 500;
	display: block;
    font-size: 20px;
    padding: 5px 0;
}

.nav-tabs-primary.nav-tabs{
	border-bottom: 1px solid #008cff;
}

.nav-tabs-primary .nav-link.active, .nav-tabs-primary .nav-item.show>.nav-link {
    color: #008cff;
    background-color: #fff;
    border-color: #008cff #008cff #fff;
    border-top: 3px solid #008cff;
}

.nav-tabs-success.nav-tabs{
	border-bottom: 1px solid #15ca20;
}

.nav-tabs-success .nav-link.active, .nav-tabs-success .nav-item.show>.nav-link {
    color: #15ca20;
    background-color: #fff;
    border-color: #15ca20 #15ca20 #fff;
    border-top: 3px solid #15ca20;
}

.nav-tabs-info.nav-tabs{
	border-bottom: 1px solid #0dceec;
}

.nav-tabs-info .nav-link.active, .nav-tabs-info .nav-item.show>.nav-link {
    color: #0dceec;
    background-color: #fff;
    border-color: #0dceec #0dceec #fff;
    border-top: 3px solid #0dceec;
}

.nav-tabs-danger.nav-tabs{
	border-bottom: 1px solid #fd3550;
}

.nav-tabs-danger .nav-link.active, .nav-tabs-danger .nav-item.show>.nav-link {
    color: #fd3550;
    background-color: #fff;
    border-color: #fd3550 #fd3550 #fff;
    border-top: 3px solid #fd3550;
}

.nav-tabs-warning.nav-tabs{
	border-bottom: 1px solid #ff9700;
}

.nav-tabs-warning .nav-link.active, .nav-tabs-warning .nav-item.show>.nav-link {
    color: #ff9700;
    background-color: #fff;
    border-color: #ff9700 #ff9700 #fff;
    border-top: 3px solid #ff9700;
}

.nav-tabs-dark.nav-tabs{
	border-bottom: 1px solid #223035;
}

.nav-tabs-dark .nav-link.active, .nav-tabs-dark .nav-item.show>.nav-link {
    color: #223035;
    background-color: #fff;
    border-color: #223035 #223035 #fff;
    border-top: 3px solid #223035;
}

.nav-tabs-secondary.nav-tabs{
	border-bottom: 1px solid #75808a;
}
.nav-tabs-secondary .nav-link.active, .nav-tabs-secondary .nav-item.show>.nav-link {
    color: #75808a;
    background-color: #fff;
    border-color: #75808a #75808a #fff;
    border-top: 3px solid #75808a;
}

.tabs-vertical .nav-tabs .nav-link {
    color: #223035;
    font-size: 12px;
    text-align: center;
    letter-spacing: 1px;
    font-weight: 600;
    margin: 2px;
    margin-right: -1px;
    padding: 12px 1px;
    text-transform: uppercase;
    border: 1px solid transparent;
    border-radius: 0;
    border-top-left-radius: .25rem;
    border-bottom-left-radius: .25rem;
}

.tabs-vertical .nav-tabs{
	border:0;
	border-right: 1px solid #dee2e6;
}

.tabs-vertical .nav-tabs .nav-item.show .nav-link, .tabs-vertical .nav-tabs .nav-link.active {
    color: #495057;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
    border-bottom: 1px solid #dee2e6;
    border-right: 0;
    border-left: 1px solid #dee2e6;
}

.tabs-vertical-primary.tabs-vertical .nav-tabs{
	border:0;
	border-right: 1px solid #008cff;
}

.tabs-vertical-primary.tabs-vertical .nav-tabs .nav-item.show .nav-link, .tabs-vertical-primary.tabs-vertical .nav-tabs .nav-link.active {
    color: #008cff;
    background-color: #fff;
    border-color: #008cff #008cff #fff;
    border-bottom: 1px solid #008cff;
    border-right: 0;
    border-left: 3px solid #008cff;
}

.tabs-vertical-success.tabs-vertical .nav-tabs{
	border:0;
	border-right: 1px solid #15ca20;
}

.tabs-vertical-success.tabs-vertical .nav-tabs .nav-item.show .nav-link, .tabs-vertical-success.tabs-vertical .nav-tabs .nav-link.active {
    color: #15ca20;
    background-color: #fff;
    border-color: #15ca20 #15ca20 #fff;
    border-bottom: 1px solid #15ca20;
    border-right: 0;
    border-left: 3px solid #15ca20;
}

.tabs-vertical-info.tabs-vertical .nav-tabs{
	border:0;
	border-right: 1px solid #0dceec;
}

.tabs-vertical-info.tabs-vertical .nav-tabs .nav-item.show .nav-link, .tabs-vertical-info.tabs-vertical .nav-tabs .nav-link.active {
    color: #0dceec;
    background-color: #fff;
    border-color: #0dceec #0dceec #fff;
    border-bottom: 1px solid #0dceec;
    border-right: 0;
    border-left: 3px solid #0dceec;
}

.tabs-vertical-danger.tabs-vertical .nav-tabs{
	border:0;
	border-right: 1px solid #fd3550;
}

.tabs-vertical-danger.tabs-vertical .nav-tabs .nav-item.show .nav-link, .tabs-vertical-danger.tabs-vertical .nav-tabs .nav-link.active {
    color: #fd3550;
    background-color: #fff;
    border-color: #fd3550 #fd3550 #fff;
    border-bottom: 1px solid #fd3550;
    border-right: 0;
    border-left: 3px solid #fd3550;
}

.tabs-vertical-warning.tabs-vertical .nav-tabs{
	border:0;
	border-right: 1px solid #ff9700;
}

.tabs-vertical-warning.tabs-vertical .nav-tabs .nav-item.show .nav-link, .tabs-vertical-warning.tabs-vertical .nav-tabs .nav-link.active {
    color: #ff9700;
    background-color: #fff;
    border-color: #ff9700 #ff9700 #fff;
    border-bottom: 1px solid #ff9700;
    border-right: 0;
    border-left: 3px solid #ff9700;
}

.tabs-vertical-dark.tabs-vertical .nav-tabs{
	border:0;
	border-right: 1px solid #223035;
}

.tabs-vertical-dark.tabs-vertical .nav-tabs .nav-item.show .nav-link, .tabs-vertical-dark.tabs-vertical .nav-tabs .nav-link.active {
    color: #223035;
    background-color: #fff;
    border-color: #223035 #223035 #fff;
    border-bottom: 1px solid #223035;
    border-right: 0;
    border-left: 3px solid #223035;
}

.tabs-vertical-secondary.tabs-vertical .nav-tabs{
	border:0;
	border-right: 1px solid #75808a;
}

.tabs-vertical-secondary.tabs-vertical .nav-tabs .nav-item.show .nav-link, .tabs-vertical-secondary.tabs-vertical .nav-tabs .nav-link.active {
    color: #75808a;
    background-color: #fff;
    border-color: #75808a #75808a #fff;
    border-bottom: 1px solid #75808a;
    border-right: 0;
    border-left: 3px solid #75808a;
}

.nav-pills .nav-link {
    border-radius: .25rem;
    color: #223035;
    font-size: 12px;
    text-align: center;
	letter-spacing: 1px;
    font-weight: 600;
    text-transform: uppercase;
	margin: 3px;
    padding: 12px 20px;
	-webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;

}

.nav-pills .nav-link:hover {
    background-color:#f4f5fa;
}

.nav-pills .nav-link i{
	margin-right:2px;
	font-weight: 600;
}

.top-icon.nav-pills .nav-link i{
	margin: 0px;
	font-weight: 500;
	display: block;
    font-size: 20px;
    padding: 5px 0;
}

.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
    color: #fff;
    background-color: #008cff;
    box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(0, 140, 255, 0.5);
}

.nav-pills-success .nav-link.active, .nav-pills-success .show>.nav-link {
    color: #fff;
    background-color: #15ca20;
    box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(21, 202, 32, .5);
}

.nav-pills-info .nav-link.active, .nav-pills-info .show>.nav-link {
    color: #fff;
    background-color: #0dceec;
    box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(13, 206, 236, 0.5);
}

.nav-pills-danger .nav-link.active, .nav-pills-danger .show>.nav-link{
    color: #fff;
    background-color: #fd3550;
    box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(253, 53, 80, .5);
}

.nav-pills-warning .nav-link.active, .nav-pills-warning .show>.nav-link {
    color: #fff;
    background-color: #ff9700;
    box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(255, 151, 0, .5);
}

.nav-pills-dark .nav-link.active, .nav-pills-dark .show>.nav-link {
    color: #fff;
    background-color: #223035;
    box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(34, 48, 53, .5);
}

.nav-pills-secondary .nav-link.active, .nav-pills-secondary .show>.nav-link {
    color: #fff;
    background-color: #75808a;
    box-shadow: 0 4px 20px 0 rgba(0,0,0,.14), 0 7px 10px -5px rgba(117, 128, 138, .5);
}
.card .tab-content{
	padding: 1rem 0 0 0;
}

.z-depth-3 {
    -webkit-box-shadow: 0 11px 7px 0 rgba(0,0,0,0.19),0 13px 25px 0 rgba(0,0,0,0.3);
    box-shadow: 0 11px 7px 0 rgba(0,0,0,0.19),0 13px 25px 0 rgba(0,0,0,0.3);
}

</style>

<!-- ここまで -->

<?php require_once('../footer.php'); ?>