<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MiniKan  Control Panel</title>
<body>

    <form action="<?php echo $this->createUrl("admin","index","loginsubmit");?>" method="post">
    	<input type="text" name="username">
    	<input type="password" name="password">
    	<input type="submit" value="提交">
    </form>
</body>
</html>
