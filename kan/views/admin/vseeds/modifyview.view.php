<?php $this->layout("admin_main");?><div class="container">
<h3 class="marginbot">编辑种子<a href="<?php echo $this->createUrl("admin","vseeds","list");?>" class="sgbtn">返回种子列表</a></h3>
	<div class="mainbox">
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","vseeds","modify");?>" method="post">
			<table class="opt">
				<tbody>
					                    <tr>
						<th colspan="2">下载id:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="downloadid" value="<?php echo $model->downloadid;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">站点id:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="siteid" value="<?php echo $model->siteid;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">种子:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="seed" value="<?php echo $model->seed;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">字幕:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="word" value="<?php echo $model->word;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">分辨率:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="videoscreen" value="<?php echo $model->videoscreen;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">文件大小:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="filesize" value="<?php echo $model->filesize;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">文件格式:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="fileformat" value="<?php echo $model->fileformat;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">时长:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="duration" value="<?php echo $model->duration;?>"></td>
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