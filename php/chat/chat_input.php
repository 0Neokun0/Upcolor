<?php
session_start();
require_once('../config.php');
require_once('../function.php');

$receiver_id = $_POST['receiver_id'];
$select_name = <<<SQL
    SELECT user_name
    FROM accounts
    WHERE user_id=?
SQL;
$sql = $pdo -> prepare($select_name);
$sql -> execute([$receiver_id]);
$user_info = $sql -> fetch(PDO::FETCH_ASSOC);

require_once('../header.php');
?>
<!-- <!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <title>Chat App</title>
</head>

<body> -->

  <?php
  require_once('../navbar.php');
  ?>
  <link rel="stylesheet" href="../../includes/css/chat_input.css">
  <div class="container mt-5">
    <div class="jumbotron">
      <h1 class="display-4"><?= $user_info['user_name'] ?></h1>

    </div>

    <div class="container-fluid bg-light" id="chat">
      <div class="content d-flex flex-column" id="chat-content">

      </div>
      <form class="tools form-row" method="POST" onsubmit="send_message(); return false;">
        <input type="hidden" id="receiver_id" value="<?= $receiver_id ?>">
        <input type="text" id="chat_text" class="form-control col mr-2" placeholder="Message">
        <div class="emoji-list" id="emoji-list">

        </div>

        <button class="btn btn-primary" type="submit">
          送信
        </button>
      </form>
    </div>
  </div>



<?php require_once('../footer.php'); ?>

<script text="text/javascript">
    $(function () {
        read_message();
        setInterval('read_message()', 3000);
    });

</script>
