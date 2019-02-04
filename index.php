<?php
require './man.function.php';
include("./config.php");
use Meayair\MeaWords;
$MeaWords = new MeaWords();
if(!$MeaWords->login_check($config)){
    header('Location: login.php');
}
$wid = time();
$page = 1;
$words_path = "./data/words.json";
include 'header.php';
$baseurl = str_ireplace("index.php","",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
?>
<div class="tpl-page-container tpl-page-header-fixed">
    <?php include 'nav.php';?>
    <div class="tpl-content-wrapper">
        <div class="tpl-portlet-components">
            <div class="portlet-title">
                <div class="caption font-green bold">
                    <span class="am-icon-code"></span> 首页
                </div>
            </div>
            <div class="tpl-block">
                <div class="am-g">
                    <div class="am-u-sm-12">
                        <div class="note note-info">
                            <h3>Meayair 一句话服务
                                <span class="close" data-close="note"></span>
                            </h3>
                            <br><br>
                            <p>
                                API  地址 ：<span class="label label-danger"><?php $api=$baseurl.'getword.php'; echo"<a style=\"color:#fff\" href=\"$api\" target=\"_blank\">$api</a>"?></span>
                            </p>
                            <br>
                            <p>
                                演示地址：<span class="label label-danger"><?php $link=$baseurl.'demo.php'; echo"<a style=\"color:#fff\" href=\"$link\" target=\"_blank\">$link</a>"?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tpl-alert"></div>
        </div>
    </div>
</div>
<?php include 'footer.php';?>