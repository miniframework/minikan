<?php $this->layout("admin_main");?><div class="container">
<h3 class="marginbot">编辑视频下载<a href="<?php echo $this->createUrl("admin","vdownloads","list");?>" class="sgbtn">返回视频下载列表</a></h3>
	<div class="mainbox">
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","vdownloads","modify");?>" method="post">
			<table class="opt">
				<tbody>
					                    <tr>
						<th colspan="2">类型:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="vtype" value="<?php echo $model->vtype;?>"></td>
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
						<th colspan="2">头图:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="imagelink" value="<?php echo $model->imagelink;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">豆瓣图片:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="doubanimage" value="<?php echo $model->doubanimage;?>"></td>
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
						<th colspan="2">年:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="year" value="<?php echo $model->year;?>"></td>
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
						<th colspan="2">国家:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="country" value="<?php echo $model->country;?>"></td>
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
						<th colspan="2">导演:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="director" value="<?php echo $model->director;?>"></td>
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
						<th colspan="2">摘要:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="summary" value="<?php echo $model->summary;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">剧照:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="photo" value="<?php echo $model->photo;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">guid:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="guid" value="<?php echo $model->guid;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">Ctime:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="ctime" value="<?php echo $model->ctime;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">Mtime:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="mtime" value="<?php echo $model->mtime;?>"></td>
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