<?php
class IndexController extends mini_web_controller
{
    public function doIndex()
    {
       
    }
    public function doHeader()
    {
    	
    	$this->view->user = $_SESSION['username'];
    }
    public function doMain()
    {
    }
    public function doMenu()
    {
    	
    }
    public function doLogin()
    {
    	
    }
    public function doLogout()
    {
    	unset($_SESSION['admin_login_key']);
    	unset($_SESSION['username']);
    	session_destroy();
    	$this->jump($this->route->createUrl("admin","index","login"));
    }
    public function doLoginsubmit()
    {
    	$username = $this->request->get("username");
    	$password = $this->request->get("password");
    	if($username == "admin" && $password == "ailezi".date("Y-m-d"))
    	{
	    	session_start();
	    	$_SESSION['admin_login_key'] = md5($username.$password); 
	    	$_SESSION['username'] = $username;
	    	$this->jump($this->route->createUrl("admin","index","index"));
    	}
    	else
    	{
    		$this->jump($this->route->createUrl("admin","index","login"));
    	}
    }
    public function doTest()
    {
    	
    }
}