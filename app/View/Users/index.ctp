<?php echo $this->Html->css('user_index'); ?>

<header>
    <?php
        echo $this->element('admin_header', array(
            'title' => '交通費精算表',
            'is_loggedIn' => 1,
            'is_admin' => $this->params['admin'],
        ));
    ?>
</header>

<div class="text-center">
    <?php echo $this->Session->flash(); ?>
</div>

<div class="content row">
    <?php echo $this->element('profile_data'); ?>

    <div class="col-sm-9">
        <div id="requests-area">
            <h4>申請一覧</h4>
            <ul class="list-group">
                <?php foreach($group_by_month as $each_month_request) : ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <div>
                        <?php
                            $print_date = date('Y年m月', strtotime($each_month_request['date']));
                            $url = array(
                                    'controller' => 'request_details',
                                    'action' => 'index',
                                    $view_user_id,
                                    $each_month_request['date'],
                            );

                            //月ごとの詳細へのリンク
                            echo $this->Html->link($print_date, $url, array('class' => 'note mr-2'));

                            // 確定済みの場合
                            if ($each_month_request['is_confirm'] == true) {
                                echo $this->element('confirm_badge');
                            }
                        ?>
                        </div>
                        <div class="pull-right">
                            <?php
                                $param = array('class' => 'note ml-sm-2');
                                echo '¥' . number_format($each_month_request['total_cost']);
                                if($each_month_request['req_count'] != 0) {
                                    echo $this->Html->link($each_month_request['req_count']. '件', $url, $param);
                                } else {
                                    echo $this->Html->link($each_month_request['req_count']. '件', '#', $param);
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
        <h1>申請のない月を選択してください :</h1>
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
                        echo $this->Form->hidden('user_id', ['value' => $view_user_id, 'id' => 'no_request_user_id']);
                    ?>
                </div>
            </div>
            <p>
                確定してもよろしいですか？ 
                <span class="note small"><i class="fas fa-exclamation-triangle"></i>取り消しは出来ません</span>
            </p>
            <button class="btn">キャンセル</button>
            <?php
                echo $this->Html->link('確定','#', array(
                                            'escape' => false,
                                            'class' => 'btn btn-black-green',
                                            'id' => 'no_request',
                                        ));
            ?>
        </div>
    </div>
    <!---->

    <?php echo $this->Html->css('modal'); ?>
    <?php echo $this->Html->script('modal'); ?>
