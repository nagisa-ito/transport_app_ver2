<?php echo $this->Html->css('user_index'); ?>

<script type="text/javascript">
    var sections = <?php echo $sections; ?>;
    var stations = <?php echo $stations; ?>;
</script>

<header>
    <?php
        echo $this->element('admin_header', array(
            'title' => '交通費精算表',
            'is_loggedIn' => 1,
            'is_admin' => $this->params['admin'],
        ));
    ?>
</header>

<div id="content" class="text-center box24">
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->fetch('content'); ?>
</div>

<div class="content row">
    <div class="col-sm-6 offset-sm-3">
        <div class="form_contents mt-4">
            <?php
                echo $this->element('input_request', array(
                    'user_id' => $user_id,
                ));
            ?>
            <div class="text-right">
                <?php
                    echo $this->Form->button(__('変更を保存'), ['class' => 'btn btn-purple mr-1', 'name' => 'add']);
                    echo $this->Form->end();
                ?>
             <button type="button" onclick="history.back()" class="btn btn-white">キャンセル</button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Html->script('search_section'); ?>
