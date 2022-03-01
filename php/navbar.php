<?php
$favorite = '';
if ((strpos(basename($_SERVER["REQUEST_URI"]), 'index.php') !== false || strpos(basename($_SERVER["REQUEST_URI"]), '') !== false) && empty($_SESSION['account'])) {
    $title = '';
    $contents = '';

    // ログインしていない場合何も出さない
} else if (empty($_SESSION['account'])) {
    $title = <<<HTML
        <i class="fa fa-user" aria-hidden="true"></i>
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            ログイン<b class="caret"></b>
        </a>
    HTML;

    $contents = <<<HTML
        <ul class="dropdown-menu">
            <li>
                <a href="URL/../../signIn/signIn.php">ログイン</a>
            </li>
            <li>
                <a href="URL/../../signUp/signUp.php">会員登録</a>
            </li>
        </ul>
    HTML;

    //データベースからユーザーIDを取得
} else if (isset($_SESSION['account'])) {
    $s = <<<SQL
        SELECT user_name
        FROM accounts
        WHERE user_id=?
    SQL;
    $sql = $pdo -> prepare($s);
    $sql -> execute([ID]);
    $nav_name = $sql -> fetch(PDO::FETCH_ASSOC);

    $title = <<<HTML
        <i class="fa fa-user" aria-hidden="true"></i>
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            $nav_name[user_name]<b class="caret"></b>
        </a>
    HTML;

    $contents = <<<HTML
        <ul class="dropdown-menu">
            <li>
                <a href="URL/../../profile/userprofile.php">プロフィール</a>
            </li>
            <li>
                <a href="URL/../../profile/profile_edit.php">プロフィール編集</a>
            </li>
            <li>
                <a href="URL/../../chat/chat_all.php">チャット</a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a href="#" id="logout">ログアウト</a>
            </li>
        </ul>
    HTML;

    $favorite = <<<HTML
        <hr class="dropdown-divider">
        <a class="dropdown-item" href="URL/../../profile_view/profile_thumbnail.php?favorite=1">お気に入り</a>
    HTML;
}

$nav_couse = '';
if (isset($_GET['couse'])) {
    $nav_couse = $_GET['couse'];
}
?>

<!-- navigation bar-->

<div class="navigation-wrap bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-light  start-header start-style" role="navigation">
                    <a href="URL/../../home/">
                        <img src = "../../includes/images/Up.png">
                    </a>




                    <hr class = "mr-3">

                    <a class="navbar-brand" href="URL/../../home/">UpColor</a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 active">
                                <a class="nav-link dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-users mr-2" aria-hidden="true"></i><?= top($nav_couse); ?>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="URL/../../profile_view/profile_thumbnail.php">プロフィール</a>
                                    <hr class="dropdown-divider">
                                    <a class="dropdown-item" href="URL/../../profile_view/profile_thumbnail.php?couse=1">本科</a>
                                    <a class="dropdown-item" href="URL/../../profile_view/profile_thumbnail.php?couse=2">情報処理・ネットワーク専攻</a>
                                    <a class="dropdown-item" href="URL/../../profile_view/profile_thumbnail.php?couse=3">ゲーム専攻</a>
                                    <a class="dropdown-item" href="URL/../../profile_view/profile_thumbnail.php?couse=4">デザイン・イラスト専攻</a>
                                    <a class="dropdown-item" href="URL/../../profile_view/profile_thumbnail.php?couse=5">ハードウェア専攻</a>
                                    <?= $favorite ?>
                                </div>
                            </li>
                            <form action="URL/../../profile_view/profile_thumbnail.php" class="form-inline" method="POST">
                                <input class="form-control mr-sm-4 ml-sm-4 col-xs-4" type="search" name="search" placeholder="名前で検索" aria-label="Search" value="<?= (isset($_POST['search'])) ? $_POST['search'] : ''; ?>">
                                <i class="fa fa-search mr-2" aria-hidden="true"></i><input type="submit" class="btn btn-sm btn-outline-secondary" value="検索">
                            </form>
                        </ul>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">

                            <?= $title ?>
                            <?= $contents ?>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>



<script>



(function($) { "use strict";

$(function() {
    var header = $(".start-style");
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 10) {
            header.removeClass('start-style').addClass("scroll-on");
        } else {
            header.removeClass("scroll-on").addClass('start-style');
        }
    });
});

//Animation

$(document).ready(function() {
    $('body.hero-anime').removeClass('hero-anime');
});

//Menu On Hover

$('body').on('mouseenter mouseleave','.nav-item',function(e){
        if ($(window).width() > 750) {
            var _d=$(e.target).closest('.nav-item');_d.addClass('show');
            setTimeout(function(){
            _d[_d.is(':hover')?'addClass':'removeClass']('show');
            },1);
        }
});

//Switch light/dark

$("#switch").on('click', function () {
    if ($("body").hasClass("dark")) {
        $("body").removeClass("dark");
        $("#switch").removeClass("switched");
    }
    else {
        $("body").addClass("dark");
        $("#switch").addClass("switched");
    }
});

})(jQuery);
</script>