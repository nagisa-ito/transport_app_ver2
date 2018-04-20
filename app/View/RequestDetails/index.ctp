<html>
<body>
	<header>
		<div class="row">
			<div class="col-sm-11"><h3><?php
											if(!empty($each_user_request_details)){
												echo date('Y年m月', strtotime($each_user_request_details[0]['RequestDetail']['date']));
											}
										?>
									</h3></div>
			<div class="col-sm-1"><button class="btn page-link text-dark d-inline-block" onclick="location.href='<?php echo $this->Html->url("/users/index/$login_user_id"); ?>';">Back</button></div>
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
			<div id="total_cost_area">
				合計金額
				<div class="text-right">
					<h3>¥ <?php echo number_format($total_cost); ?></h3>
				</div>
			</div>
			<div id="request-details-area">
			<table class="table table-hover">
				<tr>
					<?php foreach ($column_names as $key => $column_name) : ?>
						<?php
							if($key != 4){
								echo '<th>' . h($column_name) . '</th>';
							} else {
								echo '<th colspan="2">' . h($column_name) . '</th>';
							}
						?>
					<?php endforeach; ?>
				</tr>
				<!--一覧をループで表示-->
				<?php foreach ($each_user_request_details as $each_user_request_detail) : ?>
						<tr id="request_<?php echo h($each_user_request_detail['RequestDetail']['id']); ?>">
							<td><?php echo h($each_user_request_detail['RequestDetail']['id']); ?></td>
							<td><?php echo h($each_user_request_detail['RequestDetail']['date'])?></td>
							<td><?php echo h($each_user_request_detail['RequestDetail']['client'])?></td>
							<td><?php echo h($each_user_request_detail['Transportation']['transportation_name'])?></td>
							<td><?php echo h($each_user_request_detail['RequestDetail']['from_station'])?></td>
							<td><?php echo h($each_user_request_detail['RequestDetail']['to_station'])?></td>
							<td><?php echo h($each_user_request_detail['RequestDetail']['cost'])?></td>
							<td><?php echo h($each_user_request_detail['RequestDetail']['oneway_or_round'])?></td>
							<td><?php echo h($each_user_request_detail['RequestDetail']['overview'])?></td>
							<td><?php echo h($each_user_request_detail['RequestDetail']['created'])?></td>
							<td><?php echo h($each_user_request_detail['RequestDetail']['modified'])?></td>
							<td>
								<?php
									//編集・削除を実行
									echo $this->Html->link('<i class="fas fa-edit"></i>', array('action' => 'edit', $each_user_request_detail['RequestDetail']['id'], $login_user_id, $year_month),
																						  array('escape' => false));
									echo $this->Html->link('<i class="fas fa-trash-alt"></i>', '#',
															array('class' => 'delete', 'data-request-id' => $each_user_request_detail['RequestDetail']['id'], 'escape' => false));

								?>
							</td>
					    </tr>
				<?php endforeach; ?>
			</table>
		</div>
		</div>
	</div>
</table>

	<footer class="footer"></footer>

	<?php echo $this->Html->css('mystyle'); ?>

</body>
</html>
