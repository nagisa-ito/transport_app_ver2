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
            'is_admin' => $is_admin,
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
                    echo $this->Form->create('RequestDetail', ['class' => 'form_inline']);
                    echo $this->Form->hidden('RequestDetail.user_id', array('default' => $login_user_id));
                    echo $this->Form->input('RequestDetail.date', array(
                        'type'  => 'text',
                        'label' => array('text' => '日付'),
                        'id'    => 'datepicker',
                        'class' => 'form-control',
                    ));
                    echo $this->Form->input('RequestDetail.client', array(
                        'label'       => array('text' => 'クライアント名'),
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
                    echo $this->Form->input('RequestDetail.transportation_id', array(
                        'options' => $transportation_id_list,
                        'label'   => '交通手段',
                    ));
                    echo $this->Form->input('RequestDetail.from_station',  array(
                        'label'       => array('text' => '利用区間'),
                        'placeholder' => '乗車駅',
                        'class'       => 'form-control',
                    ));
                    echo $this->Form->input('RequestDetail.to_station', array(
                        'label'       => array('text' => ''),
                        'placeholder' => '降車駅',
                        'class'       => 'form-control',
                    ));
                    echo $this->Form->input('RequestDetail.cost', array(
                        'label' => array('text' => '費用'),
                        'class' => 'form-control',
                        'id' => 'cost',
                    ));
                ?>
                <div class="small note text-right">定期を考慮した金額を、片道の料金で入力してください</div>
                <label for="checkbox" class="check_css">定期</label>
                <input type="hidden" name="data[RequestDetail][is_season_ticket]" id="checkbox_" value="0">
                <input type="checkbox" name="data[RequestDetail][is_season_ticket]" id="checkbox" value="1">
                <?php
                    echo $this->Form->input('RequestDetail.oneway_or_round', array(
                        'type' => 'select',
                        'options' => $oneway_or_round,
                        'label' => '往復or片道',
                        'selected' => '片道',
                        'id' => 'set_oneway_or_round_edit',
                     ));
                     echo $this->Form->input('RequestDetail.overview', array(
                        'label' => array('text' => '備考'),
                        'class' => 'form-control',
                    ));
                ?>
                <div class="text-right">
                    <?php
                        echo $this->Form->button(__('編集'), ['class' => 'btn btn-purple mr-1']);
                        echo $this->Form->end();
                    ?>
                    <button type="button" onclick="history.back()" class="btn btn-white">キャンセル</button>
                </div>
            </div>
        </div>
    </div>

<?php echo $this->Html->script('search_section'); ?>