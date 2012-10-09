<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="/styles/kan/css/globe.css" />
<link rel="stylesheet" type="text/css" href="/styles/kan/css/kan.css" />
<script type="text/javascript"  src="/styles/kan/js/jquery.min.js" ></script>
<title><?php if(isset($title)) echo $title;?></title>
</head>
<body>
<?php $this->controller("site",'index','header');?>
<?php echo $content;?>


<div id="footer">
	<span class="foot_logo ct">logo</span> <a target="_blank" href="#">关于我们</a>|
	<a target="_blank" href="#">意见反馈</a>| <a target="_blank" href="#">免责声明</a>
	<span>Copyright &copy; 2012 .com. All Rights Reserved. </span> 
	<em><a href="#" target="_blank">京ICP证123456号</a></em>
</div>
</body>
</html>
