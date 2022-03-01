<?php
session_start();
// unset($_SESSION['account']);
require_once('../config.php');
require_once('../function.php');

if (empty($_SESSION['account'])) {
    $button = <<<HTML
        <button type="button" class="btn-solid-lg page-scroll" onclick="location.href='../signIn/signIn.php'">ログイン</button>
        <button type="button" class="btn-solid-lg page-scroll" onclick="location.href='../signUp/signUp.php'">新規登録</button>
    HTML;
} else {
    $button = <<<HTML
        <button type="button" class="btn-solid-lg page-scroll" onclick="location.href='../profile/profile_edit.php'">プロフィール編集</button>
    HTML;
}

require_once('../header.php');
require_once('../navbar.php');
?>

<!DOCTYPE html>
<html lang="ja">
<head></head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">



    <meta name="description" content="Your description">
    <meta name="author" content="Your name">




    <title>UpColor index.page</title>


    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <link href="css/introduction_swiper.css" rel="stylesheet">
	<link href="css/introduction_magnific-popup.css" rel="stylesheet">
	<link href="css/introduction_styles.css" rel="stylesheet">

	<!-- Favicon  -->
    <link rel="icon" href="../../includes/images/upcolor.png">
</head>
<body data-spy="scroll" data-target=".fixed-top">



    <!-- Header -->
    <header id="header" class="header">
        <div class="container">


            <div class="row">
                <div class="col-lg-6">
                    <div class="image-container">
                        <img class="img-fluid2" src="images/UPCOLOR.svg" alt="alternative">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-container">
                        <h3>UpColorへようこそ<br>ここではプロフィールのテンプレート、個人間のチャット、ポートフォリオの展示など様々な機能があります。</h3>

                        <?= $button ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="services">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">


                        <div class="card">
                            <div class="card-image">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">プロフィール設定</h5>
                                <div class="card-text">まず最初にプロフィールの設定を行ってください。</div>
                            </div>
                        </div>



                        <div class="card">
                            <div class="card-image">
                                <i class="fas fa-user-cog"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">プロフィールテンプレートの使用方法</h5>
                                <div class="card-text">ご自由にお使いいただいてよろしいです。あなただけのプロフィールを。</div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-image">
                                <i class="fas fa-id-card-alt"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">ポートフォリオの展示について</h5>
                                <div class="card-text">ご自身で作成されたポートフォリオをプロフィールのポートフォリオ展示欄にURLで貼ってください。</div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </header>



    <div id="details" class="basic-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-5">
                    <div class="text-container">
                        <h2>プロフィール設定方法</h2>
                        <hr class="hr-heading">
                        <p><strong>プロフィール設定</strong></p>
                        <ul class="list-unstyled li-space-lg">
                        <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">「一般」からプロフィールの画像設定・背景色・専攻を選んでください。<br>※専攻は必ず選んでください。</div>
                        </li>

                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">「ABOUT」で自己紹介・プログラミング言語・資格を入力してください。</div>
                            </li>

                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">「プロジェクト」でGitHUBのリンク・プロジェクトを載せることができます。</div>
                            </li>

                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-7">
                    <div class="image-container">
                        <img class="img-fluid" src="images/henshu1.png" alt="alternative" style="border: 1px #000000 solid;">
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="basic-2">
        <div class="container-fluid">
            <div class="row">
                <div class="image-area">
                    <div class="image-container">
                        <img class="img-fluid" src="images/henshu2.png" alt="alternative" style="border: 1px #000000 solid;">
                    </div>
                </div>
                <div class="text-area">
                    <div class="text-container">
                        <h2>プロフィールテンプレートの使用方法</h2>
                            <li class="media">
                                    <i>・</i>
                                    <div class="media-body">まず始めにプロフィールのテンプレートを1つ選択し、確定を押します。次に「詳しく」に飛んでください。</div>
                            </li>
                         <a class="btn-solid-reg popup-with-move-anim" href="#details-lightbox">詳しく</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<div id="details-lightbox" class="lightbox-basic zoom-anim-dialog mfp-hide">
        <div class="row">
            <button title="Close (Esc)" type="button" class="mfp-close x-button">×</button>
			<div class="col-lg-8">
                <div class="image-container">
                    <img class="img-fluid" src="images/henshu4.png" alt="alternative" style="border: 1px #000000 solid;">
                </div>
			</div>
			<div class="col-lg-4">
                <h3>テンプレート説</h3>
				<hr>
                <p>説明欄</p>
                <h4>テンプレート設定</h4>
                <ul class="list-unstyled li-space-lg">
                    <li class="media">
                        <i class="fas fa-square"></i><div class="media-body">左の欄にあるコピーボタンを押します。</div>
                    </li>
                    <li class="media">
                        <i class="fas fa-square"></i><div class="media-body">コピーされるのでGoogle,Safari,YahooなどにURLを貼り付けてください。</div>
                    </li>
                    <li class="media">
                        <i class="fas fa-square"></i><div class="media-body">後は個人で編集して終了です。</div>
                    </li>
                </ul>
                <a class="btn-solid-reg mfp-close page-scroll" href="#contact">戻る</a>
			</div>
		</div>
    </div>




    <div class="basic-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-5">
                    <div class="text-container">
                        <h2>ポートフォリオの展示に
                        </h2>
                        <hr class="hr-heading">
                        <ul class="list-unstyled li-space-lg">
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">1番目にGITHUBリンクを貼ります。</div>
                            </li>
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">2番目にプロジェクト数を記入します。</div>
                            </li>
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">3番目にプロジェクトタイトルとプロジェクトの画像を貼ります。</div>
                            </li>
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">確定を押して終了です。</div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-7">
                    <div class="image-container">
                        <img class="img-fluid" src="images/henshu3.png" alt="alternative" style="border: 1px #000000 solid;">
                    </div>
                </div>
            </div>
        </div>
    </div>









    <div id="projects" class="slider-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">


                    


                </div>
            </div>
        </div>
    </div>












    <!-- Scripts -->
    <script src="js/introduction_jquery.min.js"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
    <!-- <script src="js/introduction_bootstrap.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="js/introduction_jquery.easing.min.js"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
    <!-- <script src="js/introduction_swiper.min.js"></script> -->
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <script src="js/introduction_jquery.magnific-popup.js"></script> <!-- Magnific Popup for lightboxes -->
    <script src="js/introduction_scripts.js"></script>
    <!-- Custom scripts -->


</body>
</html>