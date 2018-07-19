<script type="text/javascript">
    var sections = <?php echo $sections; ?>;
    var stations = <?php echo $stations; ?>;
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
    <div class="col-sm-6 offset-sm-3">
        <div class="form_contents mt-4">
            <?php
                echo $this->Form->create();
                echo $this->Form->hidden('user_id', array('default' => $login_user_id));
                echo $this->Form->input('date', array(
                    'type'  => 'text',
                    'autocomplete' => 'off',
                    'label' => array('text' => '日付'),
                    'id'    => 'datepicker',
                    'class' => 'form-control',
                ));
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
                echo $this->Form->input('transportation_id', array(
                    'options' => $transportation_id_list,
                    'label'   => '交通手段',
                ));
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
            <div class="small note mb-2 text-right">定期を考慮した金額を、片道の料金で入力してください</div>
            <label for="checkbox" class="check_css">定期</label>
            <input type="hidden" name="data[RequestDetail][is_season_ticket]" id="checkbox_" value="0">
            <input type="checkbox" name="data[RequestDetail][is_season_ticket]" id="checkbox" value="1">
            <?php
                echo $this->Form->input('oneway_or_round', array(
                    'type' => 'select',
                    'options' => $oneway_or_round,
                    'label' => '往復or片道',
                    'selected' => '片道',
                    'id' => 'set_oneway_or_round_add',
                 ));
                 echo $this->Form->input('.overview', array(
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