<header>
    <div class="row">
    <div class="col-sm-9">
        <span class="badge badge-success">管理者</span>
        <h3 style="display: inline">交通費精算表</h3>
    </div>
    <div class="col-sm-3 text-right">
        <button type="button" class="btn page-link text-dark d-inline-block" onclick="history.back()" >Back</button>
        <?php echo $this->Html->link('<button class="btn btn-gold">ユーザー一覧</button>',
                                          array('controller' => 'users', 'action' => 'index'),
                                          array('escape' => false));
        ?>
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
					<table class="table">
    					<th>定期区間</th>
						<th><?php echo h($login_user['pass_from_station']); ?></th>
						<th><?php echo h($login_user['pass_to_station']); ?></th>
					</table>
				</div>
				<div><?php echo $this->Html->link('<button class="btn btn-myset">Add</button>',
                                                  array('controller' => 'requestdetails', 'action' => 'add', $login_user['id']),
                                                  array('escape' => false));
                ?></div>
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
																		), ['class' => 'myset']);
							?>
							<div class="pull-right">
								¥<?php echo number_format($each_month_request['sum(cost)']); ?>
								<span class="badge btn-myset badge-pill"><?php echo h($each_month_request['count']); ?></span>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
    </div>

<footer></footer>

<?php echo $this->Html->css('mystyle'); ?>
