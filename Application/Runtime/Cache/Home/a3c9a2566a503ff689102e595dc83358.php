<?php if (!defined('THINK_PATH')) exit();?><html>
	<head>
		<title>点度后台管理</title>
		<meta charset="utf-8">
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
<meta name="keywords" content="点度keywords" />
<meta name="description" content="点度科技">
<link rel="shortcut icon" href="/usedcar/Public/logo.png" type="image/x-icon" />
<link rel="stylesheet" href="/usedcar/Public/styles/reset.css">
<!-- <link rel="stylesheet" href="/usedcar/Public/styles/bootstrap.css"> -->
<link rel="stylesheet" href="/usedcar/Public/styles/bootstrap.min.css">
<!-- <link rel="stylesheet" href="/usedcar/Public/styles/bootstrap-theme.css"> -->
<link rel="stylesheet" href="/usedcar/Public/styles/bootstrap-theme.min.css">
<link rel="stylesheet" href="/usedcar/Public/styles/font-awesome.css">
<link rel="stylesheet" href="/usedcar/Public/styles/font-awesome-ie7.css">
<link rel="stylesheet" href="/usedcar/Public/styles/jquery-ui.min.css">
<link rel="stylesheet" href="/usedcar/Public/styles/jquery-ui.structure.min.css">
<link rel="stylesheet" href="/usedcar/Public/styles/jquery-ui.theme.min.css">
<link rel="stylesheet" href="/usedcar/Public/styles/main.css">
<script src="/usedcar/Public/scripts/jquery-3.1.1.min.js"></script>
<script src="/usedcar/Public/scripts/jquery-ui.min.js"></script>
<script src="/usedcar/Public/scripts/bootstrap.js"></script>
<script src="/usedcar/Public/scripts/jagoFunction.js"></script>
<script src="/usedcar/Public/scripts/main.js"></script>

<!-- 腾讯地图API -->
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
		<style>
			table tbody tr { transition: all 0.3s ease-in-out; }
			table tbody tr td span { display: inline-block; padding: 0 3px; }
			.no-view { background: #2e3252 !important; color: #fff !important; border-top: 1px solid #fff; }
			.no-view span i { color: #fff; }
			.detail-control { margin-top: 5px; }
			.detail-control .detail-add { width: 40%; margin-right: 10px; }
			.detail-control select.detail-add { width: 15%; margin-right: 10px; }
			.member-entry { margin-top: 5px; display: inline-block; width: 100%; }
			.member-entry input { width: 27%; margin-right: 3.33%; }
			.jagoFancybox .box { width: 45%; }
			.jagoFancybox .box .box-left { width: 49%; float: left; }
			.jagoFancybox .box .box-right { width: 49%; float: right; }

			.style-car-brand-ctn span { margin: 1px 2px; }
		</style>
	</head>
	<body>
		<div class="wrapper admin">
			<?php if($_GET['status'] == 'success'): ?>
	<script>cusAlert('success', '<?php echo $_GET['info']; ?>');</script>
<?php elseif($status == 'fail'): ?>
	<script>cusAlert('warning', '<?php echo $info; ?>');</script>
<?php elseif($status == 'success'): ?>
	<script>cusAlert('success', '<?php echo $info; ?>');</script>
<?php elseif($_GET['status'] == 'fail'): ?>
	<script>cusAlert('warning', '<?php echo $_GET['info']; ?>');</script>
<?php endif; ?>
			<div class="header">
	<div class="left pull-left">
		<img class="logo" src="/usedcar/Public/images/other/logo.png" alt="">
	</div>
	<div class="right pull-right">
		<ul>
			<li>
				<p><i class="icon-user"></i> <?php echo session('account'); ?></p>
			</li>
			<li>
				<p><?php echo session('name'); ?></p>
			</li>
			<li>
				<p><a href="<?php echo U('Admin/logout');?>"><i class="icon-signout"></i> 注销</a></p>
			</li>
		</ul>
	</div>
	<div class="clear"></div>
</div>
			<div class="middle-content">
				<style>
	.wrapper.admin .middle-content .sidebar ul li .second-level-menu { height: 0; transition: all 0.5s ease-in-out; opacity: 0; overflow: hidden; }
	.wrapper.admin .middle-content .sidebar ul li .second-level-menu.active { opacity: 1; }
	.wrapper.admin .middle-content .sidebar ul li .second-level-menu .arrow { margin-right: 30px; }
	.wrapper.admin .middle-content .sidebar ul li .second-level-menu a  { /*background: #2c373c;*/ background: #2c373c; padding: 8px 20%; transition: all 0.3s ease-in-out; font-weight: lighter; color: #fff; }
	.wrapper.admin .middle-content .sidebar ul li .second-level-menu a:hover { padding: 8px 20%; color: #fed926; }
	.wrapper.admin .middle-content .sidebar ul li.active .first-level .arrow i,
	.wrapper.admin .middle-content .sidebar ul li:hover .first-level .arrow i { color: #0173bc !important; }
	.wrapper.admin .middle-content .sidebar ul li.active .badge.bg-yellow,
	.wrapper.admin .middle-content .sidebar ul li:hover .badge.bg-yellow { background: #37b4ca; color: #fff; margin-top: -5px; }
</style>
<div class="sidebar pull-left">
	<ul>		
		<li class="<?php if($pageName == 'dashboard' || empty($pageName)){ echo 'active'; } ?>">
			<div class="relative first-level">
				<p>
					<a href="<?php echo U('Admin/index', array('pageName' => 'dashboard'));?>">
						<i class="icon-dashboard"></i> 面板
					</a>
				</p>
				<div class="arrow-right-ctn"><div class="cus-table"><div class="cus-table-cell"><div class="arrow"><i class="icon-angle-right white"></i></div></div></div></div>
			</div>
		</li>

		<?php  $Dao = A('Admin'); ?>

		 <li class="<?php if($pageName == 'car'){ echo 'active'; } ?>">
			<div class="relative first-level">
				<p><a href="<?php echo U('Admin/toCar', array('pageName' => 'car'));?>"><i class="icon-truck"></i> 汽车管理</a></p>
				<div class="arrow-right-ctn"><div class="cus-table"><div class="cus-table-cell"><div class="arrow"><i class="icon-angle-right white"></i></div></div></div></div>
			</div>
		</li>

		 <li class="<?php if($pageName == 'hotel'){ echo 'active'; } ?>">
			<div class="relative first-level">
				<p><a href="<?php echo U('Admin/toHotel', array('pageName' => 'hotel'));?>"><i class="icon-desktop"></i> 酒店管理</a></p>
				<div class="arrow-right-ctn"><div class="cus-table"><div class="cus-table-cell"><div class="arrow"><i class="icon-angle-right white"></i></div></div></div></div>
			</div>
		</li>

		 <li class="<?php if($pageName == 'message'){ echo 'active'; } ?>">
			<div class="relative first-level">
				<p><a href="<?php echo U('Admin/toMessage', array('pageName' => 'message'));?>"><i class="icon-tags"></i> 订单信息 <?php if($result != 0): ?><span class="badge message-new-all bg-yellow"><?php echo $result; ?></span><?php endif; ?></a></p>
				<div class="arrow-right-ctn"><div class="cus-table"><div class="cus-table-cell"><div class="arrow"><i class="icon-angle-right white"></i></div></div></div></div>
			</div>
		</li>
		<!-- <li class="<?php if($pageName == 'message'){ echo 'active'; } ?>">
			<div class="relative first-level">
				<p><a href="<?php echo U('Admin/toMessage', array('pageName' => 'message'));?>"><i class="icon-envelope"></i> 用户留言 <?php if($result != 0): ?><span class="badge message-new-all bg-yellow"><?php echo $result; ?></span><?php endif; ?></a></p>
				<div class="arrow-right-ctn"><div class="cus-table"><div class="cus-table-cell"><div class="arrow"><i class="icon-angle-right white"></i></div></div></div></div>
			</div>
		</li> -->
		<!-- <li class="<?php if($pageName == 'information'){ echo 'active'; } ?>">
			<div class="relative first-level">
				<p><a href="<?php echo U('Admin/toInformation', array('pageName' => 'information'));?>"><i class="icon-list-alt"></i> 资讯管理</a></p>
				<div class="arrow-right-ctn"><div class="cus-table"><div class="cus-table-cell"><div class="arrow"><i class="icon-angle-right white"></i></div></div></div></div>
			</div>
		</li>

		<li class="<?php if($pageName == 'download'){ echo 'active'; } ?>">
			<div class="relative first-level">
				<p><a href="<?php echo U('Admin/toDownload', array('pageName' => 'download'));?>"><i class="icon-download-alt"></i> 资料下载</a></p>
				<div class="arrow-right-ctn"><div class="cus-table"><div class="cus-table-cell"><div class="arrow"><i class="icon-angle-right white"></i></div></div></div></div>
			</div>
		</li>

		<li class="<?php if($pageName == 'frontend'){ echo 'active'; } ?>">
			<div class="relative first-level">
				<p><a class="first-level-menu" href="<?php echo U('Admin/toFrontend', array('pageName' => 'frontend'));?>"><i class="icon-desktop"></i> 前台管理</a></p>
				<div class="arrow-right-ctn"><div class="cus-table"><div class="cus-table-cell"><div class="arrow"><i class="icon-angle-right white"></i></div></div></div></div>
			</div>
			<div class="second-level-menu frontend">
				<div class="relative">
					<p><a href="<?php echo U('Admin/toFrontend', array('pageName' => 'frontend', 'isChild' => 'true', 'childName' => 'homepage'));?>">首页</a></p>
				</div>
				<div class="relative">
					<p><a href="<?php echo U('Admin/toFrontend', array('pageName' => 'frontend', 'isChild' => 'true', 'childName' => 'curriculum'));?>">课程</a></p>
				</div>
				<div class="relative">
					<p><a href="<?php echo U('Admin/toFrontend', array('pageName' => 'frontend', 'isChild' => 'true', 'childName' => 'about'));?>">关于美高</a></p>
				</div>
				<div class="relative">
					<p><a href="<?php echo U('Admin/toFrontend', array('pageName' => 'frontend', 'isChild' => 'true', 'childName' => 'partner'));?>">合作伙伴</a></p>
				</div>
				<div class="relative">
					<p><a href="<?php echo U('Admin/toFrontend', array('pageName' => 'frontend', 'isChild' => 'true', 'childName' => 'center'));?>">个人中心</a></p>
				</div>
			</div>
		</li> -->

		<?php if(session('level') == 10): ?>
			<li class="<?php if($pageName == 'accountManage'){ echo 'active'; } ?>">
				<div class="relative first-level">
					<p><a href="<?php echo U('Admin/toAccountManage', array('pageName' => 'accountManage'));?>"><i class="icon-group"></i> 账号管理</a></p>
					<div class="arrow-right-ctn"><div class="cus-table"><div class="cus-table-cell"><div class="arrow"><i class="icon-angle-right white"></i></div></div></div></div>
				</div>
			</li>
		<?php endif; ?>
	</ul>
</div>


<script>
(function ($) {
	$(function() {
		$('.first-level-menu').on('click', function() {
			var isAcitve = $(this).hasClass('active');
			if (isAcitve) {
				$(this).removeClass('active');
				$(this).closest('li').find('.second-level-menu').css({'height': '0'});
				$(this).closest('li').find('.second-level-menu').removeClass('active');
				$(this).parent().next().find('.arrow').removeClass('active');
			}else {
				var secondCount = $(this).closest('li').find('.second-level-menu').children().length;
				var secondheight = $(this).closest('li').find('.second-level-menu div').height();
				var curHeight = secondCount * secondheight;
				$(this).addClass('active');
				$(this).closest('li').find('.second-level-menu').css({'height': curHeight});
				$(this).closest('li').find('.second-level-menu').addClass('active');
				$(this).parent().next().find('.arrow').addClass('active');
			}

			return false;
		});
	});
})(jQuery);
</script>
				<div class="content pull-right">
					<div class="message">
						<h1 class="headline text-center bold">汽车管理</h1>

						<div class="tools">
							<ul>
								<li>
									<p><a class="btn btn-tools btn-car-add" href="#"><i class="icon-plus-sign-alt"></i> 汽车添加</a></p>
								</li>
								<li>
									<p><a class="btn btn-tools btn-brand-add" href="#"><i class="icon-plus-sign-alt"></i> 品牌设置</a></p>
								</li>
								<li>
									<p><a class="btn btn-tools btn-style-add" href="#"><i class="icon-plus-sign-alt"></i> 车型设置</a></p>
								</li>
							</ul>
						</div>
						
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<td width="10%">城市</td>
									<td width="7%">分类</td>
									<td width="10%">发布时间</td>
									<td width="13%">品牌</td>
									<td width="10%">车型</td>
									<td width="10%">详情</td>
									<td width="10%">购车年月</td>
									<td width="10%">里程</td>
									<td width="10%">价格</td>
									<td width="10%">操作</td>
								</tr>
							</thead>
							<tbody>
								<?php  $Dao = A('Admin'); $result = $Dao -> carSelectAll(); forEach($result as $key => $val): ?>
								 <tr>
								 	<td><?php echo $val['city_name']; ?></td>
								 	<td><?php if($val['category'] == '1'){ echo '新车'; } if($val['category'] == '2'){ echo '二手车'; } ?></td>
								 	<td><?php echo $val['publish_time'] ?></td>
								 	<td><?php echo $val['brand']['name']; ?></td>
								 	<td><?php echo $val['brand']['style']; ?></td>
								 	<td><?php echo $val['detail']; ?></td>
								 	<td>
								 		<?php  $buyTimeYear = substr($val['buy_time'],0,4); $buyTimeMonth = substr($val['buy_time'],4,2); echo $buyTimeYear.'年'.$buyTimeMonth.'月'; ?>
								 	</td>
								 	<td><?php echo $val['mileage']; ?>万</td>
								 	<td><?php echo $val['price']; ?>万</td>
								 	<td>
								 		<span><a href="<?php echo U('Admin/toCar', array('isChild' => 'true', 'childName' => 'view_car', 'pageName' => 'car', 'pageId' => $val['id']));?>" title="编辑" data-id="<?php echo $val['id']; ?>" data-tag="<?php echo $val['name'] ?>" class=""><i class="icon-edit"></i></a></span>
								 		<?php if(session('level') == 10): ?>
								 			<span><a title="删除" data-id="<?php echo $val['id']; ?>" data-tag="<?php echo $val['name'] ?>" class="jagoFancybox-delete" href="#"><i class="icon-trash"></i></a></span>
								 		<?php endif ?>
								 	</td>
								 </tr>
								<?php endforeach; ?>
							</tbody>
						</table>

					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>

		<div class="jagoFancybox">
			<div class="cus-table">
				<div class="cus-table-cell">
					<div class="box box-1">
						<div class="jagoFancybox-confirm-ctn">
							<div class="jagoFancybox-head">
								<h4 class="title">车辆删除 <a href="#"><i class="icon-remove-sign pull-right jagoFancybox-cancel"></i></a></h4>
							</div>
							<form action="<?php echo U('Admin/carDelete');?>">
								<div class="jagoFancybox-body">
									<p class="jagoFancybox-headline">是否删除?</p>
									<p class="jagoFancybox-content bold"></p>
									<div class="none">
										<input type="text" name="deleteId">
									</div>
								</div>
								<div class="jagoFancybox-btn">
									<button type="submit" class="btn btn-primary pull-left" href="#">确认</button>
									<a class="btn btn-default pull-right jagoFancybox-cancel" href="#">取消</a>
								</div>
							</form>
						</div>
					</div>

					<div class="box box-2">
						<div class="add-hotel panel panel-default">
							<div class="panel-heading">
								<h4 class="title">汽车添加 <a href="#"><i class="icon-remove-sign pull-right jagoFancybox-cancel"></i></a></h4>
							</div>
							<div class="panel-body">
								<form class="adminLogin inblk width-all" enctype="multipart/form-data" role="form" action="<?php echo U('Admin/carAdd');?>" method="POST">
									<div class="box-left">
										  <div class="form-group">
										    <label for="publish_time">发布时间</label>
										    <input name="publish_time" type="text" class="form-control" id="publish_time" placeholder="例如: 2017/10/1" autocomplete="off" required="" readonly="readonly">
										  </div>

										  <div class="form-group">
										    <label for="city">城市</label>
										    <select class="form-control tx-city" name="city" required="true"></select>
										  </div>

										  <div class="form-group">
										    <label for="category">所属类别</label>
										    <select class="form-control" name="category" required="true">
											  <option value="1">新车</option>
											  <option value="2">二手车</option>
											</select>
										  </div>

										  <div class="form-group">
										    <label for="brand_id">品牌</label>
										    <select class="form-control brandSelect" name="brand_id" required="true">
										      <?php  $result = $Dao -> carBrandSelectAll(); foreach($result as $key => $val): ?>
												  <option value="<?php echo $val['id'] ?>"><?php echo $val['name']; ?></option>
										 	  <?php endforeach; ?>
											</select>
										  </div>

										  <div class="form-group">
										    <label for="style_id">车型</label>
										    <select class="form-control styleSelect" name="style_id" required="true">
										      <?php  $result = $Dao -> carStyleSelectByBrandId(); foreach($result as $key => $val): ?>
												  <option value="<?php echo $val['id'] ?>"><?php echo $val['name']; ?></option>
										 	  <?php endforeach; ?>
											</select>
										  </div>

										  <div class="form-group">
										    <label for="detail">详情</label>
										    <input name="detail" type="text" class="form-control" id="detail" placeholder="例如: 2.0T 手自一体" autocomplete="off" required="">
										  </div>

										  <div class="form-group">
										    <label for="buy_time">购车年月</label>
										    <input name="buy_time" type="text" class="form-control" id="buy_time" placeholder="例如: 201711 (6位)" autocomplete="off" required="">
										  </div>

										  <div class="form-group">
										    <label for="cost_price">购车总价</label>
										    <input name="cost_price" type="text" class="form-control" id="cost_price" placeholder="例如: 20 (单位: 万)" autocomplete="off" required="">
										  </div>
									</div>

									<div class="box-right">
										  <div class="form-group">
										    <label class="" for="image">图片1</label>
										    <input name="photo1" type="file" class="form-control" placeholder="图片1" autocomplete="off" required>
										  </div>

										  <div class="form-group">
										    <label for="color">车辆颜色</label>
										    <input name="color" type="text" class="form-control" id="color" placeholder="例如: 蓝色" autocomplete="off" required="">
										  </div>

										  <div class="form-group">
										    <label for="situation">保养情况</label>
										    <input name="situation" type="text" class="form-control" id="situation" placeholder="例如: 4S店非定期" autocomplete="off" required="">
										  </div>

										  <div class="form-group">
										    <label for="factory">生产厂商</label>
										    <input name="factory" type="text" class="form-control" id="factory" placeholder="例如: 华晨宝马" autocomplete="off" required="">
										  </div>

										  <div class="form-group">
										    <label for="mileage">里程</label>
										    <input name="mileage" type="text" class="form-control" id="mileage" placeholder="例如: 20 (单位: 万)" autocomplete="off" required="">
										  </div>

										  <div class="form-group">
										    <label for="price">售价</label>
										    <input name="price" type="text" class="form-control" id="price" placeholder="例如: 20 (单位: 万)" autocomplete="off" required="">
										  </div>

										  <div class="form-group">
										    <label for="advantage">配置亮点</label>
										    <input name="advantage" type="text" class="form-control" id="advantage" placeholder="配置亮点  (请在下方逐个添加)" autocomplete="off" readonly="readonly" required="">
										    <div class="detail-control">
										    	<input class="detail-add form-control col-sm-5" type="text" placeholder="在此输入">
										    	<button class="btn btn-detail-add btn-primary" href="#">添加</button>
										    	<button class="btn btn-detail-clear" href="#">清空</button>
										    </div>
										  </div>
										 

										  <!-- <div class="form-group">
										    <label class="" for="image">图片2</label>
										    <input name="photo2" type="file" class="form-control" placeholder="图片2" autocomplete="off">
										  </div>

										  <div class="form-group">
										    <label class="" for="image">图片3</label>
										    <input name="photo3" type="file" class="form-control" placeholder="图片3" autocomplete="off">
										  </div>

										  <div class="form-group">
										    <label class="" for="image">图片4</label>
										    <input name="photo4" type="file" class="form-control" placeholder="图片4" autocomplete="off">
										  </div>

										  <div class="form-group">
										    <label class="" for="image">图片5</label>
										    <input name="photo5" type="file" class="form-control" placeholder="图片5" autocomplete="off">
										  </div>

										  <div class="form-group">
										    <label class="" for="image">图片6</label>
										    <input name="photo6" type="file" class="form-control" placeholder="图片6" autocomplete="off">
										  </div> -->
									</div>
									<div class="text-center width-all clear">
									  	<button type="submit" class="btn btn-primary btn-submit"><i class="icon-plus-sign-alt"></i> 添加</button>
									  	<p class="error-msg red pull-right"></p>
									  </div>
								</form>
							</div>
						</div>
					</div>

					<div class="box box-3" style="width: 500px;">
						<div class="theme-setting-ctn panel panel-default">
							<div class="panel-heading">
								<h4 class="title">品牌设置 <a href="#"><i class="icon-remove-sign pull-right jagoFancybox-cancel"></i></a></h4>
							</div>
							<div class="panel-body">
								<div class="menu-create-entry">
									<label for="homestyle">当前品牌</label>
									<!-- <h4 class="title bold">当前品牌  <span class="p-small green lighter first-menu-ajax-return"></span></h4> -->

									<?php  $result = $Dao -> carBrandSelectAll(); foreach($result as $key => $val): ?>
									<form class="first-level-create-form brand-setting-select-brand" style="max-height: 220px; overflow-y: auto;" role="form" action='<?php echo U("Admin/carBrandUpdate");?>' method="POST">
										
										  	<div class="inblk width-all current-menu-entry" style="margin-bottom: 12px;">
										  		<div style="display: none;">
										  			<input type="text" name="brand_id" value="<?php echo $val['id']; ?>">
										  		</div>
										  		<div class="col-sm-4 pull-left left">
										  			<input name="brand_name" id="<?php echo $val['id']; ?>" class="form-control" type="text" value="<?php echo $val['name']; ?>" disabled>
										  		</div>
										  		<div class="col-sm-3 pull-left left">
										  			<select name="initial" class="detail-add form-control col-sm-4" disabled required>
											    		<option <?php if($val['initial']== 'A'){echo 'selected';} ?> value="A">A</option>
											    		<option <?php if($val['initial']== 'B'){echo 'selected';} ?> value="B">B</option>
											    		<option <?php if($val['initial']== 'C'){echo 'selected';} ?> value="C">C</option>
											    		<option <?php if($val['initial']== 'D'){echo 'selected';} ?> value="D">D</option>
											    		<option <?php if($val['initial']== 'E'){echo 'selected';} ?> value="E">E</option>
											    		<option <?php if($val['initial']== 'F'){echo 'selected';} ?> value="F">F</option>
											    		<option <?php if($val['initial']== 'G'){echo 'selected';} ?> value="G">G</option>
											    		<option <?php if($val['initial']== 'H'){echo 'selected';} ?> value="H">H</option>
											    		<option <?php if($val['initial']== 'I'){echo 'selected';} ?> value="I">I</option>
											    		<option <?php if($val['initial']== 'J'){echo 'selected';} ?> value="J">J</option>
											    		<option <?php if($val['initial']== 'K'){echo 'selected';} ?> value="K">K</option>
											    		<option <?php if($val['initial']== 'L'){echo 'selected';} ?> value="L">L</option>
											    		<option <?php if($val['initial']== 'M'){echo 'selected';} ?> value="M">M</option>
											    		<option <?php if($val['initial']== 'N'){echo 'selected';} ?> value="N">N</option>
											    		<option <?php if($val['initial']== 'O'){echo 'selected';} ?> value="O">O</option>
											    		<option <?php if($val['initial']== 'P'){echo 'selected';} ?> value="P">P</option>
											    		<option <?php if($val['initial']== 'Q'){echo 'selected';} ?> value="Q">Q</option>
											    		<option <?php if($val['initial']== 'R'){echo 'selected';} ?> value="R">R</option>
											    		<option <?php if($val['initial']== 'S'){echo 'selected';} ?> value="S">S</option>
											    		<option <?php if($val['initial']== 'T'){echo 'selected';} ?> value="T">T</option>
											    		<option <?php if($val['initial']== 'U'){echo 'selected';} ?> value="U">U</option>
											    		<option <?php if($val['initial']== 'V'){echo 'selected';} ?> value="V">V</option>
											    		<option <?php if($val['initial']== 'W'){echo 'selected';} ?> value="W">W</option>
											    		<option <?php if($val['initial']== 'X'){echo 'selected';} ?> value="X">X</option>
											    		<option <?php if($val['initial']== 'Y'){echo 'selected';} ?> value="Y">Y</option>
											    		<option <?php if($val['initial']== 'Z'){echo 'selected';} ?> value="Z">Z</option>
											    	</select>
										  		</div>
										  		<div class="col-sm-4 pull-right right">
										  			<span class="box-icon"><a class="btn-edit" href="#" data-id="<?php echo $val['id']; ?>"><i class="icon-edit"></i></a></span>
										  			<span class="box-icon"><button class="none btn-save button-icon" type="submit" href="#" data-id="<?php echo $val['id']; ?>"><i class="icon-save"></i></button></span>
										  			<span class="box-icon"><a class="btn-delete jagoFnacybox-delete-carbrand" href="#" data-tag="<?php echo $val['name']; ?>" data-id="<?php echo $val['id']; ?>"><i class="icon-trash"></i></a></span>
										  		</div>
										  	</div>
									  	
									</form>
									<?php endforeach; ?>

									<form class="brand-add-form" style="max-height: 220px; overflow-y: auto; margin-top: 20px;" role="form" action="<?php echo U('Admin/carBrandAdd');?>" method="POST">
										<div class="form-group">
										    <label for="brand_name">品牌添加</label>
										    <div class="detail-control">
										    	<input name="brand_name" class="detail-add form-control col-sm-4" type="text" placeholder="品牌" autocomplete="off" required>
										    </div>
										</div>
										<div class="form-group">
										    <div class="detail-control">
										    	<input name="style_name" class="detail-add form-control col-sm-4" type="text" placeholder="车型" autocomplete="off" required>
										    </div>
										</div>
										<div class="form-group">
										    <div class="detail-control">
										    	<select name="initial" class="detail-add form-control col-sm-4" required>
										    		<option value="A">A</option>
										    		<option value="B">B</option>
										    		<option value="C">C</option>
										    		<option value="D">D</option>
										    		<option value="E">E</option>
										    		<option value="F">F</option>
										    		<option value="G">G</option>
										    		<option value="H">H</option>
										    		<option value="I">I</option>
										    		<option value="J">J</option>
										    		<option value="K">K</option>
										    		<option value="L">L</option>
										    		<option value="M">M</option>
										    		<option value="N">N</option>
										    		<option value="O">O</option>
										    		<option value="P">P</option>
										    		<option value="Q">Q</option>
										    		<option value="R">R</option>
										    		<option value="S">S</option>
										    		<option value="T">T</option>
										    		<option value="U">U</option>
										    		<option value="V">V</option>
										    		<option value="W">W</option>
										    		<option value="X">X</option>
										    		<option value="Y">Y</option>
										    		<option value="Z">Z</option>
										    	</select>
										    </div>
										</div>
										<div class="detail-control">
									    	<div>
									    		<button class="btn btn-primary" href="#">添加</button>
									    	</div>
									    </div>
									</form>
								</div>
							</div>
						</div>
					</div>

					<div class="box box-4" style="width: 500px;">
						<div class="theme-setting-ctn panel panel-default">
							<div class="panel-heading">
								<h4 class="title">车型设置 <a href="#"><i class="icon-remove-sign pull-right jagoFancybox-cancel"></i></a></h4>
							</div>
							<div class="panel-body">
								<div class="menu-create-entry">
									<div class="form-group">
										<label class="contorl-label">品牌选择</label>
										<select class="form-control select-car-brand">
											<?php  $result = $Dao -> carBrandSelectAll(); foreach($result as $key => $val): ?>
											 	<?php if($key == 0){ $defaultBrandId = $val['id']; } ?>
												<option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
											<?php endforeach; ?>
										</select>
									</div>

									<label for="homestyle">当前车型</label>
									<form class="first-level-create-form style-car-brand-ctn" style="max-height: 220px; overflow-y: auto;" role="form" action="" method="POST">										
										<?php  $result = $Dao -> carStyleSelectByBrandId($defaultBrandId); foreach($result as $key => $val): ?>
										  	<div class="inblk width-all current-menu-entry" style="margin-bottom: 12px;">
										  		<div class="col-sm-8 pull-left left">
										  			<input id="<?php echo $val['id']; ?>" class="form-control" type="text" value="<?php echo $val['name']; ?>" disabled>
										  		</div>
										  		<div class="col-sm-4 pull-right right">
										  			<span><a class="btn-edit" href="#" data-id="<?php echo $val['id']; ?>"><i class="icon-edit"></i></a></span>
										  			<span><a class="none btn-save" href="#" data-id="<?php echo $val['id']; ?>"><i class="icon-save"></i></a></span>
										  			<span><a class="btn-delete jagoFnacybox-delete-carstyle" href="#" data-brandid="<?php echo $defaultBrandId; ?>" data-tag="<?php echo $val['name']; ?>" data-id="<?php echo $val['id']; ?>"><i class="icon-trash"></i></a></span>
										  		</div>
										  	</div>
									  	<?php endforeach; ?>
									</form>

									<form class="style-add-form" style="max-height: 220px; overflow-y: auto; margin-top: 20px;" role="form" action="<?php echo U('Admin/carStyleAdd');?>" method="POST">
										<div style="display: none;"><input type="text" name="brand_id" value="<?php echo $defaultBrandId; ?>"></div>
										<div class="form-group">
										    <label for="homestyle">车型添加 (至当前品牌)</label>
										    <div class="detail-control">
										    	<input name="style_name" class="detail-add form-control col-sm-8" type="text" placeholder="车型" required autocomplete="off">
										    	<button class="btn btn-primary" href="#">添加</button>
										    </div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>

					<div class="box box-5">
						<div class="jagoFancybox-confirm-ctn">
							<div class="jagoFancybox-head">
								<h4 class="title">品牌删除 <a href="#"><i class="icon-remove-sign pull-right jagoFancybox-cancel"></i></a></h4>
							</div>
							<form action="<?php echo U('Admin/carBrandDelete');?>">
								<div class="jagoFancybox-body">
									<p class="jagoFancybox-headline">是否删除?</p>
									<p class="jagoFancybox-content bold"></p>
									<div class="none">
										<input type="text" name="deleteId">
									</div>
								</div>
								<div class="jagoFancybox-btn">
									<button type="submit" class="btn btn-primary pull-left" href="#">确认</button>
									<a class="btn btn-default pull-right jagoFancybox-cancel" href="#">取消</a>
								</div>
							</form>
						</div>
					</div>

					<div class="box box-6">
						<div class="jagoFancybox-confirm-ctn">
							<div class="jagoFancybox-head">
								<h4 class="title">车型删除 <a href="#"><i class="icon-remove-sign pull-right jagoFancybox-cancel"></i></a></h4>
							</div>
							<form action="<?php echo U('Admin/carStyleDelete');?>">
								<div class="jagoFancybox-body">
									<p class="jagoFancybox-headline">是否删除?</p>
									<p class="jagoFancybox-content bold"></p>
									<div class="none">
										<input type="text" name="deleteId">
										<input type="text" name="delete_brand_id">
									</div>
								</div>
								<div class="jagoFancybox-btn">
									<button type="submit" class="btn btn-primary pull-left" href="#">确认</button>
									<a class="btn btn-default pull-right jagoFancybox-cancel" href="#">取消</a>
								</div>
							</form>
						</div>
					</div>

				</div>
			</div>
		</div>

		<script>
		(function($) {
			$(function() {
				//配置亮点
				$('.btn-detail-clear').on('click', function() {
					$('input[name="advantage"]').val('');

					return false;
				});

				//配置亮点添加按钮
				$('.btn.btn-detail-add').on('click', function() {
					var content = $('input.detail-add').val();
					var str;
					if (content != '') {
						str = $('input[name="advantage"]').val();
						if (str == '') {
							str += content;
						}else {
							str += '、' + content;
						}						
						$('input[name="advantage"]').val(str);
						$('input.detail-add').val('');
					}

					return false;
				});

				//当前时间
				$('.box.box-2 input[name="publish_time"]').val(currentTimeDate());

				cityAdd();

				//品牌设置
				$('.btn-brand-add').on('click', function() {
					$('.jagoFancybox').addClass('active');
					$('.jagoFancybox .box-3').addClass('active');
					return false;
				});

				//品牌设置-编辑
				$('.brand-setting-select-brand').on('click', '.btn-edit', function() {
					var id = $(this).data('id');
					$(this).closest('.current-menu-entry').find('>div >input').removeAttr('disabled');
					$(this).closest('.current-menu-entry').find('>div >select').removeAttr('disabled');

					$(this).addClass('none');
					$(this).parent().next().find('.btn-save').removeClass('none');
					return false;
				});

				//品牌设置-保存
				// $('.brand-setting-select-brand').on('click', '.btn-save', function() {
				// 	var that = $(this);
				// 	var id = $(this).data('id');
				// 	var name = $(this).closest('.current-menu-entry').find('>div >input').val();
				// 	$.ajax({
				// 		type: 'POST',
				// 		url: '/usedcar/index.php/Home/Admin/carBrandNameUpdateAjaxReturn',
				// 		data: {'id': id, 'name': name}
				// 	}).done(function(res){
				// 		if (res.status == 'success') {
				// 			that.closest('.current-menu-entry').find('>div >input').attr('disabled', '');
				// 			$('.member-ajax-return').html('会员类别更新成功!')
				// 		}else {
				// 			that.closest('.current-menu-entry').find('>div >input').attr('disabled', '');
				// 			$('.member-ajax-return').html('未修改!')
				// 		}
				// 		that.addClass('none');
				// 		that.parent().prev().find('.btn-edit').removeClass('none');
				// 	});
				// 	return false;
				// });

				//车型设置
				$('.btn-style-add').on('click', function() {
					$('.jagoFancybox').addClass('active');
					$('.jagoFancybox .box-4').addClass('active');
					return false;
				});

				//车型设置-编辑
				$('.style-car-brand-ctn').on('click', '.btn-edit', function() {
					var id = $(this).data('id');
					$(this).closest('.current-menu-entry').find('>div >input').removeAttr('disabled');

					$(this).addClass('none');
					$(this).parent().next().find('.btn-save').removeClass('none');
					return false;
				});

				//车型设置-保存
				$('.style-car-brand-ctn').on('click', '.btn-save', function() {
					var that = $(this);
					var id = $(this).data('id');
					var name = $(this).closest('.current-menu-entry').find('>div >input').val();
					$.ajax({
						type: 'POST',
						url: '/usedcar/index.php/Home/Admin/carStyleNameUpdateAjaxReturn',
						data: {'id': id, 'name': name}
					}).done(function(res){
						if (res.status == 'success') {
							that.closest('.current-menu-entry').find('>div >input').attr('disabled', '');
							$('.member-ajax-return').html('会员类别更新成功!')
						}else {
							that.closest('.current-menu-entry').find('>div >input').attr('disabled', '');
							$('.member-ajax-return').html('未修改!')
						}
						that.addClass('none');
						that.parent().prev().find('.btn-edit').removeClass('none');
					});
					return false;
				});

				//车型设置-品牌选择
				$('.select-car-brand').on('change', function() {
					var brandId = $(this).val();
					$('.style-add-form input[name="brand_id"]').val(brandId);

					$.ajax({
						url: '<?php echo U("Admin/carStyleSelectByBrandIdAjaxReturn");?>',
						type: 'POST',
						data: {brand_id: brandId}
					}).done(function(res) {
						$('.style-car-brand-ctn').html('');
						res.forEach(function(val, key) {
							$('.style-car-brand-ctn').append(
								'<div class="inblk width-all current-menu-entry" style="margin-bottom: 12px;">' +
							  		'<div class="col-sm-8 pull-left left">' +
							  			'<input id="'+ val.id +'" class="form-control" type="text" value="'+ val.name +'" disabled>' +
							  		'</div>' +
							  		'<div class="col-sm-4 pull-right right">' +
							  			'<span><a class="btn-edit" href="#" data-id="'+ val.id +'"><i class="icon-edit"></i></a></span>' +
							  			'<span><a class="none btn-save" href="#" data-id="'+ val.id +'"><i class="icon-save"></i></a></span>' +
							  			'<span><a class="btn-delete jagoFnacybox-delete-carstyle" href="#" data-brandid="'+ brandId +'" data-tag="'+ val.name +'" data-id="'+ val.id +'"><i class="icon-trash"></i></a></span>' +
							  		'</div>' +
							  	'</div>'
							);
						});
						console.log(res);
					});
				});

				//首brand对应style
				var firstStyle = function() {
					var brand_id = $('select.brandSelect').val();
					$.ajax({
						url: '<?php echo U("Admin/carStyleSelectByIdAjaxReturn");?>',
						type: 'POST',
						data: {brand_id: brand_id}
					}).done(function(res) {
						if (res) {
							$('select.styleSelect').html('');
							res.forEach(function(val, key) {
								$('select.styleSelect').append(
									'<option value="'+ val.id +'">'+ val.name +'</option>'
								);
							});
						}
					});
				}
				firstStyle();


				//选择品牌
				$('select.brandSelect').on('change', function() {
					var brand_id = $(this).val();
					$.ajax({
						url: '<?php echo U("Admin/carStyleSelectByIdAjaxReturn");?>',
						type: 'POST',
						data: {brand_id: brand_id}
					}).done(function(res) {
						if (res) {
							$('select.styleSelect').html('');
							res.forEach(function(val, key) {
								$('select.styleSelect').append(
									'<option value="'+ val.id +'">'+ val.name +'</option>'
								);
							});
						}
					});
				});

				$('.btn-car-add').on('click', function() {
					$('.jagoFancybox').addClass('active');
					$('.jagoFancybox .box-2').addClass('active');
					return false;
				});

				$('.jagoFancybox-delete').on('click', function() {
					var id = $(this).data('id');
					var content = $(this).data('tag');

					$('.jagoFancybox').addClass('active');
					$('.jagoFancybox .box-1').addClass('active');
					
					$('.box-1 .jagoFancybox-confirm-ctn input[name="deleteId"]').val(id);
					$('.box-1 .jagoFancybox-confirm-ctn .jagoFancybox-content').html('');
					$('.box-1 .jagoFancybox-confirm-ctn .jagoFancybox-content').append(content);
					return false;
				});

				$('.jagoFnacybox-delete-carbrand').on('click', function() {
					$('.jagoFancybox').removeClass('active');
					$('.jagoFancybox .box').removeClass('active');

					var id = $(this).data('id');
					var content = $(this).data('tag');

					$('.jagoFancybox').addClass('active');
					$('.jagoFancybox .box-5').addClass('active');
					
					$('.box-5 .jagoFancybox-confirm-ctn input[name="deleteId"]').val(id);
					$('.box-5 .jagoFancybox-confirm-ctn .jagoFancybox-content').html('');
					$('.box-5 .jagoFancybox-confirm-ctn .jagoFancybox-content').append(content);
					return false;
				});

				$('.style-car-brand-ctn').on('click', '.jagoFnacybox-delete-carstyle', function() {
					$('.jagoFancybox').removeClass('active');
					$('.jagoFancybox .box').removeClass('active');

					var id = $(this).data('id');
					var brand_id = $(this).data('brandid');
					var content = $(this).data('tag');

					$('.jagoFancybox').addClass('active');
					$('.jagoFancybox .box-6').addClass('active');
					
					$('.box-6 .jagoFancybox-confirm-ctn input[name="deleteId"]').val(id);
					$('.box-6 .jagoFancybox-confirm-ctn input[name="delete_brand_id"]').val(brand_id);
					$('.box-6 .jagoFancybox-confirm-ctn .jagoFancybox-content').html('');
					$('.box-6 .jagoFancybox-confirm-ctn .jagoFancybox-content').append(content);
					return false;
				});

				function cityAdd(){
					var id = '530000';
					$.ajax({
						url: '<?php echo U("Wechat/txCityAreaSelect");?>',
						type: 'POST',
						data: {id: id}
					}).done(function(res) {
						// res = res.result; 
						res = jQuery.parseJSON(res); 
						res = res.result[0];	//城市 结果集
						$('.tx-city').html('');
						res.forEach(function(val,key) {
							$('.tx-city').append(
								'<option value="'+ val.id +' '+ val.fullname +'">'+ val.fullname +'</option>'
							);
						});
					});
				}
			});
		})(jQuery);
		</script>
	</body>
</html>