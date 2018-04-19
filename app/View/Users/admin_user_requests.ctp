<header>
    <div class="row">
    <div class="col-sm-10"><h3>交通費精算表</h3></div>
    <div class="col-sm-2 text-right">
        <button type="button" class="btn page-link text-dark d-inline-block" onclick="history.back()" >Back</button>
        <button type="button" class="btn btn-primary btn-sm">管理者</button>
    </div>
</header>

<div id="content" class="text-center box24">
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->fetch('content'); ?>
</div>

    <div class="content row">
        <div class="col-sm-3 text-center">
			<div id="profile-area">
				<div class="list-unstyled">
					<li><?php echo h($login_user['yourname']); ?><li>
					<br>
					<li><?php echo h($departments[$login_user['department_id']]); ?></li>
					<br>
					<table class="table table-striped">
    					<th>定期区間</th>
						<th><?php echo h($login_user['pass_from_station']); ?></th>
						<th><?php echo h($login_user['pass_to_station']); ?></th>
					</table>
				</div>
				<div class="margin10"><button class="btn btn-danger pull-right" onClick = "location.href='<?php echo $this->html->url("/requestdetails/add/$login_user[id]");?>';">Add</button></div>
			</div>
		</div>
        <div class="col-sm-9">
			<div id="requests-area">
				<h3>申請一覧</h3>
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
																		), ['class' => 'gold']);
							?>
							<div class="pull-right">
								¥<?php echo number_format($each_month_request['sum(cost)']); ?>
								<span class="badge badge-danger badge-pill"><?php echo h($each_month_request['count']); ?></span>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
    </div>

<footer></footer>

<?php echo $this->Html->css('mystyle'); ?>
