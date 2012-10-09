<?php $this->layout("admin_main");?><div class="container">
<h3 class="marginbot">编辑豆瓣信息<a href="<?php echo $this->createUrl("admin","doubans","list");?>" class="sgbtn">返回豆瓣信息列表</a></h3>
	<div class="mainbox">
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","doubans","modify");?>" method="post">
			<table class="opt">
				<tbody>
					                    <tr>
						<th colspan="2">聚合id:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="groupid" value="<?php echo $model->groupid;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">豆瓣id:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="doubanid" value="<?php echo $model->doubanid;?>"></td>
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
						<th colspan="2">海报:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="pic" value="<?php echo $model->pic;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">导演:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="director" value="<?php echo $model->director;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">编剧:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="writer" value="<?php echo $model->writer;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">明星:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="star" value="<?php echo $model->star;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">分类:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="cate" value="<?php echo $model->cate;?>"></td>
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
						<th colspan="2">官网:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="website" value="<?php echo $model->website;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">语言:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="lang" value="<?php echo $model->lang;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">发布时间:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="pubdate" value="<?php echo $model->pubdate;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">片长:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="runtime" value="<?php echo $model->runtime;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">别名:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="alias" value="<?php echo $model->alias;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">imdb:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="imdb" value="<?php echo $model->imdb;?>"></td>
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
						<th colspan="2">摘要:</th>
					</tr>
					<tr>
						<td><textarea  name="summary"  style="width:600px;height:150px;"><?php echo $model->summary;?>"></textarea></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">Tag:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="tag" value="<?php echo $model->tag;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">短评:</th>
					</tr>
					<tr>
						<td><textarea name="shortcomment"  style="width:600px;height:150px;"><?php echo $model->shortcomment;?></textarea></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">Ctime:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="ctime" value="<?php echo $model->ctime;?>"></td>
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