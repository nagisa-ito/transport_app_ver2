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
    <div class="col-sm-6 offset-sm-3">
        <div class="form_contents mt-4">
            <?php
                echo $this->Form->create();
                echo $this->Form->hidden('user_id', array('default' => $user_id));
                echo $this->Form->input('date', array(
                    'type'  => 'text',
                    'autocomplete' => 'off',
                    'label' => array('text' => '日付'),
                    'id'    => 'datepicker',
                    'class' => 'form-control',
                ));
                echo $this->Form->input('trans_type', array(
                    'type' => 'select',
                    'options' => Configure::read('trans_category'),
                    'label' => '分類',
                ));
                echo $this->Form->input('transportation_id', array(
                    'type' => 'select',
                    'options' => $transportations,
                    'label'   => '交通手段',
                ));
                echo $this->Form->input('oneway_or_round', array(
                    'type' => 'select',
                    'options' => Configure::read('oneway_or_round'),
                    'label' => '経路',
                ));
            ?>

            <?php
                echo $this->Form->input('client', array(
                    'label'       => array('text' => '訪問先'),
                    'placeholder' => '訪問先が無い場合は空欄',
                    'class'       => 'form-control',
                    'id'          => 'company_autocomplete',
                ));
                echo $this->Form->button('<i class="fas fa-search mr-1"></i>区間検索', array(
                    'type' => 'button',
                    'class' => 'btn btn-green float-right',
                    'id' => 'search_travel_section',
                ));
            ?>
            <br>
            <?php
                echo $this->Form->input('from_station',  array(
                    'label'       => array('text' => '利用区間'),
                    'placeholder' => '乗車駅',
                    'class'       => 'form-control',
                    'id'          => 'from_station_autocomplete',
                ));
                echo $this->Form->input('to_station', array(
                    'label'       => array('text' => ''),
                    'placeholder' => '降車駅',
                    'class'       => 'form-control',
                    'id'    => 'to_station_autocomplete',
                ));
                echo $this->Form->input('cost', array(
                    'label' => array('text' => '費用'),
                    'class' => 'form-control',
                    'id' => 'cost',
                ));
            ?>
            <div class="small note mb-2 text-right">定期区間を含む場合は実際に払った金額を入力</div>

            <?php
                 echo $this->Form->input('overview', array(
                    'label' => array('text' => '備考'),
                    'class' => 'form-control',
                ));
            ?>
            <div id="test"></div>
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
