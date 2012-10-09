<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MiniKan  Control Panel</title>
<body>
<style>
#noresult {
padding: 60px 0 50px;
padding-left: 100px;
width: 400px;
margin: 0 auto;
background: url(/styles/kan/images/noresult.png) 0 center no-repeat;
}
#noresult .red {
color: red;
}
#noresult .noresult-hd {
padding-bottom: 20px;
line-height: 20px;
font-size: 14px;
}
#noresult .noresult-bd {
line-height: 26px;
}  
#noresult a {
color: rgb(4, 120, 179);
}
</style>
      <div id="noresult">
            <p class="noresult-hd">抱歉，PAGE:404,我们正在努力建设中。</p>
            <ul class="noresult-bd">
                <li class="title">建议您:</li>
                <li><span class="dot"></span><a href="/">返回到首页</a></li>
                <li><span class="dot"></span>给我们<a href="＃" target="_blank">留言求片</a></li>
                <li><span class="dot"></span>去<span class="blue"><a href="<?php echo $this->createUrl('site','kan','movie');?>" target="_blank">电影</a>、
                <a href="<?php echo $this->createUrl('site','kan','tv');?>" target="_blank">电视剧</a>、
                <a href="<?php echo $this->createUrl('site','kan','cartoon');?>" target="_blank">动漫</a></span>寻找喜欢的内容</li>
            </ul>
        </div>
</body>
</html>
