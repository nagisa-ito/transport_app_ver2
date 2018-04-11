<div class="users form">

    <?php echo $this->Session->flash('auth'); ?>
    <?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo h('Please enter your username and password'); ?></legend>
        <?php echo $this->Form->input('username');
              echo $this->Form->input('password'); ?>
    </fieldset>
    <?php echo $this->Form->end(__('Login')); ?>
    <button onclick="location.href='<?php echo $this->html->url('/users/add/'); ?>';">新規登録</button>
</div>
