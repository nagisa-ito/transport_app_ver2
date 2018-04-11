<?php pr($login_user); ?>
<div>
<h2>交通費管理システム</h2>
<span>
	ようこそ、<?php echo $login_user['yourname']; ?>さん
	<button onclick="location.href='<?php echo $this->html->url('/users/logout/'); ?>';">Logout</button>
</span>

<h3>申請一覧</h3>
<button onClick = "location.href='<?php echo $this->html->url("/requestdetails/add/$login_user[id]");?>';">Add</button>
<ul>
	<?php foreach($group_by_month as $each_month_request) : ?>
		<li><?php
					$print_str = $each_month_request['date'] . ' (' . $each_month_request['count'] . ')';
					$login_user_id = $login_user['id'];
					$year_name = $each_month_request['date'];
					echo $this->Html->link($print_str, array(
																'controller' => 'requestdetails',
																'action' => "/index/$login_user_id/$year_name/"
															));?>
		</li>
	<?php endforeach; ?>
</ul>
<?php echo $this->Html->css('mystyle.css'); ?>
</div>
