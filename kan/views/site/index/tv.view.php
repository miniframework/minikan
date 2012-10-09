<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<a href="<?php echo $this->createUrl("site","index","tv",array("page"=>1),array("area"=>$search['year'],"year"=>$search['year'],"order"=>$search['order']));?>">全部</a>
<?php $cateMap = $vgroup->cateMap();
foreach($cateMap as $k => $cate){ ?>
<a href="<?php echo $this->createUrl("site","index","tv",array("page"=>1),array("cate"=>$k,"area"=>$search['year'],"year"=>$search['year'],"order"=>$search['order']));?>"><?php echo $cate;?></a>
<?php }?>
<br/>
<a href="<?php echo $this->createUrl("site","index","tv",array("page"=>1),array("cate"=>$search['cate'],"year"=>$search['year'],"order"=>$search['order']));?>">全部</a>
<?php $areaMap = $vgroup->areaMap();
foreach($areaMap as $k => $area){ ?>
<a href="<?php echo $this->createUrl("site","index","tv",array("page"=>1),array("cate"=>$search['cate'],"area"=>$k,"year"=>$search['year'],"order"=>$search['order']));?>"><?php echo $area;?></a>
<?php }?>
<br/>
<a href="<?php echo $this->createUrl("site","index","tv",array("page"=>1),array("cate"=>$search['cate'],"area"=>$search['area'],"order"=>$search['order']));?>">全部</a>
<?php $yearMap = $vgroup->yearMap();
foreach($yearMap as $k => $year){ ?>
<a href="<?php echo $this->createUrl("site","index","tv",array("page"=>1),array("cate"=>$search['cate'],"area"=>$search['area'],"year"=>$k,"order"=>$search['order']));?>"><?php echo $year;?></a>
<?php }?>
<br/>
<?php $orderMap = $vgroup->getOrder();
foreach($orderMap as $k => $order){ ?>
<a href="<?php echo $this->createUrl("site","index","tv",array("page"=>1),array("cate"=>$search['cate'],"area"=>$search['area'],"year"=>$search['year'],"order"=>$k));?>"><?php echo $order;?></a>
<?php }?>
<table border="1">
<?php 
if(!empty($models))
	foreach($models as $k => $model)
	{?>
	<tr>
<td>&nbsp;</td>
<td><?php echo $model->title;?></td>
<td><?php echo $model->cate;?></td>
<td><?php echo $model->area;?></td>
<td><?php echo $model->year;?></td>
<td><?php echo $model->rate;?></td>
<td><img width="120px" height="160px" src="<?php echo $model->imagelink;?>"/></td>
<td>
<?php $videoids = $model->getVideoids();?>
	<?php foreach($videoids['siteid'] as $k => $v) {?>
		<a target="_blank" href="<?php echo $videoids['playlink'][$k];?>"><?php echo $video->siteidMap($v);?></a>
	<?php }?>
</td>
<td><?php echo $model->getCateSum();?></td>
<td><?php echo $model->getAreaShow();?></td>
</tr>		
<?php }?>


</table>
</body>
<?php echo $page->pageHtml();?>
</html>
