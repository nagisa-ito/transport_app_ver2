<header>
    <?php
        echo $this->element('admin_header', array(
            'title' => '新規登録',
            'is_loggedIn' => 0,
            'is_admin' => 0,
        ));
    ?>
</header>

<div id="content" class="text-center box24">
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->fetch('content'); ?>
</div>

<div class="content row">
    <div class="col-sm-6 offset-sm-3">
        <div class="decor"></div>
        <div class="form_contents">
            <?php
                echo $this->Form->create('User', array('class' => 'form_inline'));
                echo $this->Form->input('username', array(
                    'label' => array('text' => 'id'),
                    'placeholder' => '(firstname)_(lastname)',
                    'class' => 'form-control',
                ));
                echo $this->Form->input('password', array(
                    'label' => array('text' => 'パスワード'),
                    'placeholder' => '７文字以上',
                    'class' => 'form-control',
                ));
                echo $this->Form->input('password_confirm', array(
                    'label' => array('text' => 'パスワード(確認用)'),
                    'placeholder' => '同じパスワードを入力',
                    'class' => 'form-control',
                    'type' => 'password',
                ));
                echo $this->Form->input('yourname', array(
                    'label' => array('text' => 'ユーザー名'),
                    'placeholder' => '名前(漢字)',
                    'class' => 'form-control',
                ));
                echo $this->Form->input('department_id', array(
                    'options' => $department_id_list,
                    'label' => array('text' => '部署を選択')
                ));
                echo $this->Form->input('pass_from_station', array(
                    'label' => array('text' => '定期区間'),
                    'placeholder' => '乗車駅',
                    'class' => 'form-control',
                ));
                echo $this->Form->input('pass_to_station', array(
                    'label' => array('text' => ''),
                    'placeholder' => '降車駅',
                    'class' => 'form-control',
                ));
            ?>
            <p class="small note text-right">定期がない場合、「なし」と登録してください</p>
            <div class="row">
                <div class="col-sm-12">
                    <a class="btn btn-white float-right" onclick="history.back()" >キャンセル</a>
                    <?php echo $this->Form->button(__('登録'), array('class' => 'btn btn-black-green float-right mr-2')); ?>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>



