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
                        echo $this->Form->create('User', ['url' =>
                                                         ['action' => "user_lists/$department_id"],
                                                          'type' => 'post',
                                                          'class' => "form-group"]);
                        echo $this->Form->input('date', ['label' => '',
                                                         'type' => 'text',
                                                         'id' => 'YearMonth',
                                                         'value' => $search_year_month,
                                                         'class' => 'form-control']);
                        echo $this->Form->input('department_id', array(
                                                         'options' => $department_id_list,
                                                         'label' => false,
                                                         'label' => '部署で絞り込む',
                                                         'class' => 'form-control',
                                                         'selected' => $department_id));
                        echo $this->Form->button(__('選択'), ['class' => 'btn btn-myset float-right']);
                        echo $this->Form->end();
                    ?>
                </div>
                <br>
                <table class="table">
                    <thead>
                        <tr class="table-success"><th colspan="2">ユーザーを選択:</th></tr>
                    </thead>
                    <?php foreach($each_user_monthly_costs as $user_monthly_cost) : ?>
                    <tbody>
                        <tr>
                            <td>
                              <?php
                                echo $this->Html->link($user_monthly_cost['yourname'],
                                                 array( 'controller' => 'users',
                                                        'action' => "admin_user_requests",
                                                        $user_monthly_cost['id'],
                                                        $user_monthly_cost['department_id']),
                                                 array( 'class' => 'myset'));
                                if($user_monthly_cost['is_confirm']) {
                                    echo $this->element('confirm_badge');
                                }
                                ?>
                            </td>
                            <td class="text-right">
                               <?php
                                    $cost =  empty($user_monthly_cost['total_cost']) ? 0 : $user_monthly_cost['total_cost'];
                                    echo '¥' . number_format($cost);
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
