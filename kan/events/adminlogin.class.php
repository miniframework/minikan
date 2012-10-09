<?php
class adminloginEvent
{
	public function onbeforeAction($args)
	{
		if($args[0]['app'] == 'admin')
		{
			if($args[0]['action'] != 'loginsubmit' &&  $args[0]['action'] !='login')
			{
				session_start();
				if(empty($_SESSION['admin_login_key']))
				{
					$controller = $args[1];
						
					$url = $controller->route->createUrl("admin","index","login");
					$controller->jump($url);
				}
				
			}
		}
	}
}