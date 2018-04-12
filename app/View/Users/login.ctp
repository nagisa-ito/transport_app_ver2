<header>
    <div>
        <button type="button" class="btn btn-default" onclick="location.href='<?php echo $this->html->url('/users/add/'); ?>';">新規登録</button>
    <div>
</header>

<body>
<div class="row">
    <div class="col-sm-4" id="login_form">
        <div><?php echo h('Please enter your username and password'); ?></div>
        <div>
            <?php
                  echo $this->Form->create('User');
                  echo $this->Form->input('username');
                  echo $this->Form->input('password');
                  echo $this->Form->end(__('Login'));
            ?>
        </div>
    </div>
</div>

<footer>
</footer>
<?php echo $this->Html->css('mystyle'); ?>
</body>
