<?php
session_start();
require_once('config.php');
require_once('function.php');

$profile_img = $user_name = $user_mail = $couse_name = $github_link = $introduction = $project_title = $project_num = $qual_name = $tool_name = $project_link = $project_img = $github_name = '';

$s = <<<SQL
    SELECT *
    FROM accounts
                LEFT OUTER JOIN couses
                    ON accounts.couse_id=couses.couse_id
                LEFT OUTER JOIN programming_lans
                    ON accounts.user_id=programming_lans.user_id
                LEFT OUTER JOIN tools
                    ON accounts.user_id=tools.user_id
        WHERE accounts.user_name=?
SQL;
$sql = $pdo -> prepare($s);
$sql -> execute([$_SERVER['QUERY_STRING']]);
$user_info = $sql -> fetch(PDO::FETCH_ASSOC);

$user_id = $user_info['user_id'];
$user_name = $user_info['user_name'];
$user_mail = $user_info['user_mail'];
$profile_img = '../profile_images/'.basename($user_info['profile_img']);
$bg_color = $user_info['bg_color'];
$couse_name = $user_info['couse_name'];
$introduction = $user_info['introduction'];
$github_account = $user_info['github_account'];
$project_num = $user_info['project_num'];
$project_title = $user_info['project_name'];
$project_img = '../project_img/'.basename($user_info['project_image']);
$template_id = $user_info['template_id'];
$release = $user_info['release_flg'];

//programing language 処理開始

$select_pro_lans = <<<SQL
    SELECT *
    FROM programming_lans
    WHERE user_id=?
SQL;
$pro_lans = $pdo -> prepare($select_pro_lans);
$pro_lans -> execute([$user_id]);
//programing language 処理終了

//資格処理開始

$select_qual = <<<SQL
    SELECT *
    FROM qual
    WHERE user_id=?
SQL;
$qual = $pdo -> prepare($select_qual);
$qual -> execute([$user_id]);

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


if ($template_id == 1) {
?>
    <!-- template 1 開始-->
    <!DOCTYPE html>
    <html lang="ja">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="description" content="">
            <meta name="author" content="">
            <title><?= $user_name ?></title>
            <link rel="icon" type="image/x-icon" href="favicon.ico">
            <!-- Font Awesome icons (free version)-->
            <script src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" crossorigin="anonymous"></script>
            <!-- Google fonts-->
            <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet" type="text/css">
            <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i" rel="stylesheet" type="text/css">
            <!-- Core theme CSS (includes Bootstrap)-->
            <link href="../includes/template_1/css/style.css" rel="stylesheet">
        </head>
        <body id="page-top">
            <!-- Navigation　開始-->
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top" id="sideNav">
                <a class="navbar-brand js-scroll-trigger" href="#page-top">
                    <span class="d-block d-lg-none"><?= $user_name ?></span>
                    <span class="d-none d-lg-block"><img class="img-fluid img-profile rounded-circle mx-auto mb-2" src="<?= $profile_img ?>" alt="..." /></span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#about">About</a></li>
                        <!-- <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#project">プロジェット</a></li> -->
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#skills">スキル</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#interests">自己アピール・自己紹介</a></li>
                    </ul>
                </div>
            </nav>
            <!-- Navigation 終了-->
            <!-- Page Content-->
            <div class="container-fluid p-0">
                <!-- About 開始-->
                <section class="resume-section" id="about">
                    <div class="resume-section-content">
                        <h1 class="mb-0">
                        <?= $user_name ?>
                        </h1>
                        <div class="subheading mb-5">
                            <a href="2180022@g.i-seifu.jp"><?= $user_mail ?></a>
                        </div>
                        <h2 class="mb-0"><?= $couse_name ?></h2>
                        <div class="social-icons">
                        <hr style="width:50%;text-align:left;margin-left:0">
                            <!--<a class="social-icon" href="https://www.linkedin.com/in/nishant-meher-2a2288137/"><i class="fab fa-linkedin-in"></i></a>-->
                            <a class="social-icon" href="<?= $github_account ?>"><i class="fab fa-github"></i></a>
                            <hr style="width:50%;text-align:left;margin-left:0">
                            <!--<a class="social-icon" href="https://twitter.com/396466477dab4a8"><i class="fab fa-twitter"></i></a>-->
                        </div>
                        <?php
                        foreach ($qual as $row) {
                            echo <<<HTML
                                <h3 class="mb-0">$row[qual_name]</h3>
                            HTML;
                        }
                        ?>
                        <hr style="width:50%;text-align:left;margin-left:0">



                        </div>


                        <hr style="width:50%;text-align:left;margin-left:0">
                    </div>
                </section>
                <!-- About 終了-->
                <section class="resume-section" id="project">
                    <div class="resume-section-content">
                        <h2 class="mb-0">project</h2>
                            <hr class="m-0" />
                                    <div class="col-md-12">

                                        <hr style="width:50%;text-align:left;margin-left:0">
                                        <div class="col-lg-4 col-sm-6 mb-4">
                                            <div class="card h-100">
                                                <img class="img-thumbnail" src="<?= $project_img ?>" alt="">
                                                    <div class="card-body"></div>
                                                        <h4 class="card-title">
                                                            <a href="#"><?= $project_title ?></a>
                                                        </h4>
                                                            <p class="card-text"></p>
                                                    </div>
                                                    <hr style="width:50%;text-align:left;margin-left:0">
                                            </div>
                                        </div>
                                </div>
                        </div>
                </section>
                <!-- project-->
                <!-- <section class="resume-section" id="project">
                    <div class="resume-section-content">
                        <h2 class="mb-7">project</h2>
                            <div class="d-flex flex-column flex-md-row justify-content-between mb-5">
                                <div class="flex-grow-1"></div>
                                    <hr style="width:50%;text-align:left;margin-left:0">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="project-info-box mt-0"></div>
                                            <h4 class="mb-1 line-height-5">プロジェット数<br><?= $project_num ?></h4>
                                                <h5>プロジェット名</h5>
                                                <p class="mb-0">プロジェット説明</p>
                                            </div> project-info-box -->

                                            <!-- <div class="project-info-box">
                                                <p><b>Date:</b> 14.02.2020</p>
                                                <p><b>ツール:</b> 例：bootstap , wordpress</p>
                                                <p class="mb-0"><b>言語</b> php, html, js</p>
                                            </div> project-info-box -->

                                            <!-- <div class="project-info-box mt-0 mb-0">
                                            <p class="mb-0">
                                                <span class="fw-bold mr-10 va-middle hide-mobile">Share:</span>
                                                <a href="#x" class="btn btn-xs btn-facebook btn-circle btn-icon mr-5 mb-0"><i class="fas fa-link"></i>リンク</a>
                                            </p> -->
                                        <!-- </div>/ project-info-box -->
                                    <!-- </div>column -->

                                <!-- <div class="col-md-7">
                                    <img src="https://via.placeholder.com/400x300/FFB6C1/000000" alt="project-image" class="rounded"> -->
                                    <!-- / project-info-box -->
                                <!-- </div>/ column -->
                            <!-- </div> -->
                        <!-- <hr style="width:50%;text-align:left;margin-left:0">
                    </div>
                </section> -->
                <hr class="m-0" />
                <!-- Skills-->
                <section class="resume-section" id="skills">
                    <div class="resume-section-content">
                        <h2 class="mb-5">スキル</h2>
                        <hr style="width:50%;text-align:left;margin-left:0">
                        <h3 class="mb-0">プログラミング言語</h3>
                        <hr style="width:50%;text-align:left;margin-left:0">
                        <ul class="list-inline dev-icons"></ul>
                        <?php
                        foreach ($pro_lans as $row) {
                            echo <<<HTML
                                <h3 class="mb-0">$row[programming_lan]</h3>
                            HTML;
                        }
                        ?>
                        </ul>
                        <hr style="width:50%;text-align:left;margin-left:0">
                        <h3 class="mb-0">ツールスキル</h3>
                        <hr style="width:50%;text-align:left;margin-left:0">
                        <ul class="list-inline dev-icons"></ul>
                        <?php
                        foreach ($tools as $row) {
                            echo <<<HTML
                                <h3 class="mb-0">$row[tool_name]</h3>
                            HTML;
                        }
                        ?>
                        </ul>
                        <!--<div class="subheading mb-3">初心者としての小さなプロジェクト</div>
                        <ul class="fa-ul mb-0">
                            <li>
                                <span class="fa-li"><i class="fas fa-check"></i></span>
                                HTML/CSS課題
                                <a href="http://upcolor.weblike.jp/game-anime/
                                " target="_blank">海外で流行っているゲーム</a></textarea>
                            </li>
                            <li>
                                <span class="fa-li"><i class="fas fa-check"></i></span>
                                PHP/SQL/AJAXデータ入力システム
                                <a href="	http://upcolor.weblike.jp/crudajax/
                                " target="_blank">PHP PDO CRUD with Ajax Jquery and Bootstrap</a></textarea>
                            </li>


                        </ul>
                    </div> -->
                </section>
                <hr class="m-0" />
                <!-- Interests-->
                <section class="resume-section" id="interests">
                    <div class="resume-section-content">
                        <h2 class="mb-5">プログラミングへの関心 &  My INTEREST</h2>
                        <h3 class="mb-0"><?= $introduction ?></h3>

                    </div>
                </section>
                <hr class="m-0" />

            </div>
            <!-- Bootstrap core JS-->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
            <!-- Core theme JS-->
            <script src="../includes/template_1/js/scripts.js"></script>
        </body>
    </html>
    <!-- template 1 終了 -->
<?php
} else if ($template_id == 2) {
?>
    <!-- template 2 開始-->
    <!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>profile_template_2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../includes/template_2/css/style.css" rel="stylesheet" />
</head>
<body>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
<div class="container">
    <!-- user image -->
    <div class="img" style="background-image: linear-gradient(150deg, rgba(63, 174, 255, .3)15%, rgba(63, 174, 255, .3)70%, rgba(63, 174, 255, .3)94%), url(https://i-seifu.com/img/header3.png);
    height: 200px;background-size: cover;"></div>
    <!-- user image -->

    <div class="card social-prof">
        <div class="card-body">
            <div class="wrapper">
                <img src="<?= $profile_img ?>" alt="" class="user-profile">
                <h3><?= $user_name ?></h3>
                <h2><?= $couse_name ?></h2>
            </div>

        </div>
    </div>
    <div class="row">
        <!-- about card -->
        <div class="col-lg-6">
            <div class="card -fluid">
                <div class="card-body info-card social-about">
                    <h2 class="text-blue"><strong>About</strong><i class="fa fa-user ml-2" aria-hidden="true"></i></h2>

                    <div class="row">
                        <div class="col-6">
                            <div class="social-info">
                                <i class="fas fa-user mr-2"></i>
                                <span><?= $user_name ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="social-info">
                                <i class="fas fas fa-envelope mr-2"></i>
                                <span><?= $user_mail ?></span>
                            </div>
                        </div>
                    </div>
                    <h2 class="text-blue mt-4"><strong>資格</strong><i class="fa fa-certificate ml-2" aria-hidden="true"></i></h2>
                    <div class="row">
                        <div class="col-lg-6">
                            <ul>
                                <?php
                                    foreach ($qual as $row) {
                                        echo <<<HTML

                                            <h4><i class="fa fa-mortar-board"></i>$row[qual_name]</h4>
                                        HTML;
                                    }
                                ?>
                            </ul>


                        </div>


                    </div>

                <!-- about card -->
                </div>
            </div>
        </div>
        <!-- skill card -->
        <div class="col-lg-6">
            <div class="card info-card">
                <div class="card-body">
                    <h2 class="text-blue"><strong>Skills</strong><i class="fa fa-code ml-2"></i></h2>
                        <div class="row">
                            <div class="col-lg-6">
                                <ul>
                                    <?php
                                    foreach ($pro_lans as $row) {
                                        echo <<<HTML
                                            <h4>$row[programming_lan]</h4>
                                        HTML;
                                    }
                                    ?>
                                </ul>
                                <h2 class="text-blue"><strong>ツールスキル</strong><i class="fa fa-cogs ml-2" aria-hidden="true"></i></h2>
                                <ul>
                                    <?php
                                    foreach ($tools as $row) {
                                        echo <<<HTML
                                            <h4>$row[tool_name]</h4>
                                        HTML;
                                    }
                                    ?>
                                </ul>
                            </div>
                        </ul>
                    </div>
                </div>
                <!-- skill card-->
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-6">

            <div class="card info-card">
                <div class="card-body">
                    <h2 class="text-blue"><strong>自己紹介</strong><i class="fa fa-bullhorn ml-2" aria-hidden="true"></i></h2>
                    <ul>
                    <div class="row">

                        <h4><?= $introduction ?></h4>
                    </div>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card info-card">
                <div class="card-body">
                    <h2 class="text-blue"><strong>プロジェクト</strong><i class="fa fa-asterisk ml-2" aria-hidden="true"></i></h2>
                    <div class="row">
                    <div class="subheading mb-3">
                    <img class="img-thumbnail" src="<?= $project_img ?>" alt="">
                                                    <div class="card-body"></div>
                                                        <h4 class="card-title">
                                                            <a href="#"><?= $project_title ?></a>
                                                        </h4>
                                                            <p class="card-text"></p>
                                                    </div>
                </div>
                <!-- skill card-->
            </div>
        </div>


    </div>
</div>


<style type="text/css">


/*end social*/
</style>

<script type="text/javascript">

</script>
</body>
</html>
    <!-- template 2 終了 -->


<?php
} else if ($template_id == 3) {
?>
    <!-- template 3 開始-->
    <!DOCTYPE html>
<html lang="ja" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>upcolor</title>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="../includes/template_3/css/theme.css" rel="stylesheet" />

  </head>


  <body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
      <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3 d-block" data-navbar-on-scroll="data-navbar-on-scroll">
        <div class="container"><a class="navbar-brand" href="index.html"><img src="../includes/template_3/img/gallery/upcolor.png" width="118" alt="logo" /></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"> </span></button>
          <div class="collapse navbar-collapse border-top border-lg-0 mt-4 mt-lg-0" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto pt-2 pt-lg-0 font-base">
              <li class="nav-item px-2"><a class="nav-link" aria-current="page" href="#about">About </a></li>
              <li class="nav-item px-2"><a class="nav-link" href="#skills">スキル</a></li>
              <!-- <li class="nav-item px-2"><a class="nav-link" href="#project">プロジェット</a></li> -->
            </ul>
          </div>
        </div>
      </nav>
      <section class="py-xxl-10 pb-0" id="home">
        <div class="bg-holder bg-size" style="background-image:url(../includes/template_3/img/gallery/hero-bg.png);background-position:top center;background-size:cover;">
        </div>
        <!--/.bg-holder-->

        <div class="container">
          <div class="row min-vh-xl-100 min-vh-xxl-25">
            <div class="col-md-5 col-xl-6 col-xxl-7 order-0 order-md-1 text-end">
              <img class="fit-cover rounded-circle w-50" src="<?= $profile_img ?>" alt="hero-header" />
            </div>
            <div class="col-md-75 col-xl-6 col-xxl-5 text-md-start text-center py-6">
              <!--これなに-->
              <p class="fs-1 mb-5"><!--自己紹介--><br /> </p>
              <h1 class="text-center"><?= $user_name ?></h1>
            </div>
          </div>
        </div>
      </section>


      <!-- ============================================-->
      <hr style="width:100%;text-align:center;margin-left:0">
      <!-- プログラミング言語セクション始まり ============================-->
      <section class="py-5" id="skills">
        <div class="container">
          <div class="row">
            <div class="col-12 py-3">

              <!--/.bg-holder-->

              <h1 class="text-center">プログラミング言語</h1>
            </div>
          </div>
        </div>
        <!-- end of .container-->

      </section>
      <!-- <section> close-->

      <!-- <section> begin ============================-->
      <section class="py-0">

        <div class="container">
            <div class="row py-5 align-items-center justify-content-center justify-content-lg-evenly">
                <div class="col-auto col-md-4 col-lg-auto text-xl-start">
                <div class="d-flex flex-column align-items-center">
                <ul class="list-inline dev-icons"></ul>
                        <?php
                        foreach ($pro_lans as $row) {
                            echo <<<HTML
                                <h3 class="mb-0">$row[programming_lan]</h3>
                            HTML;
                        }
                        ?>
                        </ul>
                </div>
                <section class="py-0">
<!-- ツールスキルセクション始まり ============================-->
<hr style="width:100%;text-align:center;margin-left:0">
      <section class="py-5" id="skills">
        <div class="container">
          <div class="row">
            <div class="col-12 py-3">

              <!--/.bg-holder-->

              <h1 class="text-center">ツールスキル</h1>
            </div>
          </div>
        </div>
        <!-- end of .container-->

      </section>
      <!-- <section> close-->

      <!-- <section> begin ============================-->
      <section class="py-0">

        <div class="container">
            <div class="row py-5 align-items-center justify-content-center justify-content-lg-evenly">
                <div class="col-auto col-md-4 col-lg-auto text-xl-start">
                <div class="d-flex flex-column align-items-center">
                <ul class="list-inline dev-icons"></ul>
                        <?php
                        foreach ($pro_lans as $row) {
                            echo <<<HTML
                                <h3 class="mb-0">$row[programming_lan]</h3>
                            HTML;
                        }
                        ?>
                        </ul>

                        <?php

                        foreach ($tools as $row) {
                            echo <<<HTML
                                <h3>$row[tool_name]</h3>
                            HTML;
                        }

                        ?>
                </div>
                <!-- </div>
                <div class="col-auto col-md-4 col-lg-auto text-xl-start">
                <div class="d-flex flex-column align-items-center">
                    <div class="icon-box text-center"><a class="text-decoration-none" href="#!"><img class="mb-3 deparment-icon" src="../includes/template_3/img/icons/html.png" alt="..." />
                        <p class="fs-1 fs-xxl-2 text-center">html</p>
                    </a></div>
                </div>
                </div>
                <div class="col-auto col-md-4 col-lg-auto text-xl-start">
                <div class="d-flex flex-column align-items-center">
                    <div class="icon-box text-center"><a class="text-decoration-none" href="#!"><img class="mb-3 deparment-icon" src="../includes/template_3/img/icons/css.png" alt="..." />
                        <p class="fs-1 fs-xxl-2 text-center">css</p>
                    </a></div>
                </div>
                </div>
                <div class="col-auto col-md-4 col-lg-auto text-xl-start">
                <div class="d-flex flex-column align-items-center">
                    <div class="icon-box text-center"><a class="text-decoration-none" href="#!"><img class="mb-3 deparment-icon" src="../includes/template_3/img/icons/ruby.png" alt="..." />
                        <p class="fs-1 fs-xxl-2 text-center">ruby</p>
                    </a></div>
                </div>
                </div>
                <div class="col-auto col-md-4 col-lg-auto text-xl-start">
                <div class="d-flex flex-column align-items-center">
                    <div class="icon-box text-center"><a class="text-decoration-none" href="#!"><img class="mb-3 deparment-icon" src="../includes/template_3/img/icons/wordpress.png" alt="..." />
                        <p class="fs-1 fs-xxl-2 text-center">wordpress</p>
                    </a></div>
                </div>
                </div> -->
          </div>
        </div>
        <!-- end of .container-->

      </section>
      <!-- <section> close ============================-->
      <hr style="width:100%;text-align:center;margin-left:0">
      <!-- <section> begin ============================-->
      <section class="pb-0" id="about">

        <div class="container">
          <div class="row">
            <div class="col-12 py-3">
              <h1 class="text-center">ABOUT ME</h1>
            </div>
          </div>
        </div>
        <!-- end of .container-->

      </section>
      <!-- <section> close ============================-->
      <!-- ============================================-->


      <section class="py-5">
        <div class="container">
          <div class="row align-items-center">
            <!-- <div class="col-md-6 order-lg-1 mb-5 mb-lg-0"><img class="fit-cover rounded-circle w-100" src="../includes/template_3/img/gallery/nishant.jpg" alt="..." /></div> -->
            <div class="d-flex flex-column align-items-center">
              <h2 class="fw-bold mb-4"><?= $couse_name ?></h2>
              <h3><?= $introduction ?></h3>

            </div>
          </div>
        </div>
      </section>






      <!-- ============================================-->
      <!-- <section> begin ============================-->
      <section class="py-5" id="project">

        <div class="container">
          <div class="row justify-content-center">
          <div class="col-lg-4 col-sm-6 mb-4">
                 <div class="card h-100">
                    <img class="img-thumbnail" src="<?= $project_img ?>" alt="">
                        <div class="card-body"></div>
                            <h4 class="text-center card-title">
                                <a href="#"><?= $project_title ?></a>
                            </h4>
                                <p class="card-text"></p>
                            </div>

                        </div>
                    </div>
            </div>
          </div>
        </div>
        <!-- end of .container-->

      </section>
      <!-- <section> close ============================-->
      <!-- ============================================-->


      <!-- <section>
        <div class="container">
          <div class="row">
            <div class="col-sm-6 col-lg-3 mb-4">
              <div class="card h-100 shadow card-span rounded-3"><img class="card-img-top rounded-top-3" src="../includes/template_3/img/gallery/muro.jpg" alt="news" />
                <h5 class="font-base fs-lg-0 fs-xl-1 my-3">COVID-19: The Most Up-to-Date Information</h5><a class="stretched-link" href="#!">read full article</a>
              </div>
            </div>
          </div>
        </div>
      </section> -->

    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <!-- <script src="../includes/template_3/js/popper.min.js"></script> -->
    <!-- <script src="../includes/template_3/js/bootstrap.min.js"></script> -->
    <!-- <script src="../includes/template_3/js/is.min.js"></script> -->
    <!-- <script src="../includes/template_3/js/all.min.js"></script> -->
    <script src="https://scripts.sirv.com/sirvjs/v3/sirv.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="../includes/template_3/js/theme.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fjalla+One&amp;family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100&amp;display=swap" rel="stylesheet">
</body>
</html>

    <!-- template 3 終了 -->
<?php
}
?>
