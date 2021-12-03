<?php
session_start();
// unset($_SESSION['account']);
require_once('../config.php');
require_once('../function.php');

$s = <<<SQL
    SELECT user_name
    FROM accounts
    WHERE user_id=?
SQL;
$sql = $pdo -> prepare($s);
$sql -> execute([ID]);
$user_name = '';
foreach ($sql as $row) {
    $user_name = $row['user_name'];
}
require_once('../header.php');
require_once('../navbar.php');
?>



<!DOCTYPE html>
<html lang="ja">
<head></head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- SEO Meta Tags -->
    <meta name="description" content="Your description">
    <meta name="author" content="Your name">

    <!-- OG Meta Tags to improve the way the post looks when you share the page on Facebook, Twitter, LinkedIn -->
	<meta property="og:site_name" content="" /> <!-- website name -->
	<meta property="og:site" content="" /> <!-- website link -->
	<meta property="og:title" content=""/> <!-- title shown in the actual shared post -->
	<meta property="og:description" content="" /> <!-- description shown in the actual shared post -->
	<meta property="og:image" content="" /> <!-- image link, make sure it's jpg -->
	<meta property="og:url" content="" /> <!-- where do you want your post to link to -->
	<meta name="twitter:card" content="summary_large_image"> <!-- to have large image post format in Twitter -->

    <!-- Webpage Title -->
    <title>UpColor index.page</title>

    <!-- Styles -->
    <!--<link rel="preconnect" href="https://fonts.gstatic.com"> -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link href="css/introduction_bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <!-- <link href="css/introduction_fontawesome-all.css" rel="stylesheet"> -->
    <link href="css/introduction_swiper.css" rel="stylesheet">
	<link href="css/introduction_magnific-popup.css" rel="stylesheet">
	<link href="css/introduction_styles.css" rel="stylesheet">

	<!-- Favicon  -->
    <link rel="icon" href="../../includes/images/upcolor.png">
</head>
<body data-spy="scroll" data-target=".fixed-top">

 <!-- Navigation -->
    <!-- <nav class="navbar navbar-expand-lg fixed-top navbar-dark"> -->
        <!-- <div class="container"> -->

            <!-- Image Logo -->
            <!-- <a class="navbar-brand logo-image" href="index.html"><img src="images/UPCOLOR.svg" alt="alternative"></a> -->

            <!-- Text Logo - Use this if you don't have a graphic logo -->
            <!-- <a class="navbar-brand logo-text page-scroll" href="index.html">Spify</a> -->

            <!-- <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas"> -->
                <!-- <span class="navbar-toggler-icon"></span> -->
            <!-- </button> -->

              <!-- <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault"> -->
                <!-- <ul class="navbar-nav ml-auto"> -->
                    <!-- <li class="nav-item"> -->
                        <!-- <a class="nav-link page-scroll" href="#header">Home <span class="sr-only">(current)</span></a> -->
                    <!-- </li> -->
                    <!-- <li class="nav-item"> -->
                        <!-- <a class="nav-link page-scroll sm" href="#details">Details</a> -->
                    <!-- </li> -->
                                <!-- <li class="nav-item"> -->
                                    <!-- <a class="nav-link page-scroll" href="#strengths">Strengths</a> -->
                                <!-- </li> -->
                                <!-- <li class="nav-item"> -->
                                    <!-- <a class="nav-link page-scroll" href="#about">About</a> -->
                                <!-- </li> -->
                                    <!-- </ul> -->
                                    <!-- <span class="nav-item social-icons"> -->
                                <!-- <span class="fa-stack"> -->
                                    <!-- <a href="#your-link"> -->
                                        <!-- <i class="fas fa-circle fa-stack-2x"></i> -->
                                        <!-- <i class="fab fa-facebook-f fa-stack-1x"></i> -->
                                    <!-- </a> -->
                                <!-- </span> -->
                                <!-- <span class="fa-stack"> -->
                                    <!-- <a href="#your-link"> -->
                                        <!-- <i class="fas fa-circle fa-stack-2x"></i> -->
                                        <!-- <i class="fab fa-twitter fa-stack-1x"></i> -->
                                    <!-- </a> -->
                                <!-- </span> -->
                            <!-- </span> -->
                <!-- </div> end of navbar-collapse -->
        <!--</div>-->  <!-- end of container -->
    <!--</nav>-->  <!-- end of navbar -->
    <!-- end of navigation -->

    <!-- Header -->
    <header id="header" class="header">
        <div class="container">


            <div class="row">
                <div class="col-lg-6">
                    <div class="image-container">
                        <img class="img-fluid" src="images/UPCOLOR.svg" alt="alternative">
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
                <div class="col-lg-6">
                    <div class="text-container">
                        <h3>UpColorへようこそ<br>ここではプロフィールのテンプレート、個人間のチャット、ポートフォリオの展示など様々な機能があります。</h3>

                        <button type="button" class="btn-solid-lg page-scroll" onclick="location.href='../signIn/signIn.php'">ログイン</button>
                        <button type="button" class="btn-solid-lg page-scroll" onclick="location.href='../signUp/signUp.php'">新規登録</button>
                    </div> <!-- end of text-container -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
        <div class="services">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">

                        <!-- Card -->
                        <div class="card">
                            <div class="card-image">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">プロフィール設定</h5>
                                <div class="card-text">まず最初にプロフィールの設定を行ってください。</div>
                            </div>
                        </div>
                        <!-- end of card -->

                        <!-- Card -->
                        <div class="card">
                            <div class="card-image">
                                <i class="fas fa-pencil-ruler"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">プロフィールテンプレートの使用方法</h5>
                                <div class="card-text">ご自由にお使いいただいてよろしいです。あなただけのプロフィールを。</div>
                            </div>
                        </div>
                        <!-- end of card -->

                        <!-- Card -->
                        <div class="card">
                            <div class="card-image">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">ポートフォリオの展示について</h5>
                                <div class="card-text">ご自身で作成されたポートフォリオをプロフィールのポートフォリオ展示欄にURLで貼ってください。</div>
                            </div>
                        </div>
                        <!-- end of card -->

                    </div> <!-- end of col -->
                </div> <!-- end of row -->
            </div> <!-- end of container -->
        </div> <!-- end of services -->
    </header> <!-- end of header -->
    <!-- end of header -->


    <!-- Details 1 -->
    <div id="details" class="basic-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-5">
                    <div class="text-container">
                        <h2>プロフィール設定方法</h2>
                        <hr class="hr-heading">
                        <p>ここにプロフィールが完成したら設定方法を細かく載せる</p>
                        <ul class="list-unstyled li-space-lg">
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">あ</div>
                            </li>
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">い</div>
                            </li>
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">う</div>
                            </li>
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">え</div>
                            </li>
                        </ul>
                    </div> <!-- end of text-container -->
                </div> <!-- end of col -->
                <div class="col-lg-6 col-xl-7">
                    <div class="image-container">
                        <img class="img-fluid" src="images/profile.henshu.png" alt="alternative">
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of basic-1 -->
    <!-- end of details 1 -->


    <!-- Details 2 -->
    <div class="basic-2">
        <div class="container-fluid">
            <div class="row">
                <div class="image-area">
                    <div class="image-container">
                        <img class="img-fluid" src="images/template.png" alt="alternative">
                    </div> <!-- end of image-container -->
                </div> <!-- end of image-area -->
                <div class="text-area">
                    <div class="text-container">
                        <h2>プロフィールテンプレートの使用方法</h2>
                        <hr class="hr-heading">
                        <p>ここも上と同様に出来上がってから</p>
                        <a class="btn-solid-reg popup-with-move-anim" href="#details-lightbox">詳しく載せる</a>
                    </div> <!-- end of text-container -->
                </div> <!-- end of text-area -->
            </div> <!-- end of row -->
        </div> <!-- end of container-fluid -->
    </div> <!-- end of basic-1 -->
    <!-- end of details 2 -->


    <!-- Details Lightbox -->
    <!-- Lightbox -->
	<div id="details-lightbox" class="lightbox-basic zoom-anim-dialog mfp-hide">
        <div class="row">
            <button title="Close (Esc)" type="button" class="mfp-close x-button">×</button>
			<div class="col-lg-8">
                <div class="image-container">
                    <img class="img-fluid" src="images/profile.henshu.png" alt="alternative">
                </div> <!-- end of image-container -->
			</div> <!-- end of col -->
			<div class="col-lg-4">
                <h3>テンプレート説明</h3>
				<hr>
                <p>説明欄</p>
                <h4>User Feedback</h4>
                <p>自由に書いて言い欄</p>
                <ul class="list-unstyled li-space-lg">
                    <li class="media">
                        <i class="fas fa-check"></i><div class="media-body">あ</div>
                    </li>
                    <li class="media">
                        <i class="fas fa-check"></i><div class="media-body">い</div>
                    </li>
                    <li class="media">
                        <i class="fas fa-check"></i><div class="media-body">う</div>
                    </li>
                    <li class="media">
                        <i class="fas fa-check"></i><div class="media-body">え</div>
                    </li>
                    <li class="media">
                        <i class="fas fa-check"></i><div class="media-body">お</div>
                    </li>
                </ul>
                <a class="btn-solid-reg mfp-close page-scroll" href="#contact">Contact Us</a> <button class="btn-outline-reg mfp-close as-button" type="button">Back</button>
			</div> <!-- end of col -->
		</div> <!-- end of row -->
    </div> <!-- end of lightbox-basic -->
    <!-- end of lightbox -->
    <!-- end of details lightbox -->


    <!-- Details 3 -->
    <div class="basic-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-5">
                    <div class="text-container">
                        <h2>ポートフォリオの展示について</h2>
                        <hr class="hr-heading">
                        <p>ここも同様に上と一緒</p>
                        <ul class="list-unstyled li-space-lg">
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">あ</div>
                            </li>
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">い</div>
                            </li>
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">う</div>
                            </li>
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">え</div>
                            </li>
                        </ul>
                    </div> <!-- end of text-container -->
                </div> <!-- end of col -->
                <div class="col-lg-6 col-xl-7">
                    <div class="image-container">
                        <img class="img-fluid" src="images/profile.henshu.png" alt="alternative">
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of basic-3 -->
    <!-- end of details 3 -->







    <!-- Projects -->
    <div id="projects" class="slider-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <!-- Text Slider -->
                    <div class="slider-container">
                        <div class="swiper-container text-slider">
                            <div class="swiper-wrapper">

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="image-container">
                                                <img class="img-fluid" src="images/nishant.jpg" alt="alternative">
                                            </div> <!-- end of image-container -->
                                        </div> <!-- end of col -->
                                        <div class="col-lg-6">
                                            <div class="text-container">
                                                <h4>ニシャントを探せ</h4>
                                                <p>あ</p>
                                                <p class="testimonial-text">い</p>
                                                <div class="testimonial-author">う</div>
                                            </div> <!-- end of text-container -->
                                        </div> <!-- end of col -->
                                    </div> <!-- end of row -->
                                </div> <!-- end of swiper-slide -->
                                <!-- end of slide -->

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="image-container">
                                                <img class="img-fluid" src="images/project-2.jpg" alt="alternative">
                                            </div> <!-- end of image-container -->
                                        </div> <!-- end of col -->
                                        <div class="col-lg-6">
                                            <div class="text-container">
                                                <h4>ここにプロジェクトのしてもいいかも</h4>
                                                <p>あ</p>
                                                <p class="testimonial-text">い</p>
                                                <div class="testimonial-author">う</div>
                                            </div> <!-- end of text-container -->
                                        </div> <!-- end of col -->
                                    </div> <!-- end of row -->
                                </div> <!-- end of swiper-slide -->
                                <!-- end of slide -->

                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="image-container">
                                                <img class="img-fluid" src="images/mecup.png" alt="alternative">
                                            </div> <!-- end of image-container -->
                                        </div> <!-- end of col -->
                                        <div class="col-lg-6">
                                            <div class="text-container">
                                                <h4>同じ</h4>
                                                <p>あ</p>
                                                <p class="testimonial-text">い</p>
                                                <div class="testimonial-author">う</div>
                                            </div> <!-- end of text-container -->
                                        </div> <!-- end of col -->
                                    </div> <!-- end of row -->
                                </div> <!-- end of swiper-slide -->
                                <!-- end of slide -->

                            </div> <!-- end of swiper-wrapper -->

                            <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <!-- end of add arrows -->

                        </div> <!-- end of swiper-container -->
                    </div> <!-- end of slider-container -->
                    <!-- end of text slider -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of slider-1 -->
    <!-- end of projects -->


    <!-- About -->
    <div id="about" class="basic-6">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-container bg-gray">
                        <h2>ここも自由欄</h2>
                        <p>背景に画像あるからsvg画像で文字切り抜いてするのもあり、そこは要相談</p>
                        <ul class="list-unstyled li-space-lg">
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">あ</div>
                            </li>
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">い</div>
                            </li>
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body">う</div>
                            </li>
                        </ul>
                    </div> <!-- end of text-container -->
                    <div class="image-container">
                        <img class="img-fluid" src="images/mesaku.png" alt="alternative">
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of basic-6 -->
    <!-- end of about -->
    <!-- Footer -->
    <!--空白のためのdiv-->












    <!-- Scripts -->
    <script src="js/introduction_jquery.min.js"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
    <script src="js/introduction_bootstrap.min.js"></script> <!-- Bootstrap framework -->
    <script src="js/introduction_jquery.easing.min.js"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
    <script src="js/introduction_swiper.min.js"></script> <!-- Swiper for image and text sliders -->
    <script src="js/introduction_jquery.magnific-popup.js"></script> <!-- Magnific Popup for lightboxes -->
    <script src="js/introduction_scripts.js"></script> <!-- Custom scripts -->
</body>
</html>
<?php require_once ('../footer.php') ?>