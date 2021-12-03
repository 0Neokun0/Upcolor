<?php
$text = '';

//プロファイルとリンク、ログアウト、ログイン会員登録のテキスト表示
if (basename($_SERVER["REQUEST_URI"]) == 'profile-edit.php') {
    $text = <<<HTML
            <li><a herf="URL/../../profile/logout.php">ログアウト</a></li>
    HTML;
} else if (empty($_SESSION['account'])) {
    $text = <<<HTML
            <li><a href="URL/../../signIn/signIn.php">ログイン</a></li>
            <li><a href="URL/../../signUp/signUp.php">会員登録</a></li>
    HTML;

            //データベースからユーザーIDを取得
} else {
    $s = <<<SQL
        SELECT couse_id
        FROM accounts
        WHERE user_id=?
    SQL;
    //実行準備
    $sql = $pdo -> prepare($s);
    $sql -> execute([ID]);
    $sql = $sql -> fetch(PDO::FETCH_ASSOC);
 //mondai ten
    //プロフィール登録、編集、ログアウトのテキスト表示

    if (!$sql['couse_id']) {
        $text = <<<HTML
                <button class="btn btn-outline-primary me-md-2" onclick="location.href='URL/../../profile/profile-edit.php'" type="button">プロフィールを登録</button>
                <li><a href="URL/../../profile/profile-edit.php">プロフィールを編集</a></li>
                <li><a href="URL/../../profile/logout.php">ログアウト</a></li>
        HTML;
    } else if(basename($_SESVER["REQUEST_URI"]) == 'userprofile.php') {
        $text = <<<HTML
                <li><a href="URL/../../profile/profile-edit.php">プロフィールを編集</a></li>
                <li><a href="URL/../../profile/logout.php">ログアウト</a></li>
        HTML;
    } else {
        $login = $_SESSION['account']['user_name'];
        $text = <<<HTML
                <li><a href="URL/../../profile/userprofile.php">$login</a></li>
                <li><a href="URL/../../profile/profile-edit.php">プロフィールを編集</a></li>
                <li><a href="URL/../../profile/logout.php">ログアウト</a></li>
        HTML;
    }
}

?>







<nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">UPCOLOR</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li class="active">
                <a href="#">HOME</a>
                </li>
                <li>
              <a href="#">Link</a>
                </li>
                <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">専攻<b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li>
                <button type="submit" name="couse" class="btn btn-link" value="1">本科</button>
                </li>
                <li class="divider"></li>
                <li>
                <button type="submit" name="couse" class="btn btn-link" value="2">情報処理ネットワーク専攻</button>
                </li>
                <li>
                <button type="submit" name="couse" class="btn btn-link" value="3">ゲーム専攻</button></li>
                </li>

                <li>
                <button type="submit" name="couse" class="btn btn-link" value="4">デザイン専攻</button>
                </li>

                <li>
                <li><button type="submit" name="couse" class="btn btn-link" value="5">ハードウェア専攻</button>
                </li>
              </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-left" role="search">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="ユーザープロフィール" />
            </div>
            <button type="submit" class="btn btn-primary btn-transparent">検索</button>
          </form>
          <ul class="nav navbar-nav navbar-right">
            <li>
              <a href="#">Link</a>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo '$row[profile_img]'?><b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="#">プロファイル編集</a>
                </li>
                <li>
                  <a href="#"></a>
                </li>
                <li>
                  <a href="#">プロファイル-About</a>
                </li>
                <li class="divider"></li>
                <li>
                  <a href="#">ログアウト</a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
    </nav>