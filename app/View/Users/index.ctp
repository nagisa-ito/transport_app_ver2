<html>
<body>

	<header>
		<div class="text-right white">
			<button class="btn bth-default" onclick="location.href='<?php echo $this->html->url('/users/logout/'); ?>';">Logout</button>
		</div>
	</header>

	<div class="content row">
		<div class="col-sm-4 text-center" style="background-color: #fff">
			<div id="profile-area">
				<div class="list-unstyled box11">
					<li><?php echo h($departments[$login_user['department_id']]); ?></li>
					<li><?php echo h($login_user['yourname']); ?> さん<li>
					<li>
						定期区間: <?php echo h($login_user['pass_from_station']); ?> 駅 〜
									<?php echo h($login_user['pass_to_station']); ?> 駅
					</li>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div id="requests-area">
				<h3>申請一覧</h3>
				<div class="text-right margin10"><button class="btn btn-danger pull-right" onClick = "location.href='<?php echo $this->html->url("/requestdetails/add/$login_user[id]");?>';">Add</button></div>
				<ul class="list-group">
					<?php foreach($group_by_month as $each_month_request) : ?>
						<li class="list-group-item">
							<?php
								$print_str = $each_month_request['date'] . ' (' . $each_month_request['count'] . ')';
								$login_user_id = $login_user['id'];
								$year_name = $each_month_request['date'];
								echo $this->Html->link($print_str, array(
																			'controller' => 'requestdetails',
																			'action' => "/index/$login_user_id/$year_name/",
																		), ['class' => 'gold']);
							?>
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
