    <header>
        <div class="row">
            <div class="col-sm-9">
                <span class="badge badge-success">管理者</span>
                <h3 style="display: inline">ユーザー一覧</h3>
            </div>
            <div class="col-sm-3 text-right">
                <button type="button" class="btn page-link text-dark d-inline-block" onclick="history.back()" >Back</button>
                <button class="btn btn-danger" onclick="location.href='<?php echo $this->html->url('/users/logout/'); ?>';">Logout</button>
            </div>
        </div>
    </header>

    <div class="text-center">
        <?php echo $this->Session->flash(); ?>
    </div>

    <div class="content row">
        <div class="col-sm-6 offset-sm-3">
            <div class="admin_contents list-group">
                <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">ユーザーを選択:</li>
                <?php foreach($users as $user) : ?>
                    <?php
                        if(isset($user['User']['role'])) continue;
                        $user_id = $user['User']['id'];
                        echo $this->Html->link($user['User']['yourname'], array('controller' => 'users', 'action' => "admin_user_requests/$user_id"),
                                                                          array('class' => 'list-group-item d-flex justify-content-between align-items-center myset'));
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>


    <footer class="footer"></footer>
