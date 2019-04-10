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
    <script type="text/javascript" src=""></script>
    <?php
        echo $this->Html->meta('icon');

        // jQuery and jQueryUI
        echo $this->Html->script('jquery/jquery.min.js');
        echo $this->Html->script('jquery/jquery-ui.min.js');
        echo $this->Html->css('jquery/jquery-ui.min.css');

        // Twitter Bootstrap 4.0 and Datepicker
        echo $this->Html->script('bootstrap/bootstrap.min.js');
        echo $this->Html->css('bootstrap/bootstrap.min.css');
        echo $this->Html->css('bootstrap/bootstrap-datepicker.min');
        echo $this->Html->script('bootstrap/bootstrap-datepicker.min');
        echo $this->Html->script('bootstrap/bootstrap-datepicker.ja.min');

        //font awesome
        echo $this->Html->css('font-awesome/all.min.css');

        // fonts
        echo $this->Html->css('webfonts.css');

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
