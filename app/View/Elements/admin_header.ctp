<div class="box">

    <div class="left-content">
        <i class="fas fa-subway fa-2x header-icon"></i>
        <div class="display-cell pl-2">
            <?php
                if($is_admin) {
                    echo $this->element('admin_badge');
                }
            ?>
        </div>
        <div class="title display-cell pl-2"><?php echo $title; ?></div>
    </div>
    
    <div class="right-content align-middle">
        <?php
            if($is_loggedIn) {
                echo $this->Html->link('<i class="fas fa-sign-out-alt mr-1"></i>ログアウト',
                                    array(
                                        'controller' => 'users',
                                        'action' => 'logout',
                                        'admin' => false,
                                    ),
                                    array(
                                        'class' => 'btn-black-pink float-right small ml-2',
                                        'escape' => false,
                ));
                if($is_admin) {
                    echo $this->Html->link('<i class="fas fa-users mr-1"></i>ユーザー一覧',
                                    array(
                                        'controller' => 'users',
                                        'action' => 'user_lists',
                                        'admin' => true,
                                    ),
                                    array(
                                        'class' => 'btn-black-green float-right small',
                                        'escape' => false,
                                    ));
                } else {
                    echo $this->Html->link('<i class="fas fa-home mr-1"></i>HOMEへ',
                        array(
                            'controller' => 'users',
                            'action' => 'index',
                        ),
                        array(
                            'class' => 'btn-black-green float-right small mr-2',
                            'escape' => false,
                        ));
                }
                echo $this->Html->link('<i class="fas fa-exchange-alt mr-1"></i>区間マスタ',
                        array(
                            'controller' => 'sections',
                            'action' => 'index',
                            $is_admin,
                        ),
                        array(
                            'class' => 'btn-black-pink float-right small mr-2',
                            'escape' => false,
                        ));
            }
        ?>
        <?php if($is_loggedIn) : ?>
            <a href='javascript: history.back()' class="btn-black-green float-right small mr-2">
                <i class="fas fa-backspace mr-1"></i>戻る
            </a>
        <?php endif; ?>
     </div>
    
</div>
