<?php
require './man.function.php';
include("./config.php");
use Meayair\MeaWords;
$MeaWords = new MeaWords();
if(!$MeaWords->login_check($config)){
    header('Location: login.php');
}
$wid = time();
$numperpage=20;
$page = empty($_GET['page'])?"1":$_GET['page'];
$words_path = "./data/words.json";
$words_list = $MeaWords->word_list_get($page,$words_path,$numperpage);
foreach ($words_list as $k=>$value) {
            $trs .='<tr>
                                            <td>'.$value['source'].'</td>
                                            <td>'.$value['word'].'</td>
                                            <td>
                                                <div class="am-btn-toolbar">
                                                    <div class="am-btn-group am-btn-group-xs">
                                                        <a href="posts.php?do=renew&wid='.$k.'" class="am-btn am-btn-default am-btn-xs am-text-secondary"><span class="am-icon-pencil-square-o"></span> 编辑</a>
                                                        <a href="posts.php?do=delete&wid='.$k.'" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"><span class="am-icon-trash-o"></span> 删除</a></a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        ';
    // code...
}
$pageAll = ceil(count($words_list)/$numperpage);
for($i=1;$i<=$pageAll;$i++){
    $lis .= '<li class="am-active"><a href="?page='.$i.'">'.$i.'</a></li>';
}
include 'header.php';
?>
<div class="tpl-page-container tpl-page-header-fixed">
<?php
include 'nav.php';
?>
<div class="tpl-content-wrapper">
            <div class="tpl-portlet-components">
                <div class="portlet-title">
                    <div class="caption font-green bold">
                        <span class="am-icon-code"></span> 句子列表
                    </div>
                    <div class="am-btn-group am-btn-group-xs" style="padding:5px 0 0 25px">
                    <button type="button" class="am-btn am-btn-default am-btn-secondary"><span class="am-icon-plus"></span> <a href="posts.php" style="color:#fff">新增</a></button>
                    </div>

                </div>
                <div class="tpl-block">
                    
                    <div class="am-g">
                        <div class="am-u-sm-12">
                                <table class="am-table am-table-striped am-table-hover table-main">
                                    <thead>
                                        <tr>
                                            <th class="table-source" style="width:24%">出处</th>
                                            <th class="table-word" style="width:52%">句子</th>
                                            <th class="table-set" style="width:24%">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?echo $trs?>
                                        
                                    </tbody>
                                </table>
                                <hr>

                        </div>

                    </div>
                </div>
                <div class="am-cf">

                                    <div class="am-fr">
                                        <ul class="am-pagination tpl-pagination">
                                            <?echo $lis;?>
                                        </ul>
                                    </div>
                                </div>
            </div>










        </div>

</div>
<?php
include 'footer.php';
?>