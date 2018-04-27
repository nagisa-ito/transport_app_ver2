<html>
<body>

	<header>
		<div class="row">
		<div class="col-sm-10">
			<h4>交通費精算表</h4>
		</div>
		<div class="col-sm-2">
			<button class="btn btn-danger d-inline-block btn-block" onclick="location.href='<?php echo $this->html->url('/users/logout/'); ?>';">Logout</button>
		</div>
	</header>

	<div class="text-center">
        <?php echo $this->Session->flash(); ?>
    </div>

	<div class="content row">
		<div class="col-sm-3 text-center">
			<div id="profile-area">
				<div class="list-unstyled">
					<li><?php echo h($login_user['yourname']); ?><li>
					<br>
					<li><?php echo h($departments[$login_user['department_id']]); ?></li>
					<br>
					<table class="table table-bordered">
    					<th>定期区間</th>
						<th><?php echo h($login_user['pass_from_station']); ?></th>
						<th><?php echo h($login_user['pass_to_station']); ?></th>
					</table>
				</div>
				<div class="row">
					<div class="col-sm-6 offset-sm-3"><button class="btn btn-myset btn-block" onClick = "location.href='<?php echo $this->html->url("/requestdetails/add/$login_user[id]");?>';">Add</button></div>
				</div>
			</div>
		</div>
		<div class="col-sm-9">
			<div id="requests-area">
				<h4>申請一覧</h4>
				<ul class="list-group">
					<?php foreach($group_by_month as $each_month_request) : ?>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							<?php
								$print_date = $each_month_request['date'];
								$print_date = date('Y年m月', strtotime($print_date));
								$login_user_id = $login_user['id'];
								$year_name = $each_month_request['date'];
								echo $this->Html->link($print_date, array(
																			'controller' => 'requestdetails',
																			'action' => "/index/$login_user_id/$year_name/",
																		), ['class' => 'myset']);
							?>
							<div class="pull-right">
								¥<?php echo number_format($each_month_request['sum(cost)']); ?>
								<?php echo $this->Html->link($each_month_request['count']. '件', array(
																										'controller' => 'requestdetails',
																										'action' => "/index/$login_user_id/$year_name/",
																										), ['class' => 'myset']); ?>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>

	<footer class="footer"></footer>

	<?php echo $this->Html->css('mystyle'); ?>
</body>
</html>
