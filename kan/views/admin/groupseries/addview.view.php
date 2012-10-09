<?php $this->layout("admin_main");?>
<div class="container">
<h3 class="marginbot">添加视频系列<a href="<?php echo $this->createUrl("admin","groupseries","list");?>" class="sgbtn">返回视频系列列表</a></h3>
	<div class="mainbox">
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","groupseries","add");?>" method="post">
			<table class="opt">
				<tbody>
					                    <tr>
						<th colspan="2">聚合id:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="groupid" value=""></td>
						<td></td>
                    </tr>
					                </tbody>
			</table>
			<div class="opt"><input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3"></div>
			</form>
		</div>
	</div>
</div>