<?php $this->layout("admin_main");?>
<div class="container">
<h3 class="marginbot">添加Spiderlogs<a href="<?php echo $this->createUrl("admin","spiderlogs","list");?>" class="sgbtn">返回Spiderlogs列表</a></h3>
	<div class="mainbox">
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","spiderlogs","add");?>" method="post">
			<table class="opt">
				<tbody>
					                    <tr>
						<th colspan="2">Spiderid:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="spiderid" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">Type:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="type" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">Starttime:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="starttime" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">Endtime:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="endtime" value=""></td>
						<td></td>
                    </tr>
					                </tbody>
			</table>
			<div class="opt"><input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3"></div>
			</form>
		</div>
	</div>
</div>