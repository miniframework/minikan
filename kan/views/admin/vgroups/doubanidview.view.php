<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="/styles/admin/images/admincp.css" type="text/css" media="all" />
<title>Insert title here</title>
</head>
<body>
<form action="<?php echo $this->createUrl("admin","vgroups","doubanid");?>" method="post">
豆瓣subjectId:<input type="text"  class="txt"  value="" name="subjectid"><br/>
<input type="hidden" name="id" value="<?php echo $vgroupid;?>">
<input  type="submit" value="获取">
</form>
</div>
</body>
</html>