<?php
require './man.function.php';
include("./config.php");
use Meayair\MeaWords;
$MeaWords = new MeaWords();
if(!$MeaWords->login_check($config)){
    header('Location: login.php');
}
$words_path = "./data/words.json";
@$do = empty($_GET['do'])?"new":$_GET['do'];
include 'header.php';
$doname = "新增";
$source_out = "";
$word_out = "";
switch ($do) {
    case 'renew':
        $doname = "编辑";// code...
        if (empty($_GET['wid'])&&$_GET['wid']!="0")exit('Something Wrong!');
        $words_arr = json_decode($MeaWords->file_read_safe($words_path),true);
        $source_out = 'value = "'.$words_arr[$_GET['wid']]['source'].'"';
        $word_out = $words_arr[$_GET['wid']]['word'];
        break;
    
    case 'delete':
        $doname = "删除";// code...
        if (empty($_GET['wid'])&&$_GET['wid']!="0")exit('Something Wrong!');
        $words_arr = json_decode($MeaWords->file_read_safe($words_path),true);
        $source_out = 'value = "'.$words_arr[$_GET['wid']]['source'].'" disabled';
        $word_out = $words_arr[$_GET['wid']]['word'];
        $word_delete = 'disabled';
        break;
        
    default:
        // code...
        break;
}
?>
<div class="tpl-page-container tpl-page-header-fixed">
<?php
include 'nav.php';
?>

<div class="tpl-content-wrapper">
            <div class="tpl-portlet-components">
                <div class="portlet-title">
                    <div class="caption font-green bold">
                        <span class="am-icon-code"></span> <?echo $doname?>句子
                    </div>

                </div>
                <div class="tpl-block">

                    <div class="am-g">
                        <div class="tpl-form-body tpl-form-line">
                            <form class="am-form tpl-form-line-form" action="posts-edit.php" method="post">
                                <input type="text" class="tpl-form-input" name="do" value="<?echo $do?>" required style="display:none">
                                <?php echo ($do=="renew"||$do=="delete")?'<input type="text" class="tpl-form-input" name="wid" value="'.$_GET['wid'].'" required style="display:none">':""?>
                                <div class="am-form-group">
                                    <label for="user-intro" class="am-u-sm-3 am-form-label">句子</label>
                                    <div class="am-u-sm-9">
                                        <textarea class="" rows="4" placeholder="请输入句子" name="word" <?echo ($do=="delete")?"disabled":""?> required><?echo $word_out?></textarea>
                                    </div>
                                </div>
                                
                                <div class="am-form-group">
                                    <label for="user-name" class="am-u-sm-3 am-form-label">出处 </label>
                                    <div class="am-u-sm-9">
                                        <input type="text" class="tpl-form-input" name="source" placeholder="请输入出处" <?echo $source_out?> required>
                                    </div>
                                </div>

                                <div class="am-form-group">
                                    <div class="am-u-sm-9 am-u-sm-push-3">
                                        <button type="submit" class="am-btn am-btn-primary tpl-btn-bg-color-success "><?echo ($do=="delete")?"确认删除":"提交"?></button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>










        </div>

</div>
<?php
include 'footer.php';
?>