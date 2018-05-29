    <header>
        <div class="row">
            <div class="col-sm-10">
                <?php echo $this->element('admin_badge'); ?>
                <h4 style="display: inline">ユーザー一覧</h4>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-danger btn-block" onclick="location.href='<?php echo $this->html->url('/users/logout/'); ?>';">Logout</button>
            </div>
        </div>
    </header>

    <div class="text-center">
        <?php echo $this->Session->flash(); ?>
    </div>
<div id="test"></div>
    <div class="content row">
        <div class="col-sm-6 offset-sm-3">
            <div class="admin_contents list-group">
                <div>
                    <?php
                        echo $this->Form->create('User', ['url' => ['action' => "user_lists/$department_id"], 'type' => 'post', 'class' => "form-group"]);
                        echo $this->Form->input('date', ['label' => '', 'type' => 'text', 'id' => 'YearMonth', 'value' => $search_year_month, 'class' => 'form-control']);
                        echo $this->Form->input('department_id', array(
                            'options' => $department_id_list,
                            'label' => false,
                            'label' => '部署で絞り込む',
                            'class' => 'form-control',
                            'selected' => $department_id
                        ));
                        echo $this->Form->button(__('選択'), ['class' => 'btn btn-myset float-right']);
                        echo $this->Form->end();
                    ?>
                </div>
                <br>
                <table class="table">
                    <thead>
                        <tr class="table-success"><th colspan="2">ユーザーを選択:</th></tr>
                    </thead>
                    <?php foreach($users as $user) : ?>
                    <?php
                        $user_id = $user['id'];
                        if(($user['role']) == 'admin') continue;
                    ?>
                    <tbody>
                        <tr>
                            <td>
                              <?php
                                echo $this->Html->link($user['yourname'],
                                                 array( 'controller' => 'users',
                                                        'action' => "admin_user_requests",
                                                        $user['id'],
                                                        $user['department_id']),
                                                 array( 'class' => 'myset'));
                                ?>
                            </td>
                            <td class="text-right">
                                <?php
                                    foreach($each_user_month_costs as $month_cost) {
                                        if($month_cost['request_details']['user_id'] == $user_id
                                                           && $month_cost[0]['date'] == $search_year_month) {
                                        echo '¥' . number_format($month_cost[0]['total_cost']);
                                        }
                                    }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>


    <footer class="footer"></footer>
