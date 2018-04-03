<h2>申請一覧</h2>

<table border="1">
	<tr>
		<?php foreach ($column_names as $key => $column_name) : ?>
			<?php 
				if($key != 5){
					echo '<th>' . h($column_name) . '</th>'; 
				} else {
					echo '<th colspan="2">' . h($column_name) . '</th>';
				}
			?>
		<?php endforeach; ?>
	</tr>

	<?php foreach ($requestdetails as $requestdetail) : ?>
	<tr>
			<td><?php echo h($requestdetail['RequestDetail']['id'])?></td>
			<td><?php echo h($requestdetail['User']['yourname'])?></td>
			<td><?php echo h($requestdetail['RequestDetail']['date'])?></td>
			<td><?php echo h($requestdetail['RequestDetail']['client'])?></td>
			<td><?php echo h($requestdetail['RequestDetail']['transportation_id'])?></td>
			<td><?php echo h($requestdetail['RequestDetail']['from_station'])?></td>
			<td><?php echo h($requestdetail['RequestDetail']['to_station'])?></td>
			<td><?php echo h($requestdetail['RequestDetail']['cost'])?></td>
			<td><?php echo h($requestdetail['RequestDetail']['oneway_or_round'])?></td>
			<td><?php echo h($requestdetail['RequestDetail']['overview'])?></td>
			<td><?php echo h($requestdetail['RequestDetail']['created'])?></td>
			<td><?php echo h($requestdetail['RequestDetail']['modified'])?></td>
	</tr>
	<?php endforeach; ?>
</table>

