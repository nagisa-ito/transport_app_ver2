<?php echo $this->Html->css('user_index'); ?>

<header>
    <?php
        echo $this->element('admin_header', array(
            'title' => '交通費精算表',
            'is_loggedIn' => 1,
            'is_admin' => $is_admin,
        ));
    ?>
</header>

<div class="text-center">
    <?php echo $this->Session->flash(); ?>
</div>

<div class="content row">
    <div class="col-sm-3">
        <div class="profile-area" class="list-unstyled">
            <h5 class="mr-2" style="display: inline"><b><?php echo h($login_user['yourname']); ?></b></h5>
            <?php echo h($departments[$login_user['department_id']]); ?>
            <hr>
            <table class="table table-bordered text-center">
                <th>定期区間</th>
                <th><?php echo h($login_user['pass_from_station']); ?></th>
                <th><?php echo h($login_user['pass_to_station']); ?></th>
            </table>
            <div class="row mb-2 text-center">
                <div class="col-sm-8 offset-sm-2">
                    <?php echo $this->Html->link('<i class="fas fa-plus-circle"></i> 申請を追加', array(
                                'controller' => 'requestdetails',
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
            <div class="text-center">
                <a href='#' class="small show-modal note">申請が無い月を確定する</a>
            </div>
        </div>
    </div>

    <div class="col-sm-9">
        <div id="requests-area">
            <h4>申請一覧</h4>
            <ul class="list-group">
                <?php foreach($group_by_month as $each_month_request) : ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <div>
                        <?php
                            $print_date = $each_month_request['monthly_request_status']['date'];
                            $print_date = date('Y年m月', strtotime($print_date));
                            $year_name = $each_month_request['monthly_request_status']['date'];
                        ?>
                        <?php
                            if(!$each_month_request['monthly_request_status']['is_no_request']) {
                                echo $this->Html->link($print_date, array(
                                    'controller' => 'requestdetails',
                                    'action' => "/index/$login_user_id/$year_name/",
                                    ),
                                    array('class' => 'note mr-2')
                                );
                            } else {
                                echo $this->Html->link($print_date, '#', array('class' => 'note mr-2'));
                            }

                            if($each_month_request['monthly_request_status']['is_confirm'] == true) {
                                echo $this->element('confirm_badge');
                            }
                        ?>
                        </div>
                        <div class="pull-right">
                            <?php
                                if(!$each_month_request['monthly_request_status']['is_no_request']) {
                                    echo '¥ ' . number_format($each_month_request['monthly_request_status']['total_cost']) ;
                                    echo $this->Html->link($each_month_request['monthly_request_status']['count']. '件',
                                            array(
                                                    'controller' => 'requestdetails',
                                                    'action' => "/index/$login_user_id/$year_name/",
                                                ),
                                            array('class' => 'note ml-sm-2'));
                                } else {
                                    echo '¥ 0';
                                    echo $this->Html->link('0件', '#', array('class' => 'note ml-sm-2'));
                                }
                            ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

    <!--モーダルウィンドウ-->
    <div id="modal_window" class="modal_window text-center">
        <a href='#' class="modal_window_close"></a>
        <h1>確定したい月を選択してください :</h1>
        <div class="modal_window_contents">
            <div class="row">
                <div class="col-sm-6 offset-sm-3">
                    <?php
                        echo $this->Form->input('date',
                                                ['label' => '',
                                                  'type' => 'text',
                                                    'id' => 'no_request_month',
                                                 'class' => 'form-control',
                                                 'value' => date('Y-m')]);
                        echo $this->Form->hidden('user_id', ['value' => $login_user_id, 'id' => 'no_request_user_id']);
                    ?>
                </div>
            </div>
            <p>申請を確定してもよろしいですか？</p>
            <button class="btn">キャンセル</button>
            <?php
                echo $this->Html->link('<button type="button" class="btn btn-black-green" id="no_request">確定</button>',
                                        '#', array('escape' => false));
            ?>
        </div>
    </div>
    <!---->

    <?php echo $this->Html->css('modal'); ?>
    <?php echo $this->Html->script('modal'); ?>
