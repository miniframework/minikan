<?php $this->layout("admin_main");?><div class="container">
<h3 class="marginbot">编辑视频聚合<a href="<?php echo $this->createUrl("admin","vgroups","list");?>" class="sgbtn">返回视频聚合列表</a></h3>
	<div class="mainbox">
	 <?php if(isset($firsterror) && !empty($firsterror)) {?>		<div class="errormsg">
				<p><em><?php echo $firsterror;?></em></p>
			</div>
			<?php } ?>
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","vgroups","modify");?>" method="post">
			<table class="opt">
				<tbody>
					                    <tr>
						<th colspan="2">类型:</th>
					</tr>
					<tr>
						<td>
						<select name="vtype">
							<option value="0">请选择</option>
							<?php foreach($video->vtypeMap() as $k => $v){?>
							<option <?php if($model->vtype == $k) echo "selected"; ?> value="<?php echo $k;?>"><?php echo $v;?></option>
							<?php }?>
						</select>
						</td>
						<td></td>
                    </tr>
				    <tr>
						<th colspan="2">视频聚合:</th>
					</tr>
					<tr>
						<td><textarea readOnly class="txt" style="width:600px;height:50px;"><?php echo $model->videoids;?></textarea></td>
						<td></td>
                    </tr>
                    <tr>
						<th colspan="2">视频聚合:</th>
					</tr>
					<tr>
						<td><span>		
						<?php $videoids = $model->getVideoids() ; foreach($videoids as $siteid => $videoid) {?>
                              <a target="_blank" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$model->id),array('autoplay'=>1,'site'=>$siteid));?>"> 
                              <img src="/styles/kan/images/icon/<?php echo $video->getIcon($siteid);?>" /><?php echo $video->getSiteZh($siteid);?>
                              </a>
                              (<a href="<?php echo $this->createUrl('admin','vgroups','delvideo',array('id'=>$model->id,"siteid"=>$siteid));?>">删除</a>)<br/>
	                   <?php }?>					 
			 		</span></td>
						<td></td>
                    </tr>
                    
					                    <tr>
						<th colspan="2">标题:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="title" value="<?php echo $model->title;?>"></td>
						<td></td>
                    </tr>
                      <tr>
						<th colspan="2">显示标题:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="showtitle" value="<?php echo $model->showtitle;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">图片地址:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="imagelink" value="<?php echo $model->imagelink;?>" style="width:600px;">
						<br/><img src="<?php echo $model->imagelink;?>" />
						</td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">douban图片:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="doubanimage" value="<?php echo $model->doubanimage;?>" style="width:600px;">
						<br/><img src="<?php echo $model->doubanimage;?>" />
						</td>
						<td></td>
                    </tr>
                               <tr>
						<th colspan="2">其他网站图片:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="webimage" value="<?php echo $model->webimage;?>" style="width:600px;">
						<br/><img src="<?php echo $model->webimage;?>" />
						</td>
						<td></td>
                    </tr>
                     <tr>
						<th colspan="2">自定义图片:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="showimage" value="<?php echo $model->showimage;?>" style="width:600px;">
						<br/><img src="<?php echo $model->showimage;?>" />
						</td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">分类:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="cate" value="<?php echo $model->changeExplode('cate');?>" style="width:600px;"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">地区:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="area" value="<?php echo $model->area;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">年:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="year" value="<?php echo $model->year;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">明星:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="star" value="<?php echo $model->changeExplode('star');?>" style="width:600px;"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">导演:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="director" value="<?php echo $model->changeExplode('director');?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">概要:</th>
					</tr>
					<tr>
						<td><textarea  class="txt" name="summary" style="width:600px;height:150px;"><?php echo $model->summary;?></textarea></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">TAG:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="tag" value="<?php echo $model->tag;?>" style="width:600px;"></td>
						<td></td>
                    </tr>
                    	<td></td>
                    </tr>
					<tr>
						<th colspan="2">评分:</th>
					</tr>
                    <tr>
						<td><input type="text" class="txt" name="rate" value="<?php echo $model->rate;?>"></td>
						<td></td>
                    </tr>
                    <tr>
						<th colspan="2">IMDB:</th>
					</tr>
                    <tr>
						<td><input type="text" class="txt" name="imdb" value="<?php echo $model->imdb;?>"></td>
						<td></td>
                    </tr>
									</tbody>
			</table>
			<div class="opt">
			<input type="hidden" name="id"	value="<?php echo $model->id;?>">
			<input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3">
			</div>
			</form>
		</div>
	</div>
</div>