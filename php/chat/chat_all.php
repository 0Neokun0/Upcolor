<?php
session_start();
require_once('../config.php');
require_once('../function.php');


$select_chat_all = <<<SQL
    SELECT chat_text, accounts.user_id, accounts.user_name, accounts.profile_img
    FROM chat
            INNER JOIN accounts
                ON chat.user_id=accounts.user_id OR chat.receiver_id=accounts.user_id
    WHERE chat_id IN (
            SELECT MAX(chat_id)
            FROM chat
                    LEFT JOIN accounts
                        ON chat.user_id=accounts.user_id OR chat.receiver_id=accounts.user_id
            WHERE chat.user_id=? OR chat.receiver_id=?
            GROUP BY accounts.user_id
            ) AND accounts.user_id!=?
    ORDER BY chat_id DESC
SQL;
$chat_user_select = $pdo -> prepare($select_chat_all);
$chat_user_select -> execute([ID, ID, ID]);

require_once('../header.php');
require_once('../navbar.php');

?>
<head>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../includes/css/chat_all.css">
</head>
<div class="container mt-5">
    <div class="row clickable-row">
<?php
foreach ($chat_user_select as $row) {
    echo <<<HTML
    <!-- <div class="col-md-4 mt-4">
        <form action="chat_input.php" method="post">
            <button type="submit">
            <input type="hidden" name="receiver_id" value="$row[user_id]">
            <img src="$row[profile_img]">
            <p>$row[user_name]</p>
            <p>$row[chat_text]</p>
            </button>
        </form> -->


        <div class="col-md-6 col-lg-4 col-xl-3 mt-5">
            <form action="chat_input.php" method="post">
                <button type="submit">
                    <input type="hidden" name="receiver_id" value="$row[user_id]">
                        <div class="card profile-card-5 text-break">
                            <div class="card-img-block" style="height: 200px">
                                <img class="card-img-top" src="$row[profile_img]" alt="Card image cap" style="width: 200px;">
                            </div>
                        <div class="card-body pt-0">
                            <h5 class="card-title">$row[user_name]</h5>
                            <small><i class="fa fa-reorder"></i>&nbsp;最新メッセージ</small>
                            <p class="card-text"><strong>$row[chat_text]</strong></p>
                        </div>
                    </div>
                </button>
            </form>
        </div>

    HTML;
}
?>
    </div>
</div>
