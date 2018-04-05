<h2>申請一覧</h2>

<?php 
	echo $select_user_id;
	debug($test);
	debug($each_user_request_details);
	?>

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
		<?php foreach($each_user_request_detail['RequestDetail'] as $request) : ?>
	<tr>
			<td><?php echo h($request['id'])?></td>
			<td><?php echo h($request['date'])?></td>
			<td><?php echo h($request['client'])?></td>
			<td><?php echo h($request['transportation_id'])?></td>
			<td><?php echo h($request['from_station'])?></td>
			<td><?php echo h($request['to_station'])?></td>
			<td><?php echo h($request['cost'])?></td>
			<td><?php echo h($request['oneway_or_round'])?></td>
			<td><?php echo h($request['overview'])?></td>
			<td><?php echo h($request['created'])?></td>
			<td><?php echo h($request['modified'])?></td>
	</tr>
		<?php endforeach;?>
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


