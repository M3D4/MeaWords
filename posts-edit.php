<?php
require './man.function.php';
include("./config.php");
use Meayair\MeaWords;
$MeaWords = new MeaWords();
if(!$MeaWords->login_check($config)){
    header('Location: login.php');
}
$words_path = "./data/words.json";
switch ($_POST['do']) {
    case 'new':
        $wid = time();
        $source = $_POST['source'];
        $word = $_POST['word'];
        $MeaWords->word_new($wid,$source,$word,$words_path);
        $output = "已新增句子";
        break;
    case 'renew':
        $wid = $_POST['wid'];
        $source = $_POST['source'];
        $word = $_POST['word'];
        $MeaWords->word_new($wid,$source,$word,$words_path);
        $output = "已修改句子";
        break;
    case 'delete':
        $wid = $_POST['wid'];
        $MeaWords->word_delete($wid,$words_path);
        $output = "已删除句子";
        break;

    default:
        // code...
        break;
}
include 'header.php';
?>
<div class="tpl-page-container tpl-page-header-fixed">
<?php
include 'nav.php';
?>

<div class="tpl-content-wrapper">
            <div class="tpl-portlet-components">
                <div class="portlet-title" style="border-bottom: 0px solid;">
                    <div class="caption font-green bold">
                        <span class="am-icon-code"></span> <?echo $output?>
                    </div>

                </div>

            </div>










        </div>

</div>
<script type="text/javascript">
		onload=function(){
			setInterval(go, 1000);
		};
		var x=1; //利用了全局变量来执行
		function go(){
		x--;
			if(x<=0){
			location.href='posts-manage.php';
			}
		}
	</script>
<?php
include 'footer.php';
?>