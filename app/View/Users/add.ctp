<html>
<body>
    <header><h3>新規登録</h3></header>
    <div class="content row">
        <div class="col-sm-6 offset-sm-3">
            <div class="form_contents">
                <?php
                    echo $this->Form->create('User', ['class' => 'form_inline']);
                    echo $this->Form->input('username', ['label' => ['text' => 'id'], 'placeholder' => '(firstname)_(lastname)', 'class' => 'form-control']);
                    echo $this->Form->input('password', ['label' => ['text' => 'password'], 'placeholder' => '７文字以上', 'class' => 'form-control']);
                    echo $this->Form->input('yourname', ['label' => ['text' => 'username'], 'placeholder' => '名前(漢字)', 'class' => 'form-control']);
                    echo $this->Form->input('department_id', array('options' => $department_id_list));
                    echo $this->Form->input('pass_from_station', ['label' => ['text' => '定期区間'], 'placeholder' => '乗車駅', 'class' => 'form-control']);
                    echo $this->Form->input('pass_to_station', ['label' => ['text' => ''], 'placeholder' => '降車駅', 'class' => 'form-control']);
                ?>
                <div class="text-right">
                    <?php
                        echo $this->Form->button(__('Add Account'), ['class' => 'btn btn-danger']);
                        echo $this->Form->end();
                        ?>
                    <button type="button" class="btn page-link text-dark d-inline-block" onclick="history.back()" >Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->Html->css('mystyle'); ?>

    <footer></footer>
</body>
</html>