<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MiniKan  Control Panel</title>
<style type="text/css">
/* common */
*{ word-wrap:break-word; outline:none; }
body{ width:159px; background:#F2F9FD url(/styles/admin/images/bg_repx_h.gif) right top no-repeat; color:#666; font:12px "Lucida Grande", Verdana, Lucida, Helvetica, Arial, "ÀŒÃÂ" ,sans-serif; }
body, ul{ margin:0; padding:0; }
a{ color:#2366A8; text-decoration:none; }
	a:hover { text-decoration:underline; }
.menu{ position:relative; z-index:20; }
	.menu ul{ position:absolute; top:10px; right:-1px !important; right:-2px; list-style:none; width:150px; background:#F2F9FD url(/styles/admin/images/bg_repx_h.gif) right -20px no-repeat; }
		.menu li{ margin:3px 0; *margin:1px 0; height:auto !important; height:24px; overflow:hidden; font-size:14px; font-weight:700; }
		.menu li a{ display:block; margin-right:2px; padding:3px 0 2px 30px; *padding:4px 0 2px 30px; border:1px solid #F2F9FD; background:url(/styles/admin/images/bg_repno.gif) no-repeat 10px -40px; color:#666; }
			.menu li a:hover{ text-decoration:none; margin-right:0; border:1px solid #B5CFD9; border-right:1px solid #FFF; background:#FFF; }
		.menu li a.tabon{ text-decoration:none; margin-right:0; border:1px solid #B5CFD9; border-right:1px solid #FFF; background:#FFF url(/styles/admin/images/bg_repy.gif) repeat-y; color:#2366A8; }
.footer{ position:absolute; z-index:10; right:13px; bottom:0; padding:5px 0; line-height:150%; background:url(/styles/admin/images/bg_repx.gif) 0 -199px repeat-x; font-family:Arial, sans-serif; font-size:10px; -webkit-text-size-adjust: none; }
</style>
</head>
<body>
<div class="menu">
	<ul id="leftmenu">
		<li><a href="<?php echo $this->createUrl("admin","index","main");?>" target="main" class="tabon">首页</a></li>
		<li><a href="<?php echo $this->createUrl("admin","spider","list");?>" target="main">蜘蛛抓取</a></li>
<li><a href="<?php echo $this->createUrl("admin","videos","list");?>" target="main">视频信息</a></li>
<li><a href="<?php echo $this->createUrl("admin","vgroups","list");?>" target="main">视频聚合</a></li>
<li><a href="<?php echo $this->createUrl("admin","vpeoples","list");?>" target="main">人名管理</a></li>
<li><a href="<?php echo $this->createUrl("admin","reviews","list");?>" target="main">豆瓣评论</a></li>
<li><a href="<?php echo $this->createUrl("admin","catalogs","list");?>" target="main">电影分组</a></li>
<li><a href="<?php echo $this->createUrl("admin","recommends","list");?>" target="main">推荐电影</a></li>
<li><a href="<?php echo $this->createUrl("admin","segments","list");?>" target="main">片段管理</a></li>
<li><a href="<?php echo $this->createUrl("admin","groupseries","list");?>" target="main">视频系列</a></li>
<li><a href="<?php echo $this->createUrl("admin","doubans","list");?>" target="main">豆瓣信息</a></li>
<li><a href="<?php echo $this->createUrl("admin","index","test");?>" target="main">测试百度编辑器</a></li>
<li><a href="<?php echo $this->createUrl("admin","spiderlogs","list");?>" target="main">Spiderlogs</a></li>
<li><a href="<?php echo $this->createUrl("admin","vdownloads","list");?>" target="main">视频下载</a></li>
<li><a href="<?php echo $this->createUrl("admin","vseeds","list");?>" target="main">种子</a></li>
<!-- {Mini-Crud-Menu} -->
	</ul>
</div>
<div class="footer">Powered by Mini <br />&copy; 2001 - 2011 <a href="http://www.github.com/miniframework/" target="_blank">Mini</a> Inc.</div>
<script type="text/javascript">
	function cleartabon() {
		if(lastmenu) {
			lastmenu.className = '';
		}
		for(var i = 0; i < menus.length; i++) {
			var menu = menus[i];
			if(menu.className == 'tabon') {
				lastmenu = menu;
			}
		}
	}
	var menus = document.getElementById('leftmenu').getElementsByTagName('a');
	var lastmenu = '';
	for(var i = 0; i < menus.length; i++) {
		var menu = menus[i];
		menu.onclick = function() {
			setTimeout('cleartabon()', 1);
			this.className = 'tabon';
			this.blur();
		}
	}

	cleartabon();
</script>
