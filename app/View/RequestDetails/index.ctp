<?php echo $this->Html->css('user_index'); ?>
<script>
    var year_month = "<?php echo $year_month; ?>";
    var user_id = "<?php echo $login_user_id; ?>";
</script>

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
    <?php echo $this->element('profile_data'); ?>

    <!--右の要素-->
    <div class="col-sm-9">
        <div id="total_cost_area">
            <div class="strong_str table-cell pr-2">
                <b><?php echo date('Y年m月', strtotime($each_user_request_details[0]['RequestDetail']['date']));?>分</b>
            </div>
            <div class="table-cell">
                <?php
                    if($is_confirm) {
                        echo $this->element('confirm_badge');
                    } else {
                        echo $this->Html->link('<i class="fas fa-clipboard-check mr-1"></i>確定する', '#',
                                    array(
                                        'class' => 'btn-black-green small',
                                        'id' => 'confirm_button',
                                        'escape' => false,
                        ));
                    }
                ?>
            </div>
            <div class="strong_str mt-3">合計金額</div>
            <h1 class="text-right numerals"><b id="total_cost"><?php echo '¥ ' . number_format($total_cost); ?></b></h1>
        </div>
        
        <div id="request-details-area">
        <table class="table">
            <thead class="thead">
            <tr>
                <?php foreach ($column_names as $key => $column_name) : ?>
                    <?php
                        if($key != 4){
                            echo '<th scope="col">' . h($column_name) . '</th>';
                        } else {
                            echo '<th scope="col" colspan="2">' . h($column_name) . '</th>';
                        }
                    ?>
                <?php endforeach; ?>
                <th scope="col">操作</th>
            </tr>
            </thead>
            <!--一覧をループで表示-->
            <tbody>
            <?php foreach ($each_user_request_details as $each_user_request_detail) : ?>
                <?php //削除フラグが1だったら表示しない
                    if($each_user_request_detail['RequestDetail']['is_delete']) { continue; }
                ?>
                    <tr id="request_<?php echo h($each_user_request_detail['RequestDetail']['id']); ?>">
                        <th scope="row"><?php echo h($each_user_request_detail['RequestDetail']['id']); ?></th>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['date'])?></td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['client'])?></td>
                        <td><?php echo h($each_user_request_detail['Transportation']['transportation_name'])?></td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['from_station'])?></td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['to_station'])?></td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['cost'])?></td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['oneway_or_round'])?></td>
                        <td>
                            <?php
                                echo $request_state =
                                    $each_user_request_detail['RequestDetail']['is_season_ticket']
                                    ? '定期代' : '営業交通費';
                            ?>
                        </td>
                        <td><?php echo h($each_user_request_detail['RequestDetail']['overview'])?></td>
                        <td>
                            <?php
                                //編集・削除を実行
                                echo $this->Html->link('<i class="fas fa-pen"></i>', array(
                                        'action' => 'edit',
                                        $each_user_request_detail['RequestDetail']['id'],
                                        $login_user_id,
                                        $year_month,
                                    ),
                                    array(
                                        'escape' => false,
                                        'class' => 'btn btn-purple mr-1 edit mb-1',
                                ));
                                echo $this->Html->link('<i class="fas fa-trash-alt"></i>', '#', array(
                                    'class' => 'delete btn btn-purple mb-1',
                                    'data-request_id' => $each_user_request_detail['RequestDetail']['id'],
                                    'data-user_id' => $login_user_id,
                                    'data-year_month' => $year_month,
                                    'escape' => false,
                                ));
                            ?>
                        </td>
                    </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </div>
    </div>
</table>

<?php $total_cost = '¥' . number_format($total_cost);?>
<script>
    var total_cost = '<?php echo $total_cost; ?>';
    var year_month2 = '<?php echo $year_month; ?>';
</script>

<?php echo $this->Html->script('detailsscript'); ?>
