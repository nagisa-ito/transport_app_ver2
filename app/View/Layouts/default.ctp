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
	   echo $this->Html->css('https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css');
	   echo $this->Html->script('https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js');

	   echo $this->fetch('meta');
	   echo $this->fetch('css');
	   echo $this->fetch('script');
   ?>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">

	<!--datepicker-->
	<!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script-->
	<?php echo $this->Html->css('bootstrap-datepicker.min'); ?>
	<?php echo $this->Html->script('bootstrap-datepicker.min'); ?>
	<?php echo $this->Html->script('bootstrap-datepicker.ja.min'); ?>
	
	<!--circliful-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/circliful/1.2.0/css/jquery.circliful.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/circliful/1.2.0/js/jquery.circliful.min.js"></script>



</head>
<body>
	<div id="container">
		<div id="header">
		</div>
		<div id="content">
			<?php echo $this->fetch('content'); ?>
		</div>

	</div>
	<?php echo $this->element('sql_dump'); ?>

<?php echo $this->Html->css('mystyle'); ?>
<?php echo $this->Html->script('myscript'); ?>

</body>
</html>
