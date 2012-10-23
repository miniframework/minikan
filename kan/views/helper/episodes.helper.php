<?php
class episodesHelper extends mini_web_helper
{
	const SHOW_PER_NUM = 7;

	public function showFirst($model)
	{
		$allnum  = $model->nowepisodes;
		if($allnum <= self::SHOW_PER_NUM+1)
		{
			$html = '';
		}
		else
		{
			$html = '<div class="partli"> <a href="'.$this->view->createUrl('site','kan','player',array('id'=>$model->id)).'" class="allpart"  target="_self">全部>></a> </div>';
		}
		if(!empty($allnum))
		{
			if($allnum <=self::SHOW_PER_NUM)
			{
				for($n = 0 ; $n<$allnum; $n++)
				{
					$html .='<a target="blank" href="'.$this->view->createUrl('site','kan','player',array('id'=>$model->id),array('autoplay'=>1,'episode'=>($n+1))).'" rel="nofollow">'.($n+1).'集</a>';
				} 
			} else { 
				for($i = 0; $i < self::SHOW_PER_NUM; $i++) {
					$html .='<a href="'.$this->view->createUrl('site','kan','player',array('id'=>$model->id),array('autoplay'=>1,'episode'=>($i+1))).'" rel="nofollow">'.($i+1).'集</a>';
				}
				if($allnum > self::SHOW_PER_NUM+1)
				{
					$html .='... ';
				}
				$html .='<a target="blank" href="'.$this->view->createUrl('site','kan','player',array('id'=>$model->id),array('autoplay'=>1,'episode'=>$allnum)).'" rel="nofollow">'.$allnum.'集';
				if($model->isNewEp()) {
					$html .='<span class="newpart"></span>';
				}
				$html .='</a>';
			}
		}
		return $html;

	}  
}
