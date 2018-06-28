<?php
    echo $this->Html->css('login');
?>
<header>
    <?php
        echo $this->element('admin_header', array(
            'title' => '交通費精算アプリ',
            'is_loggedIn' => 0,
            'is_admin' => 0,
        ));
    ?>
</header>

    <div class="text-center">
        <?php echo $this->Session->flash(); ?>
    </div>

    <div class="content row">
        <div class="container">
            <div class="col-sm-6 offset-sm-3 text-center decor"></div>
            <div class="col-sm-6 offset-sm-3 text-center" id="login_form">
                <h2><?php echo h('Sign In'); ?></h2>
                <?php
                    echo $this->Form->create('User', array('class' => 'form-group'));
                    echo $this->Form->input('username', array(
                        'label' => array('text' => ''),
                        'placeholder' => 'id',
                        'class' => 'form-control',
                    ));
                    echo $this->Form->input('password', array(
                        'label' => array('text' => ''),
                        'placeholder' => 'password',
                        'class' => 'form-control',
                    ));
                    echo $this->Form->button(__('Sign In'), array(
                        'class' => 'btn mt-4 login-button',
                    ));
                    echo $this->Form->end();
                ?>
                <?php echo $this->Html->link(__('Create an Account'), array('action' => 'add'), array('class' => 'note small')); ?>
        </div>
    </div>

    <footer></footer>

