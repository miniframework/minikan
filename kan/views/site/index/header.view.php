<div id="navigation" class="fm960">
  <ul class="navi_list">
    <li class="<?php if($parentId == 'siteindexindex') echo "c";?>"><a href="<?php echo $this->createUrl('site','index','index');?>">首页</a></li>
    <li class="split <?php if($parentId == 'sitekanmovie') echo "c";?>"><a href="<?php echo $this->createUrl('site','kan','movie');?>">电影</a></li>
    <li class="split <?php if($parentId == 'sitekantv') echo "c";?>"><a href="<?php echo $this->createUrl('site','kan','tv');?>">电视剧</a></li>
    <li class="split <?php if($parentId == 'sitekancartoon') echo "c";?>"><a href="<?php echo $this->createUrl('site','kan','cartoon');?>">动漫</a></li>
    <li class="split <?php if($parentId == 'sitekandownmovie') echo "c";?>"><a href="<?php echo $this->createUrl('site','kan','downmovie');?>">电影下载</a></li>
  </ul>
</div>
<div id="header">
  <div id="info_bar" class="fm960 clearfix">
    <div class="logo_show fl"> <a  href="/" class="logo" title="">ailezi</a> </div>
    <div class="search-bd">
      <form target="_blank" method="GET" action="<?php echo $this->createUrl("site","kan","search");?>" id="search-form" class="clearfix">
        <span class="ipt-search-area">
        <input type="text" id="kw" class="ipt-search" value="<?php if(isset($keyword)) echo $keyword;?>" name="q" autocomplete="off">
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
<link rel="stylesheet" type="text/css" href="/styles/kan/css/suggest.css" />
<script type="text/javascript"  src="/styles/kan/js/suggest.js" ></script>
<script>
$(function(){	
	var	id="kw", 
		url="http://vs.sugg.sogou.com/sugg/ajaj_json.jsp",
		script=null, //script标签引用
		doc=document,			 
		cache={}, //缓存
		suggObj=null	//百度suggest 实例
		;  		
		
	function suggest(key){
		//console.log(key)
		if(cache[key]){ //缓存
			show(cache[key][0],cache[key][1])
		}else{
			try {
				doc.body.removeChild(script);
			} catch (d) {}
	
			script = doc.createElement("script");
			script.charset = "gb2312";
			script.src = url + "?key=" + encodeURIComponent(key) + "&type=vc";
			
			doc.body.appendChild(script);            
		}                
	}
	
	//初始化
	function init(){
		//console.log("init suggObj");
		suggObj = new baidu.ui.Suggestion({
			getData: function(word) {
				//console.log("getData "+word);
				suggest(word)
			},
			onconfirm: function(evt) {
				console.log("onconfirm")
				//var c = evt.data.item.content;
				//console.log(evt)
				$("#"+id).parents("form").submit();
			},
			onbeforepick: function(evt) { //去掉加粗
				var c = evt.data.item.content;
				evt.data.item.content=evt.data.item.value=c.replace("<b>", "").replace("</b>", "")
			}
		});

		suggObj.render(id)

	}
	
	function show(key,data){
		var data2=[];
		for(var i=0,len=data.length;i<len;i++){
			var str = data[i];
			if (data[i].indexOf(key) == 0) {
				str = key + "<b>" + data[i].substr(key.length) + "</b>"
			}			
			data2.push(str)
		}
		suggObj.show(key,data2)
	}

	//------回调---
	if (typeof window.sogou != "object" || window.sogou == null) {
		window.sogou = {}
	}
	window.sogou.sug=function(data){	 
		cache[data[0]]=data;
		show(data[0],data[1]);
	}		
	init();

});

</script>