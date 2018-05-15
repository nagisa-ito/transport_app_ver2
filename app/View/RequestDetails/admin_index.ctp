<header>
    <div class="row">
    <div class="col-sm-9">
        <span class="badge badge-success">管理者</span>
        <h4 style="display: inline">交通費精算表</h4>
    </div>
    <div class="col-sm-3 text-right">
        <?php echo $this->Html->link('<button class="btn btn-caution">ユーザー一覧</button>',
                                          array('controller' => 'users', 'action' => 'user_lists'),
                                          array('escape' => false));
        ?>
        <button type="button" class="btn page-link text-dark d-inline-block" onclick="history.back()" >Back</button>
    </div>
</header>

<div id="content" class="text-center box24">
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->fetch('content'); ?>
</div>

<div class="content row">
    <div class="col-sm-3 text-center">
        <div id="profile-area">
            <div class="list-unstyled">
                <li><?php echo h($login_user['yourname']); ?><li>
                <br>
                <li><?php echo h($departments[$login_user['department_id']]); ?></li>
                <br>
                <table class="table">
                    <th>定期区間</th>
                    <th><?php echo h($login_user['pass_from_station']); ?></th>
                    <th><?php echo h($login_user['pass_to_station']); ?></th>
                </table>
            </div>
            <div class="row">
                <div class="col-sm-6 offset-sm-3"><?php echo $this->Html->link('<button class="btn btn-myset btn-block">Add</button>',
                                                  array( 'action' => 'add', $login_user['id']),
                                                  array('escape' => false));
                ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-9">
        <div id="total_cost_area" class="row">
            <div class="col-sm-6">
                <div><h4><?php echo date('Y年m月', strtotime($each_user_request_details[0]['RequestDetail']['date']));?>分</h4></div>
                <br>
                <div><h5>合計金額</h5></div>
            </div>
            <div class="col-sm-6 pull-right">
                <div id="myStat"></div>
            </div>
        </div>
        <div id="request-details-area">
        <table class="table table-hover">
            <tr>
                <?php foreach ($column_names as $key => $column_name) : ?>
                    <?php
                        if($key != 4){
                            echo '<th>' . h($column_name) . '</th>';
                        } else {
                            echo '<th colspan="2">' . h($column_name) . '</th>';
                        }
                    ?>
                <?php endforeach; ?>
            </tr>
            <!--一覧をループで表示-->
            <?php foreach ($each_user_request_details as $each_user_request_detail) : ?>
                    <tr id="request_<?php echo h($each_user_request_detail['RequestDetail']['id']); ?>">
                        <td><?php echo h($each_user_request_detail['RequestDetail']['id']); ?></td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['date'])?></td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['client'])?></td>
                        <td><?php echo h($each_user_request_detail['Transportation']['transportation_name'])?></td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['from_station'])?></td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['to_station'])?></td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['cost'])?></td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['oneway_or_round'])?></td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['overview'])?></td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['created'])?></td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['modified'])?></td>
                        <td>
                            <?php
                                //編集・削除を実行
                                echo $this->Html->link('<i class="fas fa-edit"></i>',
                                    array('action' => 'edit', $each_user_request_detail['RequestDetail']['id'],$login_user_id, $year_month),
                                    array('escape' => false));
                                echo $this->Html->link('<i class="fas fa-trash-alt"></i>', '#',
                                    array('class' => 'delete',
                                        'data-request_id' => $each_user_request_detail['RequestDetail']['id'],
                                        'data-user_id' => $login_user_id,
                                        'data-year_month' => $year_month,
                                        'escape' => false));
                            ?>
                        </td>
                    </tr>
            <?php endforeach; ?>
        </table>
    </div>
    </div>
</div>

<footer></footer>

<?php $total_cost = '¥' . number_format($total_cost);?>
<script>
	var total_cost = '<?php echo $total_cost; ?>';
</script>

<?php echo $this->Html->script('detailsscript'); ?>
