<?php
return array(
		
	'/'	=>array('app'=>'site',
					 'controller'=>'index',
					 'action'=>'index'),	
		
	'/movie/(?P<page>.*?)?/?'=>
					array('app'=>'site',
					 'controller'=>'index',
					 'action'=>'movie'),
		
	'/tv/(?P<page>.*?)?/?'=>
					array('app'=>'site',
					 'controller'=>'index',
					 'action'=>'tv'),
	'/kan/movie/(?P<page>.*?)?/?'=>
					array('app'=>'site',
						  'controller'=>'kan',
						  'action'=>'movie'),
	'/kan/tv/(?P<page>.*?)?/?'=>
					array('app'=>'site',
							'controller'=>'kan',
							'action'=>'tv'),
	'/kan/cartoon/(?P<page>.*?)?/?'=>
					array('app'=>'site',
							'controller'=>'kan',
							'action'=>'cartoon'),
	'/kan/player/(?P<id>\d+)/?'=>
					array('app'=>'site',
							'controller'=>'kan',
							'action'=>'player'),
	'/kan/search/(?P<page>.*?)?/?'=>
					array('app'=>'site',
						  'controller'=>'kan',
						  'action'=>'search'),
	'/httpproxy'=>
				array('app'=>'site',
					'controller'=>'index',
					'action'=>'httpproxy'),
		
	'/httpdata'=>
				array('app'=>'site',
					'controller'=>'index',
					'action'=>'httpdata'),
	'/xlunion'=>
				array('app'=>'site',
					'controller'=>'index',
					'action'=>'xlunion'),
			
);