<h2>申請一覧</h2>

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

	<?php foreach ($extracted_request_details as $requestdetail) : ?>
	<tr>
			<td><?php echo h($requestdetail['id'])?></td>
			<td><?php echo h($requestdetail['date'])?></td>
			<td><?php echo h($requestdetail['client'])?></td>
			<td><?php echo h($requestdetail['transportation_id'])?></td>
			<td><?php echo h($requestdetail['from_station'])?></td>
			<td><?php echo h($requestdetail['to_station'])?></td>
			<td><?php echo h($requestdetail['cost'])?></td>
			<td><?php echo h($requestdetail['oneway_or_round'])?></td>
			<td><?php echo h($requestdetail['overview'])?></td>
			<td><?php echo h($requestdetail['created'])?></td>
			<td><?php echo h($requestdetail['modified'])?></td>
	</tr>
	<?php endforeach; ?>
</table>

<h2>Add Request</h2>
<?php 
		echo $this->Form->create('add_request', array(
			'controller' => 'requestdetails',
			'url' => '/requestdetails/add'
		));
		echo $this->Form->hidden('select_user_id', ['value' => $select_user_id]);
		echo $this->Form->end('Add');
		?>