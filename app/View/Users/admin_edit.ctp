<header>
    <?php
        echo $this->element('admin_header', array(
            'title' => 'プロフィール編集',
            'is_loggedIn' => 1,
            'is_admin' => $this->params['admin'],
        ));
    ?>
</header>

<div id="content" class="text-center box24">
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->fetch('content'); ?>
</div>

<div class="content row">
    <div class="col-sm-6 offset-sm-3">
        <div class="form_contents">
            <?php
                echo $this->Form->create('User', array('class' => 'form_inline'));
                echo $this->Form->input('User.username', array(
                    'label' => array('text' => 'メールアドレス'),
                    'class' => 'form-control',
                ));
                echo $this->Form->input('User.yourname', array(
                    'label' => array('text' => 'ユーザー名'),
                    'placeholder' => '名前(漢字)',
                    'class' => 'form-control',
                ));
                echo $this->Form->input('User.department_id', array(
                    'options' => $department_id_list,
                    'label' => array('text' => '部署を選択')
                ));
                echo $this->Form->hidden('User.role', array('default' => 0));
                echo $this->Form->input('User.pass_from_station', array(
                    'label' => array('text' => '定期区間'),
                    'placeholder' => '乗車駅',
                    'class' => 'form-control',
                ));
                echo $this->Form->input('User.pass_to_station', array(
                    'label' => array('text' => ''),
                    'placeholder' => '降車駅',
                    'class' => 'form-control',
                ));
            ?>
            <p class="small note text-right">定期がない場合、「なし」と登録してください</p>
            <?php
                echo $this->Form->input('User.status', array(
                    'label' => array('text' => 'ステータス'),
                    'options' => array(
                        1 => '在籍',
                        2 => '退社済',
                    ),
                    'class' => 'form-control',
                ));
            ?>
            <p class="small note text-right">ユーザー一覧で表示したくない場合はステータスを変更してください</p>
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
