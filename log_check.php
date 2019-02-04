<?php
require './man.function.php';
use Meayair\MeaWords;
$MeaWords = new MeaWords();
$row = array(
    "username" => md5("admin"),
    "password" => md5("1234")
);
if($MeaWords->login_check($row)){
    header('Location: index.php');
}
else header('Location: login.php');