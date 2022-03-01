<?php
session_start();
unset($_SESSION['account']);
header('Location: home/index.php');
?>