<h2>申請一覧</h2>
<button onClick = "location.href='<?php echo $this->html->url('/requestdetails/add/'.$select_user_id);?>';">Add</button>
<table border="1">
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

	<?php foreach ($each_user_request_details as $each_user_request_detail) : ?>
			<tr>
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
		    </tr>
	<?php endforeach; ?>
</table>
