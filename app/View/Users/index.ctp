<div class="center">
<h2>交通費管理システム</h2>

<?php echo $this->Form->input('名前を選んでください :', array(
	'type' => 'select',
	'options' => $users1
	)); 
?>

<button type="button" value="login_username">Log In</button>
<button type="button" value="signup">Sign Up</button>

</div>

<?php echo $this->Html->css('mystyle.css'); ?>