<div class="col-sm-3">
        <div class="profile-area" class="list-unstyled">
            <div>
                <h5 class="mr-2" style="display: inline"><b><?php echo h($login_user['yourname']); ?></b></h5>
                <?php
                    echo h($departments[$login_user['department_id']]);
                    echo $this->Html->link('<i class="fas fa-cog"></i>', array(
                        'controller' => 'users',
                        'action' => 'edit',
                        $login_user_id,
                    ),
                    array(
                        'escape' => false,
                        'class' => "float-right",
                        'id' => 'config_button',
                    ));
                ?>
            </div>
            <hr>
            <table class="table table-bordered text-center">
                <th>定期区間</th>
                <th><?php echo h($login_user['pass_from_station']); ?></th>
                <th><?php echo h($login_user['pass_to_station']); ?></th>
            </table>
            <div class="row mb-2 text-center">
                <div class="col-sm-8 offset-sm-2">
                    <?php echo $this->Html->link('<i class="fas fa-plus-circle"></i> 申請を追加', array(
                                'controller' => 'request_details',
                                'action' => 'add',
                                $login_user_id,
                            ),
                            array(
                                'class' => 'btn btn-purple',
                                'escape' => false,
                            ));
                    ?>
                </div>
            </div>
            <?php if($this->name === 'Users') :?>
                <div class="text-center">
                    <a href='#' class="small show-modal note">
                        <i class="fas fa-question mr-1"></i>申請が無い場合
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>