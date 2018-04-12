<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
   <title>
	   <?php echo $cakeDescription ?>:
	   <?php echo $title_for_layout; ?>
   </title>
   <?php

	   echo $this->Html->meta('icon');

	   // jQuery CDN
	   echo $this->Html->script('//code.jquery.com/jquery-1.10.2.min.js');

	   // Twitter Bootstrap 3.0 CDN
	   echo $this->Html->css('//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/css/bootstrap.min.css');
	   echo $this->Html->script('//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc1/js/bootstrap.min.js');

	   echo $this->fetch('meta');
	   echo $this->fetch('css');
	   echo $this->fetch('script');
   ?>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
</head>
<body>
	<div id="container">
		<div id="header">
		</div>
		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<?php echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false)
				);
			?>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>

	<!-- ドットインストールで追加 -->
	<script>
		$(function(){
			setTimeout(function(){
				$('#flashMessage').fadeOut('slow');
			}, 800);
		});
	</script>

</body>
</html>
