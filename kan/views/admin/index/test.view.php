<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>MiniKan Control Panel</title>
<link rel="stylesheet" href="/thirdparty/ueditor/themes/default/ueditor.css">
<script type="text/javascript" src="<?php echo $this->createUrl("admin","ueditor","config");?>"></script>
<script type="text/javascript" src="/thirdparty/ueditor/editor_all.js"></script>

</head>
<body>
<script type="text/javascript">
    var editor = new baidu.editor.ui.Editor();
    editor.render("editorContent");



    
    function getAllHtml() {
   	 var arr = [];
     arr.push(  '<img src="http://img.baidu.com/hi/jx2/j_0001.gif" />' );
     editor.execCommand('inserthtml', arr.join(''));
     alert( editor.getContent() )
    }

    function check()
    {
    	if(editor.hasContents()){ //此处以非空为例
    	    editor.sync();       //同步内容
    	}
    	else {
        	alert("内容不能为空");
       		return false;
    	}
        return true;
    }
</script>
 <input type="button" value="获得整个html的内容" onclick="getAllHtml()">
 
 <form id="editorform" action="" method="post" onsubmit="return check();">
    <script type="text/plain" id="editorContent" name="editorContent"></script>
    <input type="submit" name="submit" value="提交">
</form>
</body>
</html>