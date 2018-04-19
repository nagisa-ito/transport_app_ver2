<html>
<body>
    <header>
        <div class="row">
            <div class="col-sm-10"><h2>admin_user_lists</h2></div>
            <div class="col-sm-2">
                <button type="button" class="btn page-link text-dark d-inline-block" onclick="history.back()" >Back</button>
                <button class="btn page-link text-dark d-inline-block" onclick="location.href='<?php echo $this->html->url('/users/logout/'); ?>';">Logout</button>
            </div>
        </div>
    </header>

    <div class="content row">
        <div class="col-sm-6 offset-sm-3">
            <div class="admin_contents list-group">
                <li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">ユーザーを選択:</li>
                <?php foreach($users as $user) : ?>
                    <?php
                        if(isset($user['User']['role'])) continue;
                        $user_id = $user['User']['id'];
                        echo $this->Html->link($user['User']['yourname'], array('controller' => 'users', 'action' => "admin_user_requests/$user_id"),
                                                                          array('class' => 'list-group-item d-flex justify-content-between align-items-center gold'));
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <footer class="footer"></footer>

</body>
    <?php echo $this->Html->css('mystyle'); ?>
</html>
