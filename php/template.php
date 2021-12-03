<?php
session_start();
require_once('config.php');
require_once('function.php');

$profile_img = $user_name = $user_mail = $couse_name = $github_link = $github_name = '';

$s = <<<SQL
    SELECT *
    FROM accounts
                LEFT OUTER JOIN couses
                    ON accounts.couse_id=couses.couse_id
                LEFT OUTER JOIN programming_lans
                    ON accounts.user_id=programming_lans.user_id
                LEFT OUTER JOIN portfolios
                    ON accounts.user_id=portfolios.user_id
        WHERE accounts.user_name=?
SQL;
$sql = $pdo -> prepare($s);
$sql -> execute([$_SERVER['QUERY_STRING']]);

foreach ($sql as $row) {
    $profile_img = $row['profile_img'];
    $user_name = $row['user_name'];
    $user_mail = $row['user_mail'];
    $couse_name = $row['couse_name'];
    $pro_lans[] = $row['programming_lan'];
    $github_link = $row['github_account'];
    $template_id = $row['template_id'];
                                                //自己紹介変数
                                                //プロジェット数変数
                                                //資格変数
                                                //自己紹介変数
}

if ($template_id == 1) {
?>
    <!-- template 1 開始-->
    <!DOCTYPE html>
    <html lang="ja">
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
            <meta name="description" content="" />
            <meta name="author" content="" />
            <title><?= $user_name ?></title>
            <link rel="icon" type="image/x-icon" href="favicon.ico" />
            <!-- Font Awesome icons (free version)-->
            <script src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" crossorigin="anonymous"></script>
            <!-- Google fonts-->
            <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet" type="text/css" />
            <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i" rel="stylesheet" type="text/css" />
            <!-- Core theme CSS (includes Bootstrap)-->
            <link href="includes/template_1/css/styles.css" rel="stylesheet" />
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
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#project">プロジェット</a></li>
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
                        <h2 class="lead mb-5"><?= $couse_name ?></h2>
                        <div class="social-icons">
                            <!--<a class="social-icon" href="https://www.linkedin.com/in/nishant-meher-2a2288137/"><i class="fab fa-linkedin-in"></i></a>-->
                            <a class="social-icon" href="https://github.com/0Neokun0"><i class="fab fa-github"></i></a>
                            <!--<a class="social-icon" href="https://twitter.com/396466477dab4a8"><i class="fab fa-twitter"></i></a>-->
                        </div>
                        <h3 class = "mb-0">Test of English for International Communication (TOEIC)</h3>
                        <div class="subheading mb-3">905/990</div>
                        <h3 class = "mb-0">情報検定-J検2級</h3>
                        <div class="subheading mb-3">66/100-合格
                        </div>
                        <h3 class = "mb-0">情報検定-J検3級</h3>
                        <div class="subheading mb-3">87/100-合格</div>
                        <hr style="width:50%;text-align:left;margin-left:0">
                    </div>
                </section>
                <!-- About 終了-->
                <hr class="m-0" />
                <!-- project-->
                <section class="resume-section" id="project">
                    <div class="resume-section-content">
                        <h2 class="mb-7">project</h2>
                            <div class="d-flex flex-column flex-md-row justify-content-between mb-5">
                                <div class="flex-grow-1">
                                    <hr style="width:50%;text-align:left;margin-left:0">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="project-info-box mt-0"></div>
                                                <h5>プロジェット名</h5>
                                                <p class="mb-0">プロジェット説明</p>
                                            </div><!-- / project-info-box -->

                                            <div class="project-info-box">
                                                <p><b>Date:</b> 14.02.2020</p>
                                                <p><b>ツール:</b> 例：bootstap , wordpress</p>
                                                <p class="mb-0"><b>言語</b> php, html, js</p>
                                            </div><!-- / project-info-box -->

                                            <div class="project-info-box mt-0 mb-0">
                                            <p class="mb-0">
                                                <span class="fw-bold mr-10 va-middle hide-mobile">Share:</span>
                                                <a href="#x" class="btn btn-xs btn-facebook btn-circle btn-icon mr-5 mb-0"><i class="fas fa-link"></i>リンク</a>
                                            </p>
                                        </div><!-- / project-info-box -->
                                    </div><!-- / column -->

                                <div class="col-md-7">
                                    <img src="https://via.placeholder.com/400x300/FFB6C1/000000" alt="project-image" class="rounded">
                                    <!-- / project-info-box -->
                                </div><!-- / column -->
                            </div>
                        <hr style="width:50%;text-align:left;margin-left:0">
                    </div>
                </section>
                <hr class="m-0" />
                <!-- Skills-->
                <section class="resume-section" id="skills">
                    <div class="resume-section-content">
                        <h2 class="mb-5">スキル</h2>
                        <hr style="width:50%;text-align:left;margin-left:0">
                        <div class="subheading mb-3">プログラミング言語とツール</div>
                        <ul class="list-inline dev-icons"></ul>
                        <?php
                        foreach ($pro_lans as $row) {
                            echo <<<HTML
                                <li class="list-inline-item">$row</li>
                            HTML;
                        }
                        ?>
                        </ul>
                        <hr style="width:50%;text-align:left;margin-left:0">
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
                        <p>私は、何かを作成し、協力して問題の解決策の一部となるために、日本語翻訳者としてのキャリアを選択しますが、Webアプリケーション開発者キャリアパスは、急速に変化するテクノロジーを使用して、企業問題解決に貢献する機会を与えてくれます。 私は日本語の翻訳者であるだけでなく、コンピューターをプログラミングするための新しい技術を学び、発見することを楽しんでいます。</p>
                        <p class="mb-0">When I am indoors I like to watch documetaries, and meditate, I was an active Youtube Video Creater too, I wish to continue it if i  get some spare time, I like to go to gym and keep my self fit, and I spend a large amount of my free time exploring the latest technology advancements in the front-end web development world.</p>
                    </div>
                </section>
                <hr class="m-0" />

            </div>
            <!-- Bootstrap core JS-->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
            <!-- Core theme JS-->
            <script src="includes/template_1/js/scripts.js"></script>
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
    <link href="includes/template_2/css/styles.css" rel="stylesheet" />
</head>
<body>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
<div class="container">
    <!-- user image -->
    <div class="img" style="background-image: linear-gradient(150deg, rgba(63, 174, 255, .3)15%, rgba(63, 174, 255, .3)70%, rgba(63, 174, 255, .3)94%), url(https://bootdey.com/img/Content/flores-amarillas-wallpaper.jpeg);
    height: 350px;background-size: cover;"></div>
    <!-- user image -->

    <div class="card social-prof">
        <div class="card-body">
            <div class="wrapper">
                <img src="<?= $profile_img ?>" alt="" class="user-profile">
                <h3><?= $user_name ?></h3>
                <p>Web Developer</p>
            </div>

        </div>
    </div>
    <div class="row">
        <!-- about card -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body info-card social-about">
                    <h2 class="text-blue">About</h2>




                    <div class="row">
                        <div class="col-6">
                            <div class="social-info">
                                <i class="fas fa-user"></i>
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
                <!-- about card -->
                </div>
            </div>
        </div>
        <!-- skill card -->
        <div class="col-lg-6">
            <div class="card info-card">
                <div class="card-body">
                    <h2 class="text-blue">Skills</h2>
                    <div class="row">
                    <div class="subheading mb-3">プログラミング言語とツール</div>
                    <ul class="list-inline dev-icons"></ul>
                                <?php
                                foreach ($pro_lans as $row) {
                                    echo <<<HTML
                                        <li class="list-inline-item">$row</li>
                                    HTML;
                                }
                                ?>
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
                    <h2 class="text-blue">資格</h2>
                    <div class="row">
                        <div class="col-lg-6">
                            <h4><strong>情報検定2級 </strong></h4>

                        </div>
                        <div class="col-lg-6">
                            <h4><strong>i.tパスポート</strong></h4>

                        </div>
                        <div class="col-lg-6">
                            <h4><strong>応用情報試験</strong></h4>

                        </div>
                        <div class="col-lg-6">
                            <h4><strong>Oracle - Gold</strong></h4>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card info-card">
                <div class="card-body">
                    <h2 class="text-blue">自己紹介</h2>
                    <div class="row">
                    <p>私は、何かを作成し、協力して問題の解決策の一部となるために、日本語翻訳者としてのキャリアを選択しますが、Webアプリケーション開発者キャリアパスは、急速に変化するテクノロジーを使用して、企業問題解決に貢献する機会を与えてくれます。 私は日本語の翻訳者であるだけでなく、コンピューターをプログラミングするための新しい技術を学び、発見することを楽しんでいます。</p>
                        <p class="mb-0">When I am indoors I like to watch documetaries, and meditate, I was an active Youtube Video Creater too, I wish to continue it if i  get some spare time, I like to go to gym and keep my self fit, and I spend a large amount of my free time exploring the latest technology advancements in the front-end web development world.</p>
                    </div>
                </div>
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

    <!-- template 3 終了 -->
<?php
}
?>
