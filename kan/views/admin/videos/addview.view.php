<?php $this->layout("admin_main");?>
<div class="container">
<h3 class="marginbot">添加视频信息<a href="<?php echo $this->createUrl("admin","videos","list");?>" class="sgbtn">返回视频信息列表</a></h3>
	<div class="mainbox">
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","videos","add");?>" method="post">
			<table class="opt">
				<tbody>
					<tr>
						<th colspan="2">视频来源:</th>
					</tr>
					<tr>
						<td>
						<select name="siteid">
							<option value="0">请选择</option>
							<?php foreach($model->siteidMap() as $k => $v){?>
							<option value="<?php echo $k;?>"><?php echo $v;?></option>
							<?php }?>
						</select>
						</td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">类型:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="vtype" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">聚合id:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="vgroupid" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">状态:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="status" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">标题:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="title" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">播放地址:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="playlink" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">图片地址:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="imagelink" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">封面地址:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="infolink" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">分类:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="cate" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">地区:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="area" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">品质:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="quality" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">时长:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="duration" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">年:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="year" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">明星:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="star" value=""/></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">导演:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="director" value=""/></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">概要:</th>
					</tr>
					<tr>
						<td><input type="text"  class="txt" name="summary" value=""/></td>
						<td></td>
                    </tr>
					                </tbody>
			</table>
			<div class="opt"><input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3"></div>
			</form>
		</div>
	</div>
</div>