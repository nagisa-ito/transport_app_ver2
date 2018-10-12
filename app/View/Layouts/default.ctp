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

$cakeDescription = __d('cake_dev', '交通費精算アプリ');
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
        echo $this->Html->script('//code.jquery.com/jquery-3.2.1.min.js');

        // Twitter Bootstrap 4.0 CDN
        echo $this->Html->css('https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css');
        echo $this->Html->script('https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js');
    
        //jQuery UI
        echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');
        echo $this->Html->css('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css');
    
        //font awesome
        echo $this->Html->css('https://use.fontawesome.com/releases/v5.1.0/css/all.css');
    
        //datepicker
        echo $this->Html->css('bootstrap-datepicker.min');
        echo $this->Html->script('bootstrap-datepicker.min');
        echo $this->Html->script('bootstrap-datepicker.ja.min');

        //google fonts
        echo $this->Html->css('https://fonts.googleapis.com/css?family=Lora');
        echo $this->Html->css('https://use.fontawesome.com/releases/v5.0.6/css/all.css');
        echo $this->Html->css('https://fonts.googleapis.com/css?family=Fugaz+One');

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
    ?>
</head>

<body>
    <div id="container">
        <div id="content">
            <?php echo $this->fetch('content'); ?>
        </div>
    </div>
    <?php echo $this->element('sql_dump'); ?>

<?php echo $this->Html->css('mystyle'); ?>
<?php echo $this->Html->script('myscript'); ?>

</body>
</html>
