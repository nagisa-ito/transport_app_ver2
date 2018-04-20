    <header></header>

    <div class="text-center">
        <?php echo $this->Session->flash(); ?>
    </div>

    <div class="content row">
        <div class="container">
            <div class="col-sm-6 offset-sm-3 text-center form_contents">
                <div id="login_title"><h2><?php echo h('Sign In'); ?></h2></div>
                <div id="login_form">
                    <?php
                        echo $this->Form->create('User', ['class' => 'form-group']);
                        echo $this->Form->input('username', ['label' => ['text' => ''], 'placeholder' => 'id', 'class' => 'form-control']);
                        echo $this->Form->input('password', ['label' => ['text' => ''], 'placeholder' => 'password', 'class' => 'form-control']);
                        echo $this->Form->button(__('Sign In'), ['class' => 'btn btn-danger btn-block mar-top', 'id' => 'login_button']);
                        echo $this->Form->end();
                    ?>
                </div>
                <?php echo $this->Html->link('Create an account', array('action' => 'add'), ['class' => 'small gold']); ?>
            </div>
        </div>
    </div>

    <footer></footer>
