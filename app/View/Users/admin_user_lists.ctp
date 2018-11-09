<?php echo $this->Html->css('user_lists'); ?>

<header>
    <?php
        echo $this->element('admin_header', array(
            'title' => 'ユーザー一覧',
            'is_loggedIn' => 1,
            'is_admin' => 1,
        ));
    ?>
</header>

<div class="text-center">
    <?php echo $this->Session->flash(); ?>
</div>

<div class="content row">
    <div class="col-sm-6 offset-sm-3">
        <div class="list-group" id="user_list">
            <span class="heading">検索条件:</span>
            <div class="row contents">
                <div class="col-sm-5">
                    <?php
                        echo $this->Form->create('User', array(
                            'url'   => array('action' => "user_lists/$department_id"),
                            'type'  => 'post',
                            'class' => 'form-group',
                        ));
                        echo $this->Form->input('date', array(
                            'label' => '年月',
                            'type'  => 'text',
                            'id'    => 'YearMonth',
                            'value' => $search_year_month,
                            'class' => 'form-control',
                            'autocomplete' => 'off',
                        ));
                    ?>
                </div>
                <div class="col-sm-5">
                    <?php
                        echo $this->Form->input('department_id', array(
                            'options' => $department_id_list,
                            'label' => false,
                            'label' => '部署',
                            'class' => 'form-control',
                            'selected' => $department_id,
                        ));
                    ?>
                </div>
                <div class="col-sm-2 outer_select_button">
                    <?php
                        echo $this->Form->button(
                            '<i class="fas fa-search"></i> 検索',
                            array(
                                'class' => 'btn btn-green',
                                'escape' => false,
                            )
                        );
                        echo $this->Form->end();
                    ?>
                </div>
            </div>
            
            <span class="heading">ユーザー一覧:</span>
            <div class='contents text-right'>
                 <?php
                    echo $this->Html->link(
                        '<i class="fas fa-download"></i> CSVダウンロード',
                        array(
                            'controller' => 'users',
                            'action' => "admin_csv_download",
                            $department_id,
                            $search_year_month,
                        ),
                        array(
                            'class' => 'btn btn-green small',
                            'escape' => false
                        )
                    );
                ?>
            </div>
            <ul class="list-group contents mb-4">
                <?php foreach($each_user_monthly_costs as $user_monthly_cost) : ?>
                    <li class="list-group-item" style="display: inline-block;">
                        <?php
                            echo $this->Html->link($user_monthly_cost['username'], array(
                                'controller' => 'users',
                                'action' => 'admin_user_requests',
                                $user_monthly_cost['id'],
                                ),
                                array('class' => 'note mr-2')
                            );
                            if($user_monthly_cost['is_confirm'] == true) {
                                echo $this->element('confirm_badge');
                            }
                        ?>
                        <span class="float-right">
                            <?php echo '¥' . number_format($user_monthly_cost['total_cost']); ?>
                            <?php echo '(' . $user_monthly_cost['req_count'] . '件)'; ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

