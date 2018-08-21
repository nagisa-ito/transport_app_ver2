<?php echo $this->Html->css('reset_passwd');?>
<header>
    <?php
        echo $this->element('admin_header', array(
            'title' => 'パスワードをリセット',
            'is_loggedIn' => 0,
            'is_admin' => 0,
        ));
    ?>
</header>

<div class="text-center">
    <?php echo $this->Session->flash(); ?>
</div>

<div class="container">
    <div class="col-sm-6 offset-sm-3 decor"></div>
    <div class="col-sm-6 offset-sm-3" id="input_area">
        <?php
            echo $this->Form->create('Token', array('class' => 'form-horizontal'));
            echo $this->Form->input('mail_address', array(
                    'label' => array('text' => 'メールアドレス'),
                    'value' => '@e-grant.net',
                    'class' => 'form-control',
                    'div' => 'form-group',
                ));
        ?>
        <div class="text-right">
            <?php
                echo $this->Form->button(__('Reset Password!'), array(
                        'class' => 'btn btn-black-green',
                        'div' => 'form-control',
                    ));
                echo $this->Form->end();
                echo $this->Html->link('Cancel',
                    array('action' => 'login'),
                    array(
                        'class' => 'btn btn-white ml-3',
                        'role' => 'button',
                    )
                );
            ?>
        </div>
    </div>
</div>


