<html>
<body>
    <header><h4>新規登録</h4></header>

    <div id="content" class="text-center box24">
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>
    </div>


    <div class="content row">
        <div class="col-sm-6 offset-sm-3">
            <div class="form_contents">
                <?php
                    echo $this->Form->create('User', ['class' => 'form_inline']);
                    echo $this->Form->input('username', ['label' => ['text' => 'id'], 'placeholder' => '(firstname)_(lastname)', 'class' => 'form-control']);
                    echo $this->Form->input('password', ['label' => ['text' => 'パスワード'], 'placeholder' => '７文字以上', 'class' => 'form-control']);
                    echo $this->Form->input('password_confirm', ['label' => ['text' => 'パスワード(確認用)'], 'placeholder' => '同じパスワードを入力', 'class' => 'form-control', 'type' => 'password']);
                    echo $this->Form->input('yourname', ['label' => ['text' => 'ユーザー名'], 'placeholder' => '名前(漢字)', 'class' => 'form-control']);
                    echo $this->Form->input('department_id', array('options' => $department_id_list, 'label' => array('text' => '部署を選択')));
                    echo $this->Form->input('pass_from_station', ['label' => ['text' => '定期区間'], 'placeholder' => '乗車駅', 'class' => 'form-control']);
                    echo $this->Form->input('pass_to_station', ['label' => ['text' => ''], 'placeholder' => '降車駅', 'class' => 'form-control']);
                ?>
                <div class="row">
                    <div class="col-sm-7"></div>
                    <div class="col-sm-3">
                        <?php echo $this->Form->button(__('Add'), ['class' => 'btn btn-myset btn-block']); ?>
                        <?php echo $this->Form->end(); ?>
                    </div>
                    <div class="col-sm-2"><button type="button" class="btn page-link text-dark d-inline-block" onclick="history.back()" >Cancel</button></div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Html->css('mystyle'); ?>

    <footer></footer>
</body>
</html>
