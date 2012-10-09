<?php $this->layout("admin_main");?>	
<div class="container">
	<h3 class="marginbot">
		<a <?php if($id=='hot_search' || empty($id)){ echo 'style="color:red;"';}?> href="<?php echo $this->createUrl("admin","segments","list",array('id'=>'hot_search'));?>" class="sgbtn">热词搜索</a>
		<!--  <a <?php if($id==2){ echo 'style="color:red;"';}?> href="<?php echo $this->createUrl("admin","segments","list",array('id'=>2));?>" class="sgbtn">热词搜索</a>
	-->
	</h3>
	<div class="mainbox">
	<?php if($firsterror) {?>
		<div class="errormsg">
			<p><em><?php echo $firsterror;?></em></p>
		</div>
		<?php }?>
		<form action="<?php echo $this->createUrl("admin","segments","save");?>"  method="post">
			<textarea style="width:800px;height:400px;" name="data_content"><?php echo $data_content;?></textarea><br/>
			<input type="hidden" value="<?php echo  $id;?>" name="id">
			<input type="submit" value="提 交" class="btn">
		</form>
	</div>
</div>
