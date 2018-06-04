<header>
    <div class="row">
        <div class="col-sm-11">
            <span class="badge badge-success">管理者</span>
            <h3 style="display: inline">交通費精算表</h3>
        </div>
        <div class="col-sm-1">
            <?php echo $this->Html->link('<button class="btn btn-block">Back</button>',
                                              array('controller' => 'users', 'action' => 'user_lists'),
                                              array('escape' => false));
            ?>
        </div>
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
                                                      array('controller' => 'requestdetails', 'action' => 'add', $login_user['id']),
                                                      array('escape' => false));
                    ?></div>
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <div id="requests-area">
                <h3>申請一覧</h3>
                <ul class="list-group">
                    <?php foreach($group_by_month as $each_month_request) : ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php
                                $print_date = $each_month_request['date'];
                                $print_date = date('Y年m月', strtotime($print_date));
                                $login_user_id = $login_user['id'];
                                $year_name = $each_month_request['date'];
                                echo $this->Html->link($print_date, array(
                                                                            'controller' => 'requestdetails',
                                                                            'action' => "/index/$login_user_id/$year_name/",
                                                                        ), ['class' => 'myset']);
                            ?>
                            <div class="pull-right">
                                ¥<?php echo number_format($each_month_request['sum(cost)']); ?>
                                <?php echo $this->Html->link($each_month_request['count']. '件', array(
                                                                                                        'controller' => 'requestdetails',
                                                                                                        'action' => "/index/$login_user_id/$year_name/",
                                                                                                        ), ['class' => 'myset']); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

<footer></footer>

<?php echo $this->Html->css('mystyle'); ?>
