<?php $this->layout("admin_main");?><div class="container">
<h3 class="marginbot">编辑Spiderlogs<a href="<?php echo $this->createUrl("admin","spiderlogs","list");?>" class="sgbtn">返回Spiderlogs列表</a></h3>
	<div class="mainbox">
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","spiderlogs","modify");?>" method="post">
			<table class="opt">
				<tbody>
					                    <tr>
						<th colspan="2">Spiderid:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="spiderid" value="<?php echo $model->spiderid;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">Type:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="type" value="<?php echo $model->type;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">Starttime:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="starttime" value="<?php echo $model->starttime;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">Endtime:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="endtime" value="<?php echo $model->endtime;?>"></td>
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