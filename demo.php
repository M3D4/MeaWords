
<!doctype html>
<html>

	<head>
		<!--[if lte IE 9]><script>window.location.href='update.html';</script><![endif]-->
		<meta charset="UTF-8" />
        <meta name="keywords" content="米说使用DEMO" />
        <meta name="description" content="米说使用DEMO" />
		<title>米说使用DEMO</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<script src="https://cdn.staticfile.org/jquery/3.3.1/jquery.min.js"></script>
		<style>
		.s-skin-container {
    position: fixed;
    _position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    min-width: 1000px;
    z-index: -10;
    background-position: center 0;
    background-repeat: no-repeat;
    background-size: cover;
    -webkit-background-size: cover;
    -o-background-size: cover;
    zoom: 1;
}
#search-container {
    top: 25%;
    left: 500px;
}
#search-container .search-logo {
    margin: 0;
}
.search-logo{
    float: left;
}
#search-container .search-hot {
    margin-top: 80px;
    width: 676px;
}
#search-container .search-hot ul li {
    float: left;
}
.lines{
    position: absolute;
    top: 72%;
    left: 10%;
    width: 80%;
    font-size: 22px;
    
}
.lines a{
    color:white;
    text-decoration:none;
}
.lines p.title{
    float:right;
    font-size: 20px;
    margin-right: 50px;
}
		</style>
	</head>
	<body>
	    <div class="s-skin-container s-isindex-wrap" style="background-color:rgb(64, 64, 64);background-image:url(http://www.meayair.com/pic/?s=large&r=img);">  </div>

		<div class="lines">
			<p><a id="word"></a></p><br>
			<p class="title"><a id="source"></a></p>
		</div>
        <script type="text/javascript">
    $(function(){
        setTimeout(initData(),10000);
    });

    //加载初始化数据
    function initData(){
        $.ajax({
            type:'POST',
            dataType: "json",
            url:"<?php echo str_replace("demo.php","getword.php",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>",
            success:function(res){
                    $("#source").text("——『 "+res.source+" 』");
                    $('#word').text(res.word);
            },
            error:function(){
                alert("数据加载发生错误！");
            }
        });
    }
</script>
	</body>

</html>
