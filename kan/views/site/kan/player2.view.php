<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="/styles/kan/css/globe.css" />
<script type="text/javascript"  src="/styles/kan/js/jquery.min.js" ></script>
<title>Insert title here</title>
<style>
.navi_list .top_search .ts_txt,.navi_list .top_search .ts_btn,#info_bar .logo_jia,#info_bar .logo_jie,.info_show .my_btn li,.info_show .my_btn .libg,.navi_list .split,.login_methods .regist,.login_methods .login,.login_methods .more_login b,.info_show .my_btn .upload_btn,.info_show .mb_name
	{
	background: url("/styles/kan/images/header.png") no-repeat scroll 0 0
		transparent;
}

#info_bar .logo_jia {
	background-position: right -700px;
	display: block;
	float: left;
	height: 60px;
	text-indent: -9999px;
	width: 140px;
}

#info_bar {
	overflow: hidden;
	zoom: 1;
	height: 60px;
	margin: 18px auto;
}

.fm960 {
	margin: 0 auto;
	width: 960px;
}

#navigation {
	height: 40px;
	line-height: 37px;
	margin-bottom: 0;
}

.navi_list {
	width: 100%;
	background: rgb(143, 198, 59);
	height: 40px;
}

.navi_list a {
	color: #FFFFFF;
	font-size: 14px;
	font-weight: bold;
	line-height: 40px;
	margin: 0 2px;
}

.navi_list li {
	float: left;
	padding: 0 20px;
	height: 40px;
}

.navi_list .split {
	background-position: -299px -450px;
	padding: 0 20px;
}

.navi_list .c {
	background: none repeat scroll 0 0 #00461A;
	margin-right: -1px;
	position: relative;
}

.navi_list .top_search {
	height: 25px;
	margin: 8px 10px 0 0;
	padding: 0;
	float: right;
}

.navi_list .top_search .ts_txt {
	width: 170px;
	height: 21px;
	line-height: 21px;
	background-color: white;
	background-position: -278px -251px;
	border: none;
	border-top: 1px solid rgb(116, 171, 49);
	border-bottom: 1px solid rgb(116, 171, 49);
	padding-left: 25px;
	color: rgb(153, 153, 153);
	float: left;
}

.navi_list .top_search .ts_btn {
	width: 51px;
	height: 23px;
	border: none;
	background-position: right -300px;
	cursor: pointer;
	float: left;
}

#searching {
	background: none repeat scroll 0 0 #F8F8F8;
}

#searching .searching-bd {
	padding: 8px;
	overflow: hidden;
	width: 100%;
}

#searching .searching-bd ul {
	font-size: 12px;
	overflow: hidden;
	position: relative;
}

#searching .searching-bd li {
	float: left;
	margin: 5px 0;
	padding-right: 13px;
}

#searching .searching-ft {
	position: relative;
	padding: 8px;
}

#searching .searching-ft .tab {
	font-size: 14px;
	line-height: 18px;
}

#searching .searching-ft .tab .on {
	color: #FFFFFF;
	padding: 2px 5px;
}

#searching .searching-ft .tab a {
	vertical-align: middle;
}

#searching a.on {
	background: none repeat scroll 0 0 #8FC63B;
	color: #FFFFFF;
}

#searching a {
	height: 16px;
	overflow: hidden;
	padding: 2px;
}
</style>
</head>
<body>

	<div id="header">


		<div id="info_bar" class="fm960">
			<div class="logo_show fl">
				<a href="/" class="logo_jia" title="">蘑菇家</a>
			</div>

		</div>

		<div id="navigation" class="fm960">
			<ul class="navi_list fl">

				<li><a href="/">首页</a></li>

				<li class="split "><a href="<?php echo $this->createUrl('site','kan','movie');?>">电影</a></li>

				<li class="split c"><a href="<?php echo $this->createUrl('site','kan','tv');?>">电视剧</a></li>

				<li class="split "><a href="<?php echo $this->createUrl('site','kan','cartoon');?>">动漫</a></li>

				<li class="split "><a href="/bedroom/">综艺</a></li>

				<li class="mogu_search top_search" id="top_search_form">
					<form target="_blank" action="/search/" method="get"
						id="search_form">
						<input type="text" name="q" class="ts_txt fl"
							value="搜电影，电视剧，动漫，综艺" autocomplete="off" def-val="搜宝贝、找人"
							style="color: rgb(204, 204, 204);"> <input type="submit"
							value=" " class="ts_btn"> <input type="hidden" name="t" value="9"
							id="select_type">
					</form>
				</li>
			</ul>
		</div>
	</div>
	<style>
	.flv_player {
		margin-top:15px;
	}
	</style>
	<div class="flv_player fm960 clearfix" id="flv_player_now" <?php if($autoplay != true){?> style="display:none" <?php }?>>
	<embed src="http://www.tudou.com/a/gW4f8tlJ3pQ/&resourceId=0_05_05_99&iid=123233052&bid=05/v.swf"
		 type="application/x-shockwave-flash"
		  allowscriptaccess="always" allowfullscreen="true" wmode="opaque" width="960" height="500"></embed>
	</div>
	<style>
	
	.detail{
		margin-top:10px;
	}
	.detail .left_detail {
		width:255px;
		float:left;
	}
	.detail .right_detail {
		margin-left:20px;
		width:685px;
		float:left;
	}
	
	</style>
	<div class="detail fm960 clearfix">
		<div class="left_detail">
			<div class="poster">
				<a href=""><img style="width:230px;height:307px"  src="<?php echo $vgroup->getImageLink();?>"></a>
			</div>
		</div>
		<div class="right_detail">
		
			<?php if($vgroup->isMoive()) {?>
				<?php $this->partial("player.moive.htm",array("vgroup"=>$vgroup,'video'=>$video)); ?>
            <?php } else if($vgroup->isTv()){?>
            	<?php $this->partial("player.tv.htm",array("vgroup"=>$vgroup,'video'=>$video)); ?>
            <?php } else if($vgroup->isCartoon()){?>
            	<?php $this->partial("player.cartoon.htm",array("vgroup"=>$vgroup,'video'=>$video)); ?>
            <?php }?>
            
            
            <style>
.v-related {
width: 685px;
overflow: hidden;
}
.text-list-struct .selected, .text-list-struct .selected:hover, .v-related .selected, .v-related .selected:hover {
background: url('/styles/kan/images/tab.png') no-repeat;
cursor: default;
}
.text-list-struct .tab, .v-related .tab {
position: relative;
padding: 0 8px;
margin: 20px 0 20px 0;
width: 85px;
outline: 0;
display: inline-block;
line-height: 24px;
}
.v-related .list {
width: 800px;
}
.v-related .list .item {
position: relative;
padding-right: 33px;
padding-bottom: 20px;
float: left;
display: block;
width: 111px;
overflow: hidden;
}
.v-related .list .poster-link {
display: block;
font-size: 0;
}
.v-details a {
color: 
rgb(5, 110, 161);
text-decoration: none;
font-family: "宋体b8b\4f53";
}
.v-related .list .title {
height: 24px;
line-height: 24px;
white-space: nowrap;
overflow: hidden;
color: 
rgb(5, 110, 161);
text-align: center;
font-size: 14px;
}
.v-related .list .text {
height: 24px;
line-height: 24px;
overflow: hidden;
color: 
rgb(153, 153, 153);
text-align: center;
font-size: 12px;
}
.v-related .list .poster-img {
width: 107px;
height: 146px;
}
.v-related .list .hide-text {
overflow: hidden;
text-align: center;
}
.v-related .list .hide-bg {
    background: none repeat scroll 0 0 #000000;
    opacity: 0.5;
}
.v-related .list .hide-text, .v-related .list .hide-bg {
    height: 19px;
    left: 0;
    line-height: 19px;
    position: absolute;
    top: 128px;
    width: 107px;
}
.v-related .list .hide-text .trait {
    color: #FFFFFF;
    float: left;
    font-family: '宋体';
    padding-left: 5px;
    font: 12px/1.5 Arial;
}
.v-related .list .hide-text .grade {
    color: #FFFFFF;
    float: right;
    padding-right: 5px;
}
.v-related .list .hide-text .grade em {
    color: orange;
    font-size: 12px;
}
            </style>
            <script>
            $(document).ready(function() {
				 $("#tab_select > a").each(function(i){
					  
						$(this).click(function(){
							
							$("#tab_select > a").each(function(i){
								
								 $(this).removeClass('selected');
							});
							var type = $(this).attr('type');
							$(this).addClass('selected');	
							$("#tab_content > ul").each(function(i){
								
								 $(this).hide();
								 if($(this).attr('type') == type)
								 {
									 $(this).show();
								 }
							});
							
				 		});

				 });
				 	
			});

            </script>
            <div class="line-b v-related">
				<div id="tab_select" class="clearfix">
					<a href="javascript:void(0);"  class="tab selected"  type="love" >你或许也喜欢</a>					
					<a href="javascript:void(0);"  class="tab"  type="star">该片主演作品</a>
					<a href="javascript:void(0);"  class="tab"  type="director">该片导演作品</a>
				</div>
				<div id="tab_content">
					<ul class="list clearfix" type="love" >
						<li class="item">
							<a class="poster-link" target="_self" href="/m/haTqY0D5QHTAUR.html" >
								<img class="poster-img" src="http://image11.m1905.cn/uploadfile/2012/0309/thumb_1_149_219_20120309113724683.jpg">
								<div class="hide-bg"></div>
								<div class="hide-text">
									<span class="trait">2011年</span>							
									<span class="grade"><em>4.8</em>分</span>
								</div>
							</a>
							<h2 class="title"><a target="_self" href="/m/haTqY0D5QHTAUR.html" title="饭局也疯狂">饭局也疯狂</a></h2>
							<p class="text">黄渤</p>
						</li>
				
					</ul>
					<ul class="list clearfix" type="star" style="display: none; ">
					<?php $samestars =  $vgroup->getSameStar();
						foreach($samestars as $k => $samestar) {?>
						<li class="item">
							<a class="poster-link" target="_self" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$samestar->id));?>" >
								<img class="poster-img" src="<?php echo $samestar->getImageLink()?>">
								<div class="hide-bg"></div>
								<div class="hide-text">
									<span class="trait"><?php echo $samestar->year;?>年</span>							
									<span class="grade"><em><?php echo $samestar->getRate();?></em>分</span>
								</div>
							</a>
							<h2 class="title"><a target="_self" href="/m/haTqY0D5QHTAUR.html" title="饭局也疯狂"><?php echo $samestar->title;?></a></h2>
							<p class="text"><?php echo $samestar->star;?></p>
						</li>
					<?php }?>
					</ul>
					<ul class="list clearfix" type="director" style="display: none; ">
					<?php $samestars =  $vgroup->getSameDirector();
						foreach($samestars as $k => $samedirector) {?>
						<li class="item">
							<a class="poster-link" target="_self" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$samedirector->id));?>" >
								<img class="poster-img" src="<?php echo $samedirector->getImageLink()?>">
								<div class="hide-bg"></div>
								<div class="hide-text">
									<span class="trait"><?php echo $samedirector->year;?>年</span>							
									<span class="grade"><em><?php echo $samedirector->getRate();?></em>分</span>
								</div>
							</a>
							<h2 class="title"><a target="_self" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$samedirector->id));?>"><?php echo $samedirector->title;?></a></h2>
							<p class="text"><?php echo $samedirector->star;?></p>
						</li>
					<?php }?>
					</ul>
				</div>
			</div>
		</div>
		
	</div>
<style>
#footer {
	clear: both;
	height: 52px;
	line-height: 52px;
	margin: 0 auto;
	position: relative;
	text-align: center;
	width: 960px;
}
</style>
	<div id="footer">
		<span class="foot_logo ct">logo</span> <a target="_blank" href="#">关于我们</a>|
		<a target="_blank" href="#">意见反馈</a>| <a target="_blank" href="#">免责声明</a>
		<span>Copyright &copy; 2012 .com. All Rights Reserved. </span> <em><a
			href="#" target="_blank">京ICP证123456号</a></em>
	</div>
</body>
</html>