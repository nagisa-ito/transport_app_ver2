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

<div class="text-center">
    <?php echo $this->Session->flash(); ?>
</div>

<div class="content row">
    <?php echo $this->element('profile_data'); ?>

    <div class="col-sm-7">
        <?php echo $this->element('request_description_panel'); ?>
        <div class="form_contents panel_right_side">
           <?php
                echo $this->element('input_request', array(
                    'user_id' => $user['id'],
                ));
            ?>
            
            <div class="text-right">
            <?php
                echo $this->Form->button(__('続けて登録'), array(
                    'class' => 'btn btn-purple mr-1',
                    'name' => 'add_repeat')
                );
                echo $this->Form->button(__('登録'), ['class' => 'btn btn-purple mr-1', 'name' => 'add']);
                echo $this->Form->end();
            ?>
             <button type="button" onclick="history.back()" class="btn btn-white">キャンセル</button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->Html->script('search_section'); ?>
