<div class="center">
<h2>交通費管理システム</h2>

<?php
	echo $this->Form->create('Requestdetail', array(
			'action' => 'request_detail',
			'url' => '/request_details'
		));
	echo $this->Form->input('user_id', array(
		'type' => 'select',
		'options' => $select_users
		));
	echo $this->Form->end('Log In');
?>

<button type="button" value="signup">Sign Up</button>
</div>

<?php echo $this->Html->css('mystyle.css'); ?>
