<?php
session_start();
require_once('../config.php');
require_once('../function.php');

if (isset($_GET['couse'])) {
    $s = <<<SQL
        SELECT user_id, user_name, profile_img, bg_color, release_flg
        FROM accounts
        WHERE couse_id=?
    SQL;
    $sql = $pdo -> prepare($s);
    $sql -> execute([$_GET['couse']]);
} else if (isset($_GET['favorite'])) {
    $s = <<<SQL
        SELECT user_id, user_name, profile_img, bg_color, release_flg
        FROM accounts
        WHERE user_id IN (SELECT favorited_id FROM favorite WHERE user_id=?)
    SQL;
    $sql = $pdo -> prepare($s);
    $sql -> execute([ID]);
} else if (isset($_POST['search'])) {
    $s = <<<SQL
        SELECT user_id, user_name, profile_img, bg_color, release_flg
        FROM accounts
        WHERE user_name like ?
    SQL;
    $sql = $pdo -> prepare($s);
    $sql -> execute(['%'.$_POST['search'].'%']);
} else {
    $s = <<<SQL
        SELECT user_id, user_name, profile_img, bg_color, release_flg
        FROM accounts
    SQL;
    $sql = $pdo -> prepare($s);
    $sql -> execute([]);
}

// 表示順番のシャッフル
$thumbnail = $sql -> fetchAll(PDO::FETCH_ASSOC);
shuffle($thumbnail);

require_once('../header.php');
require_once('../navbar.php');
?>
<link rel="stylesheet" href="../../includes/css/profile_thumbnail.css">
<div class="container mt-5">
        <div class="row clickable-row">
            <?php
            foreach ($thumbnail as $row) {
                if ($row['user_id'] != ID && $row['release_flg']) {
                    echo <<<HTML
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <!-- カードスタート -->
                            <a href = "./profile_view.php?name=$row[user_name]">
                                <div class="profile-card-2" style="width: 18rem; background: $row[bg_color];">
                                    <div class="d-flex align-items-center trim" style="height: 18rem;">
                                        <img src="$row[profile_img]" class="img img-responsive mx-auto my-auto" style="max-width: 17rem; max-height:17rem; border-radius: 50%;">
                                    </div>
                                    <div class="profile-name">$row[user_name]</div>
                                </div>
                            </a>
                        <!-- カードエンド -->
                        </div>
                    HTML;
                }
            }
            ?>
        </div>
    </div>
</div>




<?php require_once('../footer.php'); ?>