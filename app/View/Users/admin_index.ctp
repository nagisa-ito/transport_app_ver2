<html>
<body>

    <header>
        <div class="row">
            <div class="col-sm-11"><h3><?php echo h('管理者用ページ'); ?></h3></div>
            <div class="col-sm-1">
                <button class="btn page-link text-dark d-inline-block" onclick="location.href='<?php echo $this->html->url('/users/logout/'); ?>';">Logout</button>
            </div>
        </div>
    </header>

    <div class="content row">
        <div class="col-sm-6 offset-sm-3">
            <div class="admin_contents list-group">
                <li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">部署を選択:</li>
                <?php foreach($departments as $key => $department) : ?>
                    <a><?php echo $this->Html->link($department, array('controller' => 'users', 'action' => "admin_user_lists/$key"),
                                                                 array('class' => 'list-group-item d-flex justify-content-between align-items-center gold'));
                    ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <footer></footer>

    <?php echo $this->Html->css('mystyle'); ?>

</body>
</html>
