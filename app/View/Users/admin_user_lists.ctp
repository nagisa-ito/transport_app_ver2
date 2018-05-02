    <header>
        <div class="row">
            <div class="col-sm-9">
                <span class="badge badge-success">管理者</span>
                <h4 style="display: inline">ユーザー一覧</h4>
            </div>
            <div class="col-sm-1">
                <?php echo $this->Html->link('<button class="btn">部署一覧</button>',
                                                  array('controller' => 'users', 'action' => 'index'),
                                                  array('escape' => false));
                ?>
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
                        echo $this->Form->input('date', ['label' => '', 'type' => 'text', 'id' => 'year_month', 'placeholder' => $search_year_month, 'class' => 'form-control']);
                        echo $this->Form->input('department_id', array(
                            'options' => $department_id_list,
                            'label' => false,
                            'empty' => '部署で絞り込む',
                            'class' => 'form-control'));
                        echo $this->Form->button(__('選択'), ['class' => 'btn btn-myset float-right']);
                        echo $this->Form->end();
                    ?>
                </div>
                <br>
                <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                    ユーザーを選択:
                </li>
                <?php foreach($users as $user) : ?>
                    <?php if(isset($user['role'])) continue; ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?php
                        $user_id = $user['id'];
                        echo $this->Html->link($user['yourname'], array('controller' => 'users', 'action' => "admin_user_requests/$user_id"),
                                                                          array('class' => 'myset',
                                                                                'id' => $user_id,
                                                                                'data-department_id' => $user['department_id']));
                        foreach($each_user_month_costs as $month_cost) {
                            if($month_cost['request_details']['user_id'] == $user_id && $month_cost[0]['date'] == $search_year_month) {
                                echo '¥' . number_format($month_cost[0]['total_cost']);
                            }
                        }
                    ?>
                    </li>
                <?php endforeach; ?>
            </div>
        </div>
    </div>


    <footer class="footer"></footer>
