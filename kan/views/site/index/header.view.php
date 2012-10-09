<div id="navigation" class="fm960">
  <ul class="navi_list">
    <li class="<?php if($parentId == 'siteindexindex') echo "c";?>"><a href="<?php echo $this->createUrl('site','index','index');?>">首页</a></li>
    <li class="split <?php if($parentId == 'sitekanmovie') echo "c";?>"><a href="<?php echo $this->createUrl('site','kan','movie');?>">电影</a></li>
    <li class="split <?php if($parentId == 'sitekantv') echo "c";?>"><a href="<?php echo $this->createUrl('site','kan','tv');?>">电视剧</a></li>
    <li class="split <?php if($parentId == 'sitekancartoon') echo "c";?>"><a href="<?php echo $this->createUrl('site','kan','cartoon');?>">动漫</a></li>
  </ul>
</div>
<div id="header">
  <div id="info_bar" class="fm960 clearfix">
    <div class="logo_show fl"> <a href="/" class="logo" title="">ailezi</a> </div>
    <div class="search-bd">
      <form target="_blank" method="GET" action="<?php echo $this->createUrl("site","kan","search");?>" id="search-form" class="clearfix">
        <span class="ipt-search-area">
        <input type="text" id="kw" class="ipt-search" name="q" autocomplete="off">
        <input type="hidden" name="t" value="1">
        <!--[if lte IE8]>
					<span class="shadowtop"></span>
					<span class="shadowleft"></span>
					<![endif]-->
        </span>
        <input type="submit" value="搜　索" class="btn-search" id="btn">
      </form>
      <div  class="hot"> <?php echo $hotSearch;?> </div>
    </div>
  </div>
</div>
