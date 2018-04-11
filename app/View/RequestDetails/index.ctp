<h2>申請一覧</h2>

<button onClick = "location.href='<?php echo $this->html->url("/requestdetails/add/$login_user_id/$year_month");?>';">Add</button>
<button type="button" onclick="location.href='<?php echo $this->Html->url("/users/index/$login_user_id");?>';">Back</button>

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
						echo $this->Html->link('編集 ', array('action' => 'edit', $each_user_request_detail['RequestDetail']['id'], $login_user_id, $year_month));
						echo $this->Form->postlink('削除', array('action' => 'delete', $each_user_request_detail['RequestDetail']['id'], $login_user_id, $year_month), array('confirm' => '削除しますか？'));
						//echo $this->Html->link('削除', '#', array('class' => 'delete', 'data-post-id' => $each_user_request_detail['RequestDetail']['id']));
					?>
					 </td>
		    </tr>
	<?php endforeach; ?>

	<script>
		$(function() {
		    $('a.delete').click(function(e) {
		        if (confirm('sure?')) {
		            $.post('/blog/requestdetails/delete/'+$(this).data('post-id'), {}, function(res) {
		                $('#request_'+res.id).fadeOut();
		            }, "json");
		        }
		        return false;
		    });
		});
	</script>
</table>
